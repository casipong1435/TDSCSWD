import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
    server: {
    host: '0.0.0.0', // Allow Vite to listen on all network interfaces
    port: 3000,      // Or any available port
    hmr: {
      host: '192.168.1.124', // Replace with your local machine's IP address
    },
  },
});
