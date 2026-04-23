<div class="card">
    <h2>Alta de compañía de seguros</h2>
    <form method="post" action="<?= htmlspecialchars($basePath) ?>/insurers/store">
        <input name="name" placeholder="Nombre" required>
        <input type="email" name="contact_email" placeholder="Email de contacto">
        <input name="contact_phone" placeholder="Teléfono de contacto">
        <button type="submit">Guardar</button>
    </form>
</div>

<div class="card">
    <h2>Compañías</h2>
    <table>
        <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th></tr></thead>
        <tbody>
        <?php foreach ($insurers as $i): ?>
            <tr>
                <td><?= $i['id'] ?></td>
                <td><?= htmlspecialchars($i['name']) ?></td>
                <td><?= htmlspecialchars($i['contact_email']) ?></td>
                <td><?= htmlspecialchars($i['contact_phone']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
