#!/usr/bin/env bash
set -e

echo "Running composer install..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo "Caching config..."
set +e
php artisan config:cache
if [ $? -ne 0 ]; then
    echo "Warning: Config cache failed, clearing cache and continuing..."
    php artisan config:clear
fi
set -e

echo "Caching routes..."
set +e
php artisan route:cache
if [ $? -ne 0 ]; then
    echo "Warning: Route cache failed, clearing cache and continuing..."
    php artisan route:clear
fi
set -e

echo "Caching views..."
set +e
php artisan view:cache
if [ $? -ne 0 ]; then
    echo "Warning: View cache failed, clearing cache and continuing..."
    php artisan view:clear
fi
set -e

echo "Running migrations..."
php artisan migrate --force

echo "Deployment script completed successfully!"

