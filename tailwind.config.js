import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // Modo oscuro controlado por clase: añade/quita 'dark' en <html>
    darkMode: 'class',
    // Rutas de archivos que Tailwind escanea para extraer clases usadas
    content: [
        // Vistas de paginación de Laravel (vendor)
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        // Vistas compiladas por Laravel en runtime
        './storage/framework/views/*.php',
        // Todas las plantillas Blade del proyecto
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // Paleta de colores personalizada usada en la app
            colors: {
                azul: '#011A6B',
                rojo: '#D51C3B',
                beige: '#F3E8D0',
                beige2: '#F9F9F9',
                negro: '#0E0D0D',
                ok: '#2E7D32',
                info: '#FBC02D',
            },
            // Familias tipográficas: prioriza Raleway/Inconsolata si están cargadas
            fontFamily: {
                sans: ['Raleway', ...defaultTheme.fontFamily.sans],
                mono: ['Inconsolata', ...defaultTheme.fontFamily.mono],
            },
            // Radio de borde adicional para tarjetas
            borderRadius: {
                card: '12px',
            },
        },
    },

    // Plugin oficial para mejorar estilos de formularios
    plugins: [forms],
};
