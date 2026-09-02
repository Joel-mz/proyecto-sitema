#!/bin/bash
set -e

# Make sure storage and database directories exist
mkdir -p /var/www/html/database
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/bootstrap/cache

# Create SQLite database file if not exists
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Link storage
php artisan storage:link --force || true

# Run migrations and seed admin user
php artisan migrate --force || true
php artisan db:seed --class=AdminUserSeeder --force || true

# Clear cached configs to avoid issues with environment variables
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Assign ownership to www-data so SQLite and logs can be written
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

exec apache2-foreground
