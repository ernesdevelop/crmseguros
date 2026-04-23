<div class="card">
    <h2>Acciones Rápidas</h2>
    <div class="action-buttons">
        <a class="button-link primary" href="<?= htmlspecialchars($basePath) ?>/clients">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M12 5v14"></path><path d="M5 12h14"></path>
            </svg>
            <span class="btn-label">Nuevo cliente</span>
        </a>
        <a class="button-link primary" href="<?= htmlspecialchars($basePath) ?>/policies">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M4 4h16v16H4z"></path><path d="M8 8h8"></path><path d="M8 12h8"></path><path d="M8 16h5"></path>
            </svg>
            <span class="btn-label">Nueva póliza</span>
        </a>
        <a class="button-link secondary" href="<?= htmlspecialchars($basePath) ?>/renewals">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M12 8v5l3 2"></path><circle cx="12" cy="12" r="8"></circle>
            </svg>
            <span class="btn-label">Ver vencimientos</span>
        </a>
        <?php if (!empty($authUser) && ($authUser['role'] ?? '') === 'admin'): ?>
            <a class="button-link secondary" href="<?= htmlspecialchars($basePath) ?>/admin/tools">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.6 1.6 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.6 1.6 0 0 0-1.8-.3 1.6 1.6 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.2a1.6 1.6 0 0 0-1-1.5 1.6 1.6 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.6 1.6 0 0 0 .3-1.8 1.6 1.6 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.2a1.6 1.6 0 0 0 1.5-1 1.6 1.6 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.6 1.6 0 0 0 1.8.3h.1a1.6 1.6 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.2a1.6 1.6 0 0 0 1 1.5h.1a1.6 1.6 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.6 1.6 0 0 0-.3 1.8v.1a1.6 1.6 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.2a1.6 1.6 0 0 0-1.5 1z"></path>
                </svg>
                <span class="btn-label">Panel admin</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="grid">
    <div class="card stat-card stat-card-clients">
        <div class="stat-label">Clientes</div>
        <div class="stat"><?= $clientsCount ?></div>
    </div>
    <div class="card stat-card stat-card-policies">
        <div class="stat-label">Pólizas</div>
        <div class="stat"><?= $policiesCount ?></div>
    </div>
    <div class="card stat-card stat-card-insurers">
        <div class="stat-label">Compañías</div>
        <div class="stat"><?= $insurersCount ?></div>
    </div>
</div>

<div class="card">
    <h2>Próximos vencimientos (45 días)</h2>
    <table>
        <thead><tr><th>Póliza</th><th>Cliente</th><th>Compañía</th><th>Vence</th></tr></thead>
        <tbody>
        <?php foreach ($renewals as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['policy_number']) ?></td>
                <td><?= htmlspecialchars($row['client_name']) ?></td>
                <td><?= htmlspecialchars($row['insurer_name']) ?></td>
                <td><?= htmlspecialchars($row['end_date']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
