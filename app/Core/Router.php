<?php
namespace App\Core;

use RuntimeException;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $uri, array $config): void
    {
        $path = $uri;

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            $this->renderErrorPage(404, 'Ruta no encontrada', 'La URL solicitada no existe.', $config, $method, $path);
            return;
        }

        [$controllerClass, $action] = $this->routes[$method][$path];

        if (!class_exists($controllerClass)) {
            throw new RuntimeException("La clase controlador '$controllerClass' no existe.");
        }

        $controller = new $controllerClass($config);
        if (!method_exists($controller, $action)) {
            throw new RuntimeException("La acción '$action' no existe en '$controllerClass'.");
        }

        $controller->$action();
    }

    private function renderErrorPage(
        int $statusCode,
        string $title,
        string $message,
        array $config,
        string $method,
        string $path
    ): void {
        $appName = $config['app_name'] ?? 'CRM Seguros';
        $basePath = $this->resolveBasePath($config);
        $viewFile = __DIR__ . '/../Views/errors/404.php';

        if (file_exists($viewFile)) {
            require $viewFile;
            return;
        }

        echo sprintf('%d - %s (%s %s)', $statusCode, $title, $method, $path);
    }

    private function resolveBasePath(array $config): string
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

}
