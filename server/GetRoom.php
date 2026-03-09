<?php

include_once "Connection.php";
include_once "ConstantDatas.php";
include_once "jwt_utils.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS"); // specify allowed methods
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

$pdo = Connection::getConnection();

//requete qui renvoie les rooms avec les messages les plus récents d'abord
$requete = 'SELECT r.*, MAX(m.postDate) AS lastMessageDate FROM Room r LEFT JOIN Message m ON r.roomId = m.roomId GROUP BY r.roomId, r.roomName ORDER BY lastMessageDate ASC';

$statement = $pdo->prepare($requete);
$statement->execute();
$result = $statement->fetchAll(\PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode($result);

?>