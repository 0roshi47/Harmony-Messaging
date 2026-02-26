<?php

include_once "Connection.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); // specify allowed methods
header("Content-Type:application/json; charset=utf-8");//Indique auclient le format de la réponse

$postedData = file_get_contents('php://input');
$data = json_decode($postedData,true);

$currentDate = date("Y-m-d H:i:s");

$pdo = Connection::getConnection();

$requete = 'INSERT INTO Message (content, author, postDate) VALUES (:content, :author, :postDate)';
$statement = $pdo->prepare($requete);
$statement->execute([
    ':content' => $data["content"],
    ':author' => $data["author"],
    ':postDate' => $currentDate,
    ]);
    
http_response_code(201);
$response = [
    'success' => true,
    'message' => 'Message posted successfully.'];

echo json_encode($response);

?>