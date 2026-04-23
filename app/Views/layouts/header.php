<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName) ?></title>
    <script>
        (function () {
            try {
                var savedTheme = localStorage.getItem('theme');
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = savedTheme || (prefersDark ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/style.css">
</head>
<body>
<header>
    <div class="topbar">
        <h1 class="brand"><?= htmlspecialchars($appName) ?></h1>
        <nav>
            <?php if (!empty($authUser)): ?>
                <a href="<?= htmlspecialchars($basePath) ?>/">Dashboard</a>
                <?php if (($authUser['role'] ?? '') === 'admin'): ?>
                    <a href="<?= htmlspecialchars($basePath) ?>/users">Usuarios</a>
                <?php endif; ?>
                <a href="<?= htmlspecialchars($basePath) ?>/clients">Clientes</a>
                <a href="<?= htmlspecialchars($basePath) ?>/insurers">Compañías</a>
                <a href="<?= htmlspecialchars($basePath) ?>/policies">Pólizas</a>
                <a href="<?= htmlspecialchars($basePath) ?>/renewals">Vencimientos</a>
                <?php if (($authUser['role'] ?? '') === 'admin'): ?>
                    <a href="<?= htmlspecialchars($basePath) ?>/admin/tools">Admin</a>
                <?php endif; ?>
                <span class="nav-user"><?= htmlspecialchars((string) $authUser['name']) ?> (<?= htmlspecialchars((string) $authUser['role']) ?>)</span>
                <form class="nav-logout" method="post" action="<?= htmlspecialchars($basePath) ?>/logout">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken) ?>">
                    <button class="btn-icon" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M10 6l-6 6 6 6"></path><path d="M4 12h12"></path><path d="M20 4v16"></path>
                        </svg>
                        <span class="btn-label">Salir</span>
                    </button>
                </form>
            <?php else: ?>
                <a href="<?= htmlspecialchars($basePath) ?>/login">Login</a>
            <?php endif; ?>
            <button id="themeToggle" type="button" class="theme-toggle btn-icon" aria-pressed="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"></path>
                </svg>
                <span class="btn-label" id="themeToggleLabel">Tema</span>
            </button>
        </nav>
    </div>
</header>
<main>
<?php if (!empty($flash) && is_array($flash)): ?>
    <div class="flash flash-<?= htmlspecialchars((string) ($flash['type'] ?? 'info')) ?>">
        <?= htmlspecialchars((string) ($flash['message'] ?? '')) ?>
    </div>
<?php endif; ?>
