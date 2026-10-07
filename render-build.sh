#!/usr/bin/env bash
# Exit on error
set -o errexit

echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Installing and compiling frontend assets..."
npm install
npm run build

echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ -n "$DB_HOST" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi
