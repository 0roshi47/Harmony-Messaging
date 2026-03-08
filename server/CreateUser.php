<?php

include_once "Connection.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); // specify allowed methods
header("Content-Type:application/json; charset=utf-8");//Indique auclient le format de la réponse

$postedData = file_get_contents('php://input');
$data = json_decode($postedData,true);

if (!is_array($data) || !key_exists("password", $data) || !key_exists("email", $data) || !key_exists("username", $data)) {
    http_response_code(400);
    $response = [
        'success' => false,
        'message' => 'Posted data incorrect'
    ];
    echo json_encode($response);
    return;
}

$pdo = Connection::getConnection();

$passwordHash = password_hash($data["password"], PASSWORD_DEFAULT);

$requete = 'insert into `Author`(password, email, username) values(:password, :email, :username)';
$statement = $pdo->prepare($requete);
$statement->execute([
    ':password' => $passwordHash,
    ':email' => $data["email"],
    ':username' => $data["username"],
]);
    
http_response_code(201);
$response = [
    'success' => true,
    'message' => 'User created successfully.'
    ];

echo json_encode($response);

?>