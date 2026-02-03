#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting deployment script..."

# Run migrations
echo "🔄 Running migrations..."
php artisan migrate --force

# Cache configuration, routes, and views
echo "📦 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Preparation complete. Starting Supervisor..."

# Start Supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
