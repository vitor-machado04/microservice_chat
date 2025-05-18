<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env');
    $_ENV = array_merge($_ENV, $env);
}

use App\Router;

header("Content-Type: application/json; charset=UTF-8");

$router = new Router();
$router->handleRequest();
