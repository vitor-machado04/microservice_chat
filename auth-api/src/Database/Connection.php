<?php
namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    private static $conn;

    public static function getConnection()
    {
        if (!self::$conn) {
            $host = 'db';
            $db = 'chat_app';
            $user = 'user';
            $pass = 'password';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                self::$conn = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(["message" => "Erro na conexão com o banco"]);
                exit;
            }
        }
        return self::$conn;
    }
}
