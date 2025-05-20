#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

php artisan storage:link

echo "Caching config..."
php artisan config:cache

# echo "Caching routes..."
# php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

php artisan config:clear
php artisan config:cache

php artisan route:clear
php artisan cache:clear

chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
