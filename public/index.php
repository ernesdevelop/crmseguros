<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
    if (PHP_SAPI === 'cli') {
        $tmpDir = sys_get_temp_dir();
        if (is_dir($tmpDir) && is_writable($tmpDir)) {
            @ini_set('session.save_path', $tmpDir);
        }
    }
    @session_start();
}

// 1. Autoload
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/../app/Core/Router.php';
    // Importante: si no usas Composer, tendrías que cargar cada controlador manualmente aquí
}

$config = require __DIR__ . '/../config/config.php';

function resolveBasePath(array $config): string
{
    if (!empty($config['basePath'])) {
        return '/' . trim((string) $config['basePath'], '/');
    }

    if (!empty($config['base_url'])) {
        $urlPath = (string) parse_url((string) $config['base_url'], PHP_URL_PATH);
        if ($urlPath !== '') {
            return '/' . trim($urlPath, '/');
        }
    }

    return '';
}

function isAuthenticated(): bool
{
    if (!isset($_SESSION['auth_user']) || !is_array($_SESSION['auth_user'])) {
        return false;
    }

    return isset($_SESSION['auth_user']['id'], $_SESSION['auth_user']['role']);
}

function redirectTo(array $config, string $path): void
{
    $basePath = resolveBasePath($config);
    $targetPath = '/' . ltrim($path, '/');
    header('Location: ' . rtrim($basePath, '/') . $targetPath);
    exit;
}

function isValidCsrfToken(?string $providedToken): bool
{
    $sessionToken = $_SESSION['csrf_token'] ?? null;
    if (!is_string($sessionToken) || $sessionToken === '') {
        return false;
    }

    if (!is_string($providedToken) || $providedToken === '') {
        return false;
    }

    return hash_equals($sessionToken, $providedToken);
}

function renderServerErrorPage(array $config, \Throwable $e): void
{
    http_response_code(500);

    $appName = $config['app_name'] ?? 'CRM Seguros';
    $basePath = resolveBasePath($config);
    $title = 'Error interno del servidor';
    $message = 'Se produjo un error inesperado. Intentá nuevamente en unos minutos.';
    $details = ini_get('display_errors') ? $e->getMessage() : '';
    $viewFile = __DIR__ . '/../app/Views/errors/500.php';

    if (file_exists($viewFile)) {
        require $viewFile;
        return;
    }

    echo '500 - Error interno del servidor';
}

try {
    $router = new \App\Core\Router();

    $router->add('GET', '/', [\App\Controllers\DashboardController::class, 'index']);
    $router->add('GET', '/login', [\App\Controllers\AuthController::class, 'showLogin']);
    $router->add('POST', '/login', [\App\Controllers\AuthController::class, 'login']);
    $router->add('POST', '/logout', [\App\Controllers\AuthController::class, 'logout']);

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

    $router->add('GET', '/admin/tools', [\App\Controllers\AdminController::class, 'tools']);
    $router->add('POST', '/admin/backup/download', [\App\Controllers\AdminController::class, 'downloadBackup']);

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $providedCsrf = $_POST['_csrf'] ?? null;
        if (!isValidCsrfToken(is_string($providedCsrf) ? $providedCsrf : null)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Token de seguridad inválido. Intentá nuevamente.',
            ];
            redirectTo($config, isAuthenticated() ? '/' : '/login');
        }
    }

    $isAuth = isAuthenticated();
    $publicRoutes = ['/login'];
    $isPublicRoute = in_array($uri, $publicRoutes, true);

    if (!$isAuth && !$isPublicRoute) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Debés iniciar sesión para acceder al sistema.',
        ];
        redirectTo($config, '/login');
    }

    if ($isAuth && $uri === '/login' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        redirectTo($config, '/');
    }

    $router->dispatch($_SERVER['REQUEST_METHOD'], $uri, $config);

} catch (\Throwable $e) {
    renderServerErrorPage($config, $e);
}
