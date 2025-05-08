#!/bin/bash

# Stop on error
set -e


echo "⚙️ Running migrations..."
php artisan migrate --force

echo "🌱 Running seeders..."
php artisan db:seed --force

echo "🚀 Starting Laravel development server..."
exec php artisan serve --host=0.0.0.0 --port=8000
