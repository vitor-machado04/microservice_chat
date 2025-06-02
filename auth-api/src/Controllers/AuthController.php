<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\JwtService;

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function getToken()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? null;
        $userId = $_GET['user'] ?? null;

        if (!$token || !$userId) {
            http_response_code(401);
            echo json_encode(['auth' => false]);
            return;
        }

        $decoded = JwtService::validateToken($token);

        if (!$decoded) {
            echo json_encode(['auth' => false]);
            return;
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['auth' => false]);
            return;
        }

        if ($decoded->userId == $user['user_id']) {
            echo json_encode(['auth' => true]);
        } else {
            echo json_encode(['auth' => false]);
        }
    }
    public function postToken()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['token' => false]);
            return;
        }

        $user = $this->userModel->findByEmail($data['email']);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['token' => false]);
            return;
        }

        if (!password_verify($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(['token' => false]);
            return;
        }

        $token = JwtService::generateToken(['userId' => $user['user_id'], 'password' => $user['password']]);

        echo json_encode(['token' => $token]);
    }

    public function postUser()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['name'], $data['lastName'], $data['email'], $data['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados incompletos']);
            return;
        }

        $exists = $this->userModel->findByEmail($data['email']);
        if ($exists) {
            http_response_code(409);
            echo json_encode(['message' => 'Usuário já existe']);
            return;
        }

        $created = $this->userModel->create($data['name'], $data['lastName'], $data['email'], $data['password']);

        if ($created) {
            http_response_code(201);
            echo json_encode([
                'message' => 'ok',
                'user' => [
                        'name' => $data['name'],
                        'lastName' => $data['lastName'],
                        'email' => $data['email']
                    ]
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Erro ao criar usuário']);
        }
    }

    public function getUser()
    {
        $email = $_GET['email'] ?? null;

        if (!$email) {
            http_response_code(400);
            echo json_encode(['message' => 'Email obrigatório']);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user) {
            unset($user['password']);
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Usuário não encontrado']);
        }
    }

    public function health()
    {
        try {
            $this->userModel->testConnection();

            http_response_code(200);
            echo json_encode(['status' => 'healthy', 'database' => 'connected']);
        } 
        catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'unhealthy', 'database' => 'disconnected', 'error' => $e->getMessage()]);
        }
    }
}
