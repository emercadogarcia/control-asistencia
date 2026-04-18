#!/bin/sh
set -eu

cd /var/www/html

php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
