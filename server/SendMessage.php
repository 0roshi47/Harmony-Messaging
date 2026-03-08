<?php

include_once "Connection.php";
include_once "ConstantDatas.php";
include_once "jwt_utils.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS"); // specify allowed methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // specify allowed headers
header("Content-Type:application/json; charset=utf-8");//Indique auclient le format de la réponse

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; //empêche le traitement quand ajax envoie la preflight requête
}

$bearer_token = get_bearer_token();

if (!$bearer_token || !is_jwt_valid($bearer_token, JWT_SECRET)) {
    http_response_code(401);
    echo json_encode("Bearer token invalid or not supplied.");
    return;
}

$payload = extract_payload($bearer_token, JWT_SECRET);
$payloadData = json_decode($payload, true);

$postedData = file_get_contents('php://input');
$data = json_decode($postedData,true);

if (!is_array($data) || !key_exists("content", $data) || !key_exists("room", $data)) {
    http_response_code(400);
    echo json_encode('Posted data incorrect');
    return;
}

$currentDate = date("Y-m-d H:i:s");

$pdo = Connection::getConnection();

$requete = 'INSERT INTO Message (content, postDate, authorId, roomId) VALUES (:content, :postDate, :authorId, :roomId)';
$statement = $pdo->prepare($requete);
$statement->execute([
    ':content' => $data['content'],
    ':postDate' => $currentDate,
    ':authorId' => $payloadData['authorId'],
    ':roomId' => $data['room']
]);
    
http_response_code(201);
echo json_encode("Message sent successfully.");

?>