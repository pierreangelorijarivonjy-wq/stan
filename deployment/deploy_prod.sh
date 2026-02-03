#!/bin/bash

# 🚀 EduPass-MG Production Deployment Script
# Run this ON THE SERVER inside /var/www/edupass

set -e

echo "--- 🚀 Starting Production Deployment ---"

# 1. Pull latest changes
echo "--- 📥 Pulling latest changes ---"
git pull origin main

# 2. Install PHP dependencies
echo "--- 📦 Installing PHP dependencies ---"
composer install --no-dev --optimize-autoloader

# 3. Install JS dependencies & Build assets
echo "--- 🎨 Building assets ---"
npm install
npm run build

# 4. Run database migrations
echo "--- 🗄️ Running migrations ---"
php artisan migrate --force

# 5. Clear and cache config/routes/views
echo "--- ⚡ Optimizing application ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Restart Queue Worker
echo "--- 🔄 Restarting Queue Worker ---"
php artisan queue:restart

# 7. Set permissions
echo "--- 🔑 Setting permissions ---"
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data .

echo "--- ✅ Deployment Finished Successfully! ---"
