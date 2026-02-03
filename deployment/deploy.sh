#!/bin/bash

# EduPass-MG Deployment Script
# Usage: ./deploy.sh [staging|production]

set -e

ENV=${1:-staging}
COMPOSE_FILE="docker-compose.prod.yml"

echo "🚀 Deploying EduPass-MG to $ENV..."

# Load environment variables
if [ -f ".env.$ENV" ]; then
    export $(cat .env.$ENV | grep -v '^#' | xargs)
else
    echo "❌ Error: .env.$ENV file not found"
    exit 1
fi

# Pull latest images
echo "📦 Pulling latest Docker images..."
docker-compose -f $COMPOSE_FILE pull

# Stop existing containers
echo "🛑 Stopping existing containers..."
docker-compose -f $COMPOSE_FILE down

# Start new containers
echo "▶️  Starting new containers..."
docker-compose -f $COMPOSE_FILE up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 15

# Run database migrations
echo "🗄️  Running database migrations..."
docker-compose -f $COMPOSE_FILE exec -T app php artisan migrate --force

# Clear and optimize caches
echo "🧹 Clearing and optimizing caches..."
docker-compose -f $COMPOSE_FILE exec -T app php artisan config:cache
docker-compose -f $COMPOSE_FILE exec -T app php artisan route:cache
docker-compose -f $COMPOSE_FILE exec -T app php artisan view:cache

# Restart queue workers
echo "🔄 Restarting queue workers..."
docker-compose -f $COMPOSE_FILE exec -T app php artisan queue:restart

# Clean up old Docker images
echo "🧼 Cleaning up old Docker images..."
docker image prune -af

# Health check
echo "🏥 Running health check..."
if curl -f http://localhost:${APP_PORT:-8080}/health > /dev/null 2>&1; then
    echo "✅ Deployment successful! Application is healthy."
else
    echo "⚠️  Warning: Health check failed. Please verify manually."
fi

# Show running containers
echo ""
echo "📊 Running containers:"
docker-compose -f $COMPOSE_FILE ps

echo ""
echo "✨ Deployment to $ENV completed!"
echo "🌐 Application URL: $APP_URL"
