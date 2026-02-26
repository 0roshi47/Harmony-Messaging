<?php

include_once "Connection.php";
header("Access-Control-Allow-Origin: *");

if (!isset($_GET['limit'])) {
    http_response_code(400);
    echo "Requête incorrect, veuillez préciser la limite de message à get";
    return;
}

$limit = $_GET['limit'];

$pdo = Connection::getConnection();

$requete = 'select * from Message order by postDate DESC limit '.$limit;
$statement = $pdo->query($requete);
$result = $statement->fetchAll(\PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode($result);

?>