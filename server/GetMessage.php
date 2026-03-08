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

if (!isset($_GET['room'])) {
    http_response_code(400);
    echo json_encode("Paramètre limit incorrecte");
    return;
}

$room = $_GET['room'];

$requete = 'select Message.*, Author.username from Message, Author where Author.authorId = Message.authorId and Message.roomId = :room order by Message.postDate DESC';
    
if (isset($_GET['limit'])) { //paramètre optionnel
    $limit = $_GET['limit'];
    if (!is_numeric($limit)) {
        http_response_code(400);
        echo json_encode("Incorrect limit parameter");
        return;
    }
    $requete = $requete.' limit '.$limit; //ajoute une limit à la requête pour get les n derniers messages
}

$statement = $pdo->prepare($requete);
$statement->execute([
    ':room' => $room,
]);
$result = $statement->fetchAll(\PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode($result);

?>