import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { existsSync, readFileSync } from 'node:fs';

const installedPackagesPath = 'vendor/composer/installed.json';
const installedPackages = new Set();

if (existsSync(installedPackagesPath)) {
    const installed = JSON.parse(readFileSync(installedPackagesPath, 'utf8'));
    const packages = Array.isArray(installed) ? installed : installed.packages ?? [];

    packages.forEach(({ name }) => installedPackages.add(name));
}

const optionalInputs = {
    'figure-admin/booking': [
        'modules/Booking/resources/views/admin/assets/js/calendar-booking.js',
        'modules/Booking/resources/scss/calendar.scss',
    ],
    'figure-admin/communications': [
        'modules/Communications/resources/js/email-template-preview.js',
        'modules/Communications/resources/scss/email-template-preview.scss',
    ],
};

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'modules/Shared/resources/js/app.js',
                'modules/Shared/resources/scss/admin.scss',
                'modules/Dashboard/resources/js/dashboard.js',
                'modules/Dashboard/resources/scss/dashboard.scss',
                ...Object.entries(optionalInputs)
                    .filter(([packageName]) => installedPackages.has(packageName))
                    .flatMap(([, inputs]) => inputs),
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
