<div class="card auth-card">
    <h2>Iniciar sesión</h2>
    <p>Ingresá con un usuario existente para acceder a funciones administrativas.</p>
    <form method="post" action="<?= htmlspecialchars($basePath) ?>/login">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button class="btn-icon" type="submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M10 6l6 6-6 6"></path><path d="M16 12H4"></path><path d="M20 4v16"></path>
            </svg>
            <span class="btn-label">Ingresar</span>
        </button>
    </form>
</div>
