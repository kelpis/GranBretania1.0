import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                azul: '#011A6B',
                rojo: '#D51C3B',
                beige: '#F3E8D0',
                beige2: '#F9F9F9',
                negro: '#0E0D0D',
                ok: '#2E7D32',
                info: '#FBC02D',
            },
            fontFamily: {
                sans: ['Raleway', ...defaultTheme.fontFamily.sans],
                mono: ['Inconsolata', ...defaultTheme.fontFamily.mono],
            },
            borderRadius: {
                card: '12px',
            }
        },
    },

    plugins: [forms],
};
