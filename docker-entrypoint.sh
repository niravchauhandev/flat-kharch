#!/usr/bin/env bash
set -e

# Fix storage permissions on startup
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run database migrations and seed permissions automatically in production if DB is configured
if [ -n "$DB_HOST" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || echo "Migration skipped or database not reachable yet"
    echo "Seeding roles and permissions..."
    php artisan db:seed --class=PermissionSeeder --force || echo "Permission seeding skipped"
fi

# Create storage symlink if not present
php artisan storage:link || true

# Optimize Laravel cache
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec "$@"
