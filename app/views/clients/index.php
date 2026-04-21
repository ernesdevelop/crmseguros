<div class="card">
    <h2><?= $editClient ? 'Modificar cliente' : 'Alta de cliente' ?></h2>
    <form method="post" action="<?= htmlspecialchars($basePath) ?><?= $editClient ? '/clients/update' : '/clients/store' ?>">
        <?php if ($editClient): ?><input type="hidden" name="id" value="<?= $editClient['id'] ?>"><?php endif; ?>
        <input name="full_name" placeholder="Nombre completo" required value="<?= htmlspecialchars($editClient['full_name'] ?? '') ?>">
        <input name="document" placeholder="Documento" required value="<?= htmlspecialchars($editClient['document'] ?? '') ?>">
        <input name="phone" placeholder="Teléfono" value="<?= htmlspecialchars($editClient['phone'] ?? '') ?>">
        <input name="email" type="email" placeholder="Email" value="<?= htmlspecialchars($editClient['email'] ?? '') ?>">
        <input name="address" placeholder="Dirección" value="<?= htmlspecialchars($editClient['address'] ?? '') ?>">
        <select name="status">
            <option value="activo" <?= (($editClient['status'] ?? '') === 'activo') ? 'selected' : '' ?>>Activo</option>
            <option value="inactivo" <?= (($editClient['status'] ?? '') === 'inactivo') ? 'selected' : '' ?>>Inactivo</option>
        </select>
        <button type="submit">Guardar</button>
    </form>
</div>

<div class="card">
    <h2>Clientes</h2>
    <table>
        <thead><tr><th>ID</th><th>Nombre</th><th>Documento</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($clients as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['full_name']) ?></td>
                <td><?= htmlspecialchars($c['document']) ?></td>
                <td><?= htmlspecialchars($c['status']) ?></td>
                <td class="actions">
                    <a href="<?= htmlspecialchars($basePath) ?>/clients?edit=<?= $c['id'] ?>">Editar</a>
                    <form class="inline" method="post" action="<?= htmlspecialchars($basePath) ?>/clients/delete" onsubmit="return confirm('¿Eliminar cliente?');">
                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                        <button class="danger" type="submit">Baja</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
