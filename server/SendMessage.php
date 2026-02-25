<?php

include_once "Connection.php";

$postedData = file_get_contents('php://input');
$data = json_decode($postedData,true);

$currentDate = date("Y-m-d H:i:s");

$pdo = Connection::getConnection();

echo $postedData;

// return;

$requete = 'INSERT INTO Message (content, author, postDate) VALUES (:content, :author, :postDate)';
$statement = $pdo->prepare($requete);
$statement->execute([
    ':content' => $data["content"],
    ':author' => $data["author"],
    ':postDate' => $currentDate,
]);


?>