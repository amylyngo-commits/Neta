<?php
declare(strict_types=1);
$config = require __DIR__ . '/config.php';
date_default_timezone_set($config['timezone']);
session_start();
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) return;
    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($path)) require $path;
});
require_once __DIR__ . '/../app/Core/helpers.php';
