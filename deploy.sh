#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting Deployment..."

# 1. Pull latest changes
echo "📥 Pulling latest changes from Git..."
git pull origin main

# 2. Rebuild and restart containers
echo "docker-compose up -d --build..."
docker-compose up -d --build

# 3. Install dependencies
echo "📦 Installing PHP dependencies..."
docker-compose exec -T app composer install --no-dev --optimize-autoloader

# 4. Run database migrations
echo "🗄️ Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# 5. Clear and optimize cache
echo "✨ Clearing and optimizing cache..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

# 6. Set permissions
echo "🔑 Setting permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment finished successfully!"
