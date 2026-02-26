<?php

class Connection {
    
    private static String $host = "mysql-harmony-messaging-backend.alwaysdata.net";
    private static String $port = "3306";
    private static String $db = "harmony-messaging-backend_db";
    private static String $user = "harmony-messaging-backend";
    private static String $password = "tw9<0(M,HB\hR=>*";
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