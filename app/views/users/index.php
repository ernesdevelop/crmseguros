<div class="card">
    <h2>Alta de usuario</h2>
    <form method="post" action="/users/store">
        <input name="name" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="operador">Operador</option>
        </select>
        <button type="submit">Guardar</button>
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
