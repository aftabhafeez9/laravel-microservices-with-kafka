# Use official PHP image
FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www/admin

# Install system dependencies for Laravel + PostgreSQL + Kafka
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    librdkafka-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip

# Install rdkafka extension
RUN pecl install rdkafka \
    && docker-php-ext-enable rdkafka

# Install Composer (latest version)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install Composer dependencies first
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Ensure .env exists and generate APP_KEY if missing
RUN if [ ! -f .env ]; then cp .env.example .env; fi
RUN php artisan key:generate --ansi

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set correct permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Expose port for Laravel server
EXPOSE 9000

# Run Laravel development server (for local/testing)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
