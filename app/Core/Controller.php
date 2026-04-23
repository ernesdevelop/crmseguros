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
        $flash = $this->consumeFlash();

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

    protected function redirectWithMessage(string $path, string $type, string $message): void
    {
        $this->flash($type, $message);
        $this->redirect($path);
    }

    protected function flash(string $type, string $message): void
    {
        if (!$this->ensureSessionStarted()) {
            return;
        }

        $_SESSION['flash'] = [
            'type' => in_array($type, ['success', 'error', 'info'], true) ? $type : 'info',
            'message' => $message,
        ];
    }

    protected function consumeFlash(): ?array
    {
        if (!$this->ensureSessionStarted() || !isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    private function ensureSessionStarted(): bool
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }

        if (headers_sent()) {
            return false;
        }

        if (PHP_SAPI === 'cli') {
            $tmpDir = sys_get_temp_dir();
            if (is_dir($tmpDir) && is_writable($tmpDir)) {
                @ini_set('session.save_path', $tmpDir);
            }
        }

        return @session_start();
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
