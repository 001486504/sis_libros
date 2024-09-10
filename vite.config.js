import { defineConfig } from 'vite';
import { resolve } from 'path'; // Importa resolve desde path
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            //input: ['resources/sass/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(), // Agrega el plugin de Vue aquí
    ],
    css: {
        preprocessorOptions: {
            scss: {
                includePaths: [resolve(__dirname, 'node_modules')],
            },
        },
    },
});
