<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 | <?= htmlspecialchars($appName) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>/style.css">
</head>
<body>
<main class="error-page-wrap">
    <section class="error-page-card">
        <p class="error-code">500</p>
        <h1><?= htmlspecialchars($title ?? 'Error interno') ?></h1>
        <p><?= htmlspecialchars($message ?? 'Ocurrió un error inesperado en la aplicación.') ?></p>
        <?php if (!empty($details)): ?>
            <p class="error-detail"><?= htmlspecialchars((string) $details) ?></p>
        <?php endif; ?>
        <a class="error-link" href="<?= htmlspecialchars($basePath) ?>/">Ir al dashboard</a>
    </section>
</main>
</body>
</html>
