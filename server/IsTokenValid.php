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

if (!$bearer_token) {
    http_response_code(400);
    echo json_encode("Bearer token not supplied.");
    return;
}

http_response_code(200);
echo json_encode(['valid' => is_jwt_valid($bearer_token, JWT_SECRET)]);

?>