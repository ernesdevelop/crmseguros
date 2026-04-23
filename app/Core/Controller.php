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
        $authUser = $this->currentUser();
        $csrfToken = $this->csrfToken();

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

    protected function currentUser(): ?array
    {
        if (!$this->ensureSessionStarted()) {
            return null;
        }

        $user = $_SESSION['auth_user'] ?? null;
        if (!is_array($user)) {
            return null;
        }

        if (!isset($user['id'], $user['name'], $user['email'], $user['role'])) {
            return null;
        }

        return [
            'id' => (int) $user['id'],
            'name' => (string) $user['name'],
            'email' => (string) $user['email'],
            'role' => (string) $user['role'],
        ];
    }

    protected function loginUser(array $user): void
    {
        if (!$this->ensureSessionStarted()) {
            return;
        }

        $_SESSION['auth_user'] = [
            'id' => (int) ($user['id'] ?? 0),
            'name' => (string) ($user['name'] ?? ''),
            'email' => (string) ($user['email'] ?? ''),
            'role' => (string) ($user['role'] ?? 'operador'),
        ];
    }

    protected function logoutUser(): void
    {
        if (!$this->ensureSessionStarted()) {
            return;
        }

        unset($_SESSION['auth_user']);
    }

    protected function requireAdmin(): void
    {
        $user = $this->currentUser();
        if ($user === null) {
            $this->redirectWithMessage('/login', 'error', 'Iniciá sesión para acceder al módulo admin.');
        }

        if (($user['role'] ?? '') !== 'admin') {
            $this->redirectWithMessage('/', 'error', 'No tenés permisos para acceder al módulo admin.');
        }
    }

    protected function csrfToken(): string
    {
        if (!$this->ensureSessionStarted()) {
            return '';
        }

        if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
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
