#!/bin/sh
set -e

if [ -d /shared-public ]; then
  echo "[entrypoint] Syncing public assets to shared volume..."
  cp -r /var/www/html/public/. /shared-public/
fi

echo "[entrypoint] Ensuring storage directories..."
mkdir -p /var/www/html/storage/logs \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/app/public

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

php artisan storage:link --force 2>/dev/null || true

echo "[entrypoint] Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[entrypoint] Running migrations..."
php artisan migrate --force

echo "[entrypoint] Starting $@"
exec "$@"
