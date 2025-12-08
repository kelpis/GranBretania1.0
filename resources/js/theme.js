// Tema oscuro: respeta la preferencia del usuario y la del sistema.

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
        
        // Dejamos el comportamiento por defecto del navegador.
        
        console.warn('theme.js: no se pudo aplicar la preferencia de tema', e);
    }
})();
