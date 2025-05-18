<?php
namespace App;

use App\Controllers\AuthController;

class Router {
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Limpa a URI para tirar /public (dependendo do servidor)
        $uri = str_replace('/auth-api/public', '', $uri);

        if ($uri === '/token' && $method === 'GET') {
            (new AuthController())->getToken();
        } 
        elseif ($uri === '/token' && $method === 'POST') {
            (new AuthController())->postToken();
        } 
        elseif ($uri === '/user' && $method === 'POST') {
            (new AuthController())->postUser();
        } 
        elseif ($uri === '/user' && $method === 'GET') {
            (new AuthController())->getUser();
        } 
        else {
            http_response_code(404);
            echo json_encode(["message" => "Endpoint não encontrado"]);
        }
    }
}
