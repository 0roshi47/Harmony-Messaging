<?php

require_once "jwt_utils.php";

define("JWT_SECRET", '<7:DPcH]/d)\OpI}');
define("JWT_TOKEN_DURATION_SECONDS", 60*60*24); //24 heures

function incorrectData() {
    http_response_code(400);
    $response = [
        'success' => false,
        'message' => "Password or email provided doesn't exist." //on precise pas que uniquement le mail est mauvais pour des raisons de securité
    ];
    echo json_encode($response);
}

$USER_PASSWORD_HASH_KEY = '<7:DPcH]/d)\OpI}';

include_once "Connection.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); // specify allowed methods
header("Content-Type:application/json; charset=utf-8");//Indique auclient le format de la réponse

$postedData = file_get_contents('php://input');
$data = json_decode($postedData,true);

if (!is_array($data) || !key_exists("password", $data) || !key_exists("email", $data)) {
    http_response_code(400);
    $response = [
        'success' => false,
        'message' => 'Posted data incorrect'
    ];
    echo json_encode($response);
    return;
}

$pdo = Connection::getConnection();


$requete = 'select * from Author where email = :email';
$statement = $pdo->prepare($requete);
$statement->execute([
    ':email' => $data["email"],
]);
$result = $statement->fetch(\PDO::FETCH_ASSOC);

if (!$result) {
    incorrectData();
    return;
}

if (!password_verify($data["password"], $result["password"])) {
    incorrectData();
    return;
}

$headers = array('alg' => 'HS256', 'typ' => 'JWT');
$payload = array(
    'authorId' => $data["authorId"],
    'username' => $data["username"],
    'exp' => (time() + JWT_TOKEN_DURATION_SECONDS)
);

$jwt = generate_jwt($headers, $payload, JWT_SECRET);

http_response_code(200);
$response = [
    'success' => true,
    'message' => $jwt
];

echo json_encode($response);

?>