<?php

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$config = require __DIR__ . '/../config/config.php';

$router = new App\Core\Router();

$router->add('GET', '/', [App\Controllers\DashboardController::class, 'index']);

$router->add('GET', '/users', [App\Controllers\UserController::class, 'index']);
$router->add('POST', '/users/store', [App\Controllers\UserController::class, 'store']);

$router->add('GET', '/clients', [App\Controllers\ClientController::class, 'index']);
$router->add('POST', '/clients/store', [App\Controllers\ClientController::class, 'store']);
$router->add('POST', '/clients/update', [App\Controllers\ClientController::class, 'update']);
$router->add('POST', '/clients/delete', [App\Controllers\ClientController::class, 'delete']);

$router->add('GET', '/insurers', [App\Controllers\InsurerController::class, 'index']);
$router->add('POST', '/insurers/store', [App\Controllers\InsurerController::class, 'store']);

$router->add('GET', '/policies', [App\Controllers\PolicyController::class, 'index']);
$router->add('POST', '/policies/store', [App\Controllers\PolicyController::class, 'store']);

$router->add('GET', '/renewals', [App\Controllers\RenewalController::class, 'index']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $config);
