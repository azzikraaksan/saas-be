# Gunakan base image PHP 8.2 dengan ekstensi penting
FROM php:8.2-fpm

# Install dependencies system
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Atur working directory
WORKDIR /var/www

# Copy semua file project ke dalam container
COPY . .

# Install dependency Laravel
RUN composer install

# Set permission folder storage dan bootstrap/cache
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Port expose (biar Railway tahu portnya)
EXPOSE 8000
