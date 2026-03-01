<?php

include_once "Connection.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json; charset=utf-8");//Indique auclient le format de la réponse

$pdo = Connection::getConnection();

$requete = 'select * from Message order by postDate DESC';

if (isset($_GET['limit'])) {
    $limit = $_GET['limit'];
    if (!is_numeric($limit)) {
        http_response_code(400);
        echo json_encode("Paramètre limit incorrecte");
        return;
    }
    $requete = $requete.' limit '.$_GET['limit']; //ajoute une limit à la requête pour get les n derniers messages
}

$statement = $pdo->query($requete);
$result = $statement->fetchAll(\PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode($result);

?>