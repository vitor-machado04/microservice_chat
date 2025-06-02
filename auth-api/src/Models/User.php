<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class User
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getConnection();
    }

    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($name, $lastName, $email, $password)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO users (name, last_name, email, password) VALUES (:name, :lastName, :email, :password)"
        );
        return $stmt->execute([
            'name' => $name,
            'lastName' => $lastName,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function testConnection()
    {
        $stmt = $this->conn->query("SELECT 1");
        return $stmt->fetch();
    }
}
