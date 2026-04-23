<?php
namespace App\Core;

class Controller
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $appName = $this->config['app_name'] ?? 'CRM';
        $basePath = '/crmseguros/public'; 

        require __DIR__ . '/../Views/' . $view . '.php';
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . rtrim($this->config['base_url'], '/') . $path);
        exit;
    }
}
