#!/bin/sh

# Copy .env kalau belum ada
[ ! -f .env ] && cp .env.example .env

# Generate key dan link storage
php artisan key:generate --force
php artisan storage:link

# Jalankan migrate biar tabel kebentuk
php artisan migrate --force

# Cache config (biar cepat)
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

mkdir -p storage/app/public/uploads/images
mkdir -p storage/app/public/uploads/pdfs
mkdir -p storage/app/public/uploads/excels

# Atur permission
chmod -R 775 storage bootstrap/cache

# INI WAJIB BANGET
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
