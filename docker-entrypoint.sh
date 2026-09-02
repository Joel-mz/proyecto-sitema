#!/bin/bash
set -e

# Make sure storage and database directories exist and have full permissions
mkdir -p /var/www/html/database
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/bootstrap/cache

touch /var/www/html/database/database.sqlite
chmod -R 777 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache
chmod 666 /var/www/html/database/database.sqlite

# Default env vars if not set in Render dashboard
export APP_KEY="${APP_KEY:-base64:BBPGlkCnlMThDkU23YeCMHvP7ZAfe5bUc2ynzkfuDqs=}"
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"
export DB_DATABASE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
export SESSION_DRIVER="${SESSION_DRIVER:-file}"
export CACHE_STORE="${CACHE_STORE:-file}"

# Clear cached configs
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Storage link
php artisan storage:link --force || true

# Run migrations and seed database
php artisan migrate --force || true
php artisan db:seed --force || true

# Final permission ensure
chmod -R 777 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache

exec apache2-foreground
