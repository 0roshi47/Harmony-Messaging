<?php

require_once "jwt_utils.php";
require_once "ConstantDatas.php";
require_once "Connection.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS"); // specify allowed methods
header(header: "Access-Control-Allow-Headers: Content-Type, Authorization"); // specify allowed headers
header("Content-Type:application/json; charset=utf-8");//Indique auclient le format de la réponse

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; //empêche le traitement quand ajax envoie la preflight requête
}

$bearer_token = get_bearer_token();

if (!$bearer_token || !is_jwt_valid($bearer_token, JWT_SECRET)) {
    http_response_code(401);
    echo json_encode("Bearer token invalid or not supplied.");
    exit;
}

$payload = extract_payload($bearer_token, JWT_SECRET);
$payloadData = json_decode($payload, true);

$postedData = file_get_contents('php://input');
$data = json_decode($postedData,true);

if (!is_array($data) || !key_exists("roomName", $data)) {
    http_response_code(400);
    $response = [
        'success' => false,
        'message' => 'Posted data incorrect'
    ];
    echo json_encode($response);
    exit;
}

$pdo = Connection::getConnection();

//verifie si le nom de la room est déjà pris
$roomNameExist = 'select * from Room where roomName = :roomName';
$statement = $pdo->prepare($roomNameExist);
$statement->execute([
    ':roomName' => $data["roomName"],
]);
$result = $statement->fetch(\PDO::FETCH_ASSOC);

if ($result) {
    http_response_code(400);
    $response = [
        'success' => false,
        'message' => 'Room name already exist.'
    ];
    echo json_encode($response);
    exit;
}

$requete = 'insert into Room(roomName, authorId) values(:roomName, :authorId)';
$statement = $pdo->prepare($requete);
$statement->execute([
    ':roomName' => $data["roomName"],
    ':authorId' => $payloadData["authorId"],
]);
    
http_response_code(201);
$response = [
    'success' => true,
    'message' => 'Room created successfully.'
];

echo json_encode($response);

?>