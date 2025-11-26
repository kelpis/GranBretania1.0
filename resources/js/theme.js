// Tema oscuro: respeta la preferencia del usuario y la del sistema.
// Mueve la lógica desde un script inline en la vista a un módulo JS gestionado por Vite.
(function () {
    try {
        const userTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (userTheme === 'dark' || (!userTheme && systemPrefersDark)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    } catch (e) {
        // En caso de error (p. ej. bloqueo de acceso a localStorage), no romper la app.
        // Dejamos el comportamiento por defecto del navegador.
        // eslint-disable-next-line no-console
        console.warn('theme.js: no se pudo aplicar la preferencia de tema', e);
    }
})();
