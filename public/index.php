<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($class) {
    $baseDirs = [
        __DIR__ . '/../app/core/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/controllers/client/',
        __DIR__ . '/../app/controllers/admin/',
    ];
    foreach ($baseDirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$router = new Router();
require_once __DIR__ . '/../config/routes.php';
$router->dispatch();