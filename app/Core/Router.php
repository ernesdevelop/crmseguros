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
    // Eliminamos parse_url de aquí porque ya la procesamos en index.php
    $path = $uri; 

    if (!isset($this->routes[$method][$path])) {
        http_response_code(404);
        echo "404 - Ruta no encontrada: [$method] $path";
        return;
    }

    [$controllerClass, $action] = $this->routes[$method][$path];
    
    // Verificamos si la clase existe antes de instanciar
    if (!class_exists($controllerClass)) {
        die("Error: La clase controlador '$controllerClass' no existe. Revisa el autoloader y las mayúsculas.");
    }

    $controller = new $controllerClass($config);
    $controller->$action();
}

}
