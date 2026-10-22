<?php
require_once __DIR__ . '/config/bootstrap.php';
$router = new App\Core\Router();
$router->dispatch();
