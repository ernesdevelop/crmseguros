<div class="grid">
    <div class="card"><div>Clientes</div><div class="stat"><?= $clientsCount ?></div></div>
    <div class="card"><div>Pólizas</div><div class="stat"><?= $policiesCount ?></div></div>
    <div class="card"><div>Compañías</div><div class="stat"><?= $insurersCount ?></div></div>
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
