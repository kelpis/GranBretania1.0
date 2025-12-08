import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                'resources/js/bookings.js',
                'resources/js/translation.js',
                'resources/js/user-bookings.js',
                'resources/js/user-bookings-modal.js',
                'resources/js/admin-booking.js'],
            refresh: true,
        }),
    ],
});
