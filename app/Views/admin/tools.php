<div class="card">
    <h2>Herramientas de Administración</h2>
    <p>Desde aquí podés generar y descargar un backup completo de la base de datos actual.</p>
    <form method="post" action="<?= htmlspecialchars($basePath) ?>/admin/backup/download">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken) ?>">
        <button class="btn-icon" type="submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M12 3v12"></path><path d="M7 10l5 5 5-5"></path><path d="M4 20h16"></path>
            </svg>
            <span class="btn-label">Descargar backup (.sql.gz)</span>
        </button>
    </form>
</div>

<div class="card">
    <h2>Healthcheck del Sistema</h2>
    <p><a href="<?= htmlspecialchars($basePath) ?>/admin/tools">Actualizar healthcheck</a></p>
    <?php $healthStatus = (string) ($health['status'] ?? 'error'); ?>
    <p>
        Estado:
        <strong class="<?= $healthStatus === 'ok' ? 'status-ok' : 'status-error' ?>">
            <?= strtoupper(htmlspecialchars($healthStatus)) ?>
        </strong>
        | Duración: <?= (int) ($health['duration_ms'] ?? 0) ?> ms
    </p>
    <p>Última ejecución: <?= htmlspecialchars((string) ($health['timestamp'] ?? '-')) ?></p>
    <table>
        <thead><tr><th>Check</th><th>Estado</th><th>Detalle</th></tr></thead>
        <tbody>
        <?php foreach (($health['checks'] ?? []) as $checkName => $check): ?>
            <?php $ok = !empty($check['ok']); ?>
            <tr>
                <td><?= htmlspecialchars((string) $checkName) ?></td>
                <td class="<?= $ok ? 'status-ok' : 'status-error' ?>"><?= $ok ? 'OK' : 'ERROR' ?></td>
                <td><code><?= htmlspecialchars((string) json_encode($check, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?></code></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p>
        Ver endpoint:
        <a href="<?= htmlspecialchars($basePath) ?>/healthcheck.php" target="_blank" rel="noopener noreferrer">JSON</a>
        |
        <a href="<?= htmlspecialchars($basePath) ?>/healthcheck.php?format=html" target="_blank" rel="noopener noreferrer">HTML</a>
    </p>
</div>
