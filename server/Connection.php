<?php

require_once "ConstantDatas.php";

class Connection {
    
    //constant are defined in an ignored file
    private static String $host = DB_HOST;
    private static String $port = "3306";
    private static String $db = DB_NAME;
    private static String $user = DB_USER;
    private static String $password = DB_PASSWORD;
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (!isset(self::$connection)) {
            try {
                self::$connection = new PDO("mysql:host=".Connection::$host.";port=".Connection::$port.";dbname=".Connection::$db, Connection::$user, Connection::$password);
            } catch (PDOException $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function deconnection(): void {
        Connection::$connection = null;
    }
}
?>