# #!/usr/bin/env bash
# echo "Running composer"
# composer install --no-dev --working-dir=/var/www/html

# echo "Caching config..."
# php artisan config:cache

# echo "Caching routes..."
# php artisan route:cache

# echo "Running migrations..."
# php artisan migrate --force

# php artisan config:clear
# php artisan config:cache

# chmod -R 775 storage bootstrap/cache
# chown -R www-data:www-data storage bootstrap/cache

#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force
