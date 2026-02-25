<?php

class Connection {
    
    private static String $host = "localhost";
    private static String $port = "3306";
    private static String $db = "harmony_db";
    private static String $user = "harmony_connexion";
    private static String $password = "4\$j;[F+.c$1KrGEa";
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