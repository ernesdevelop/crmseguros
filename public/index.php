<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Autoload
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/../app/Core/Router.php';
    // Importante: si no usas Composer, tendrías que cargar cada controlador manualmente aquí
}

$config = require __DIR__ . '/../config/config.php';

try {
    $router = new \App\Core\Router();

    $router->add('GET', '/', [\App\Controllers\DashboardController::class, 'index']);

    $router->add('GET', '/users', [\App\Controllers\UserController::class, 'index']);
    $router->add('POST', '/users/store', [\App\Controllers\UserController::class, 'store']);

    $router->add('GET', '/clients', [\App\Controllers\ClientController::class, 'index']);
    $router->add('POST', '/clients/store', [\App\Controllers\ClientController::class, 'store']);
    $router->add('POST', '/clients/update', [\App\Controllers\ClientController::class, 'update']);
    $router->add('POST', '/clients/delete', [\App\Controllers\ClientController::class, 'delete']);

    $router->add('GET', '/insurers', [\App\Controllers\InsurerController::class, 'index']);
    $router->add('POST', '/insurers/store', [\App\Controllers\InsurerController::class, 'store']);

    $router->add('GET', '/policies', [\App\Controllers\PolicyController::class, 'index']);
    $router->add('POST', '/policies/store', [\App\Controllers\PolicyController::class, 'store']);

    $router->add('GET', '/renewals', [\App\Controllers\RenewalController::class, 'index']);

    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    $base = '';
    if (!empty($config['basePath'])) {
        $base = '/' . trim((string) $config['basePath'], '/');
    } elseif (!empty($config['base_url'])) {
        $base = '/' . trim((string) parse_url((string) $config['base_url'], PHP_URL_PATH), '/');
    }

    if ($base !== '' && strpos($uri, $base) === 0) {
        $uri = substr($uri, strlen($base));
    }

    // Aseguramos que la ruta sea limpia para el Router
    $uri = '/' . trim($uri, '/');
    if ($uri === '//') {
        $uri = '/';
    }

    $router->dispatch($_SERVER['REQUEST_METHOD'], $uri, $config);

} catch (\Throwable $e) {
    echo "Error fatal: " . $e->getMessage();
}
