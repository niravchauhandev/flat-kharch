#!/usr/bin/env bash
set -e

# Fix storage permissions on startup
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run database migrations automatically in production if DB is configured
if [ -n "$DB_HOST" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || echo "Migration skipped or database not reachable yet"
fi

# Optimize Laravel cache
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec "$@"
