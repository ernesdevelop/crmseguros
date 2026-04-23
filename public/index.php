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

   $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = '/crmseguros/public';

if (strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}

// Aseguramos que la ruta sea limpia para el Router
$uri = '/' . trim($uri, '/');

$router->dispatch($_SERVER['REQUEST_METHOD'], $uri, $config);

} catch (\Throwable $e) {
    echo "Error fatal: " . $e->getMessage();
}
