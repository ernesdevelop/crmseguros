<div class="card">
    <h2>Alta de usuario</h2>
    <form method="post" action="<?= htmlspecialchars($basePath) ?>/users/store">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken) ?>">
        <input name="name" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Contraseña (mínimo 8 caracteres)" required minlength="8">
        <input type="password" name="password_confirm" placeholder="Confirmar contraseña" required minlength="8">
        <select name="role">
            <option value="admin">Admin</option>
            <option value="operador">Operador</option>
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
    <h2>Usuarios</h2>
    <table>
        <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th></tr></thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
