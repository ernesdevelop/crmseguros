</main>
<script>
    (function () {
        var root = document.documentElement;
        var button = document.getElementById('themeToggle');
        var label = document.getElementById('themeToggleLabel');
        if (!button) {
            return;
        }

        function applyTheme(theme) {
            root.setAttribute('data-theme', theme);
            button.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
            if (label) {
                label.textContent = theme === 'dark' ? 'Tema: Oscuro' : 'Tema: Claro';
            }
        }

        var currentTheme = root.getAttribute('data-theme') || 'light';
        applyTheme(currentTheme);

        button.addEventListener('click', function () {
            var nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            applyTheme(nextTheme);
            try {
                localStorage.setItem('theme', nextTheme);
            } catch (e) {
                // Ignore storage errors in restricted environments.
            }
        });
    })();
</script>
</body>
</html>
