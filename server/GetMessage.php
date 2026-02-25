<?php

include_once "Connection.php";

$pdo = Connection::getConnection();

$requete = 'select * from Message order by postDate limit 10';
$statement = $pdo->prepare($requete);
$statement->execute();
$result = $statement->fetchAll(\PDO::FETCH_ASSOC);

http_response_code(200);
echo json_encode($result);

?>