<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | <?= htmlspecialchars($appName) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/style.css">
</head>
<body>
<main class="error-page-wrap">
    <section class="error-page-card">
        <p class="error-code">404</p>
        <h1><?= htmlspecialchars($title ?? 'Ruta no encontrada') ?></h1>
        <p><?= htmlspecialchars($message ?? 'La página solicitada no existe o fue movida.') ?></p>
        <p class="error-detail">Solicitud: <?= htmlspecialchars(($method ?? 'GET') . ' ' . ($path ?? '/')) ?></p>
        <a class="error-link" href="<?= htmlspecialchars($basePath) ?>/">Volver al dashboard</a>
    </section>
</main>
</body>
</html>
