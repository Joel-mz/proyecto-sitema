FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    sqlite3 \
    libsqlite3-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Configure Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Create database file and set permissions
RUN mkdir -p database storage/logs storage/framework/views storage/framework/sessions storage/framework/cache/data bootstrap/cache && \
    touch database/database.sqlite && \
    chmod -R 777 database storage bootstrap/cache && \
    chmod 666 database/database.sqlite

# Setup entrypoint script
RUN chmod +x /var/www/html/docker-entrypoint.sh

# Expose default port
EXPOSE 80

CMD ["/var/www/html/docker-entrypoint.sh"]
