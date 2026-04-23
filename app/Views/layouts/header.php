<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/style.css">
</head>
<body>
<header>
    <div class="topbar">
        <h1 class="brand"><?= htmlspecialchars($appName) ?></h1>
        <nav>
            <a href="<?= htmlspecialchars($basePath) ?>/">Dashboard</a>
            <a href="<?= htmlspecialchars($basePath) ?>/users">Usuarios</a>
            <a href="<?= htmlspecialchars($basePath) ?>/clients">Clientes</a>
            <a href="<?= htmlspecialchars($basePath) ?>/insurers">Compañías</a>
            <a href="<?= htmlspecialchars($basePath) ?>/policies">Pólizas</a>
            <a href="<?= htmlspecialchars($basePath) ?>/renewals">Vencimientos</a>
        </nav>
    </div>
</header>
<main>
<?php if (!empty($flash) && is_array($flash)): ?>
    <div class="flash flash-<?= htmlspecialchars((string) ($flash['type'] ?? 'info')) ?>">
        <?= htmlspecialchars((string) ($flash['message'] ?? '')) ?>
    </div>
<?php endif; ?>
