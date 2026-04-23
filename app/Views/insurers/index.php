<div class="card">
    <h2>Alta de compañía de seguros</h2>
    <form method="post" action="<?= htmlspecialchars($basePath) ?>/insurers/store">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken) ?>">
        <input name="name" placeholder="Nombre" required>
        <input type="email" name="contact_email" placeholder="Email de contacto">
        <input name="contact_phone" placeholder="Teléfono de contacto">
        <button class="btn-icon" type="submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M4 4h16v16H4z"></path><path d="M8 4v6h8V4"></path><path d="M8 16h8"></path>
            </svg>
            <span class="btn-label">Guardar</span>
        </button>
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
