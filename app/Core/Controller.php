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
        $basePath = $this->resolveBasePath();

        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/' . $view . '.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

    protected function redirect(string $path): void
    {
        $targetPath = '/' . ltrim($path, '/');
        header('Location: ' . rtrim($this->resolveBasePath(), '/') . $targetPath);
        exit;
    }

    protected function resolveBasePath(): string
    {
        if (!empty($this->config['basePath'])) {
            return '/' . trim((string) $this->config['basePath'], '/');
        }

        if (!empty($this->config['base_url'])) {
            $urlPath = (string) parse_url((string) $this->config['base_url'], PHP_URL_PATH);
            if ($urlPath !== '') {
                return '/' . trim($urlPath, '/');
            }
        }

        return '';
    }
}
