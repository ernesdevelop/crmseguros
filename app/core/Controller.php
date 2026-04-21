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
        $basePath = rtrim((string) parse_url($this->config['base_url'] ?? '', PHP_URL_PATH), '/');
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . rtrim($this->config['base_url'], '/') . $path);
        exit;
    }
}
