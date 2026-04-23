<div class="card">
    <h2><?= $editClient ? 'Modificar cliente' : 'Alta de cliente' ?></h2>
    <form method="post" action="<?= htmlspecialchars($basePath) ?><?= $editClient ? '/clients/update' : '/clients/store' ?>">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken) ?>">
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
        <button class="btn-icon" type="submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M4 4h16v16H4z"></path><path d="M8 4v6h8V4"></path><path d="M8 16h8"></path>
            </svg>
            <span class="btn-label">Guardar</span>
        </button>
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
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                        <button class="danger btn-icon" type="submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M6 6l1 14h10l1-14"></path><path d="M10 10v7"></path><path d="M14 10v7"></path>
                            </svg>
                            <span class="btn-label">Baja</span>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
