import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'modules/Shared/resources/js/app.js',
                'modules/Shared/resources/scss/admin.scss',
                'modules/Booking/resources/views/admin/assets/js/calendar-booking.js',
                'modules/Booking/resources/scss/calendar.scss',
                'modules/Communications/resources/js/email-template-preview.js',
                'modules/Communications/resources/scss/email-template-preview.scss',
                'modules/Dashboard/resources/js/dashboard.js',
                'modules/Dashboard/resources/scss/dashboard.scss',
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                silenceDeprecations: [
                    'import',
                    'global-builtin',
                    'color-functions',
                    'mixed-decls',
                    'legacy-js-api'
                ],
                quietDeps: true,
            },
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
