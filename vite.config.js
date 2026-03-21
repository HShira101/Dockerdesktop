import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        // Host del servicio en Docker para actualización de estilos
        // tailwing en tiempo real.
        host: '0.0.0.0',//apunta al localhost.
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
            interval: 100, // Revisa cambios cada 100 milisegundos (por defecto Vite tarda 1 segundo)
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
