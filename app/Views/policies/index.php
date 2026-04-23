<div class="card">
    <h2>Alta de póliza</h2>
    <form method="post" action="<?= htmlspecialchars($basePath) ?>/policies/store">
        <input name="policy_number" placeholder="Número de póliza" required>
        <select name="client_id" required>
            <option value="">Cliente</option>
            <?php foreach ($clients as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['full_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="insurer_id" required>
            <option value="">Compañía</option>
            <?php foreach ($insurers as $i): ?>
                <option value="<?= $i['id'] ?>"><?= htmlspecialchars($i['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input name="coverage_type" placeholder="Tipo de cobertura" required>
        <input type="date" name="start_date" required>
        <input type="date" name="end_date" required>
        <input type="number" step="0.01" name="premium" placeholder="Prima" required>
        <select name="status">
            <option value="vigente">Vigente</option>
            <option value="vencida">Vencida</option>
            <option value="cancelada">Cancelada</option>
        </select>
        <button type="submit">Guardar</button>
    </form>
</div>

<div class="card">
    <h2>Pólizas</h2>
    <table>
        <thead><tr><th>N°</th><th>Cliente</th><th>Compañía</th><th>Cobertura</th><th>Vigencia</th><th>Estado</th></tr></thead>
        <tbody>
        <?php foreach ($policies as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['policy_number']) ?></td>
                <td><?= htmlspecialchars($p['client_name']) ?></td>
                <td><?= htmlspecialchars($p['insurer_name']) ?></td>
                <td><?= htmlspecialchars($p['coverage_type']) ?></td>
                <td><?= htmlspecialchars($p['start_date']) ?> a <?= htmlspecialchars($p['end_date']) ?></td>
                <td><?= htmlspecialchars($p['status']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
