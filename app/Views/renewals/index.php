<div class="card">
    <h2>Vencimientos próximos</h2>
    <form method="get" action="<?= htmlspecialchars($basePath) ?>/renewals">
        <label>Mostrar vencimientos en los próximos días:</label>
        <input type="number" min="1" max="365" name="days" value="<?= $days ?>">
        <button type="submit">Filtrar</button>
    </form>
</div>

<div class="card">
    <table>
        <thead><tr><th>Póliza</th><th>Cliente</th><th>Compañía</th><th>Fecha de vencimiento</th></tr></thead>
        <tbody>
        <?php foreach ($renewals as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['policy_number']) ?></td>
                <td><?= htmlspecialchars($r['client_name']) ?></td>
                <td><?= htmlspecialchars($r['insurer_name']) ?></td>
                <td><?= htmlspecialchars($r['end_date']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
