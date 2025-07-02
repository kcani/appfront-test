import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/guest.scss',
                'resources/scss/admin.scss',
                'resources/scss/login.scss',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        outDir: 'public/build/assets',
        manifest: true,
        emptyOutDir: true,
        rollupOptions: {
            output: {
                entryFileNames: 'js/[name].js',
                chunkFileNames: 'js/[name].js',
                assetFileNames: 'css/[name][extname]',
            },
        },
    },
});
