#!/bin/bash
set -e

# Make sure storage and database directories exist
mkdir -p /var/www/html/database
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/bootstrap/cache

# Create SQLite database file if not exists
touch /var/www/html/database/database.sqlite
chmod -R 777 /var/www/html/database
chmod 666 /var/www/html/database/database.sqlite

# Link storage
php artisan storage:link --force || true

# Clear cached configs
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations and seeders
php artisan migrate --force || true
php artisan db:seed --force || true

# Set final permissions for web server (www-data)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/database
chmod 666 /var/www/html/database/database.sqlite

exec apache2-foreground
