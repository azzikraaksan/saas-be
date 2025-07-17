#!/bin/sh

# Copy .env kalau belum ada
[ ! -f .env ] && cp .env.example .env

# Generate key dan link storage
php artisan key:generate --force
php artisan storage:link

# Jalankan migrate biar tabel kebentuk
php artisan migrate --force

# Cache config (biar cepat)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Atur permission
chmod -R 775 storage bootstrap/cache
