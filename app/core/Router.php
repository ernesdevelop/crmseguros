<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $uri, array $config): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo '404 - Ruta no encontrada';
            return;
        }

        [$controllerClass, $action] = $this->routes[$method][$path];
        $controller = new $controllerClass($config);
        $controller->$action();
    }
}
