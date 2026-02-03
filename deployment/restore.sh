#!/bin/bash

# EduPass-MG Restore Script
# Usage: ./restore.sh <backup_file.sql.gz>

set -e

if [ -z "$1" ]; then
    echo "❌ Error: Please specify a backup file"
    echo "Usage: ./restore.sh <backup_file.sql.gz>"
    exit 1
fi

BACKUP_FILE=$1
COMPOSE_FILE="docker-compose.prod.yml"

if [ ! -f "$BACKUP_FILE" ]; then
    echo "❌ Error: Backup file not found: $BACKUP_FILE"
    exit 1
fi

echo "⚠️  WARNING: This will REPLACE the current database!"
echo "Backup file: $BACKUP_FILE"
echo ""
read -p "Are you sure you want to continue? (yes/no): " CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo "❌ Restore cancelled"
    exit 0
fi

echo ""
echo "🔄 Starting database restore..."

# Stop application to prevent writes
echo "🛑 Stopping application..."
docker-compose -f $COMPOSE_FILE stop app

# Drop and recreate database
echo "🗄️  Recreating database..."
docker-compose -f $COMPOSE_FILE exec -T postgres psql -U ${DB_USERNAME} -c "DROP DATABASE IF EXISTS ${DB_DATABASE};"
docker-compose -f $COMPOSE_FILE exec -T postgres psql -U ${DB_USERNAME} -c "CREATE DATABASE ${DB_DATABASE};"

# Restore from backup
echo "📥 Restoring from backup..."
gunzip -c $BACKUP_FILE | docker-compose -f $COMPOSE_FILE exec -T postgres psql -U ${DB_USERNAME} ${DB_DATABASE}

# Restart application
echo "▶️  Restarting application..."
docker-compose -f $COMPOSE_FILE start app

# Run migrations (in case of schema changes)
echo "🔄 Running migrations..."
docker-compose -f $COMPOSE_FILE exec app php artisan migrate --force

# Clear caches
echo "🧹 Clearing caches..."
docker-compose -f $COMPOSE_FILE exec app php artisan cache:clear
docker-compose -f $COMPOSE_FILE exec app php artisan config:cache

echo ""
echo "✅ Database restore completed successfully!"
echo ""
echo "🏥 Running health check..."
curl -f http://localhost:${APP_PORT:-8080}/health || echo "⚠️  Health check failed"
