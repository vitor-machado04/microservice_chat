<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Router;

header("Content-Type: application/json; charset=UTF-8");

$router = new Router();
$router->handleRequest();
