<?php

require_once "jwt_utils.php";
require_once "ConstantDatas.php";
require_once "Connection.php";

function incorrectData() {
    http_response_code(400);
    $response = [
        'success' => false,
        'message' => "Password or email provided doesn't exist." //on precise pas que uniquement le mail est mauvais pour des raisons de securité
    ];
    echo json_encode($response);
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); // specify allowed methods
header("Content-Type:application/json; charset=utf-8");//Indique auclient le format de la réponse

$postedData = file_get_contents('php://input');
$data = json_decode($postedData,true);

if (!is_array($data) || !key_exists("password", $data) || !key_exists("email", $data)) {
    http_response_code(400);
    echo json_encode('Posted data incorrect');
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
    'authorId' => $result["authorId"],
    'username' => $result["username"],
    'exp' => (time() + JWT_TOKEN_DURATION_SECONDS)
);

$jwt = generate_jwt($headers, $payload, JWT_SECRET);

http_response_code(200);
echo json_encode($jwt);

?>