import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    // ADD THIS BLOCK RIGHT HERE:
    resolve: {
        alias: {
            // This tells Vite to use the version of Vue that can read Blade files!
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});