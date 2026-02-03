#!/bin/bash

# EduPass-MG Backup Script
# Usage: ./backup.sh [staging|production]

set -e

ENV=${1:-production}
BACKUP_DIR="/home/deploy/backups/edupass-mg"
DATE=$(date +%Y%m%d_%H%M%S)
COMPOSE_FILE="docker-compose.prod.yml"

echo "🔒 Starting backup for $ENV environment..."

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
echo "📦 Backing up PostgreSQL database..."
docker-compose -f $COMPOSE_FILE exec -T postgres pg_dump -U ${DB_USERNAME} ${DB_DATABASE} | gzip > "$BACKUP_DIR/db_${ENV}_${DATE}.sql.gz"

# Backup storage files
echo "📁 Backing up storage files..."
tar -czf "$BACKUP_DIR/storage_${ENV}_${DATE}.tar.gz" -C /home/deploy/edupass-mg storage/app

# Backup .env file
echo "⚙️  Backing up environment file..."
cp /home/deploy/edupass-mg/.env "$BACKUP_DIR/env_${ENV}_${DATE}.backup"

# Clean old backups (keep last 7 days)
echo "🧹 Cleaning old backups..."
find $BACKUP_DIR -name "db_${ENV}_*.sql.gz" -mtime +7 -delete
find $BACKUP_DIR -name "storage_${ENV}_*.tar.gz" -mtime +7 -delete
find $BACKUP_DIR -name "env_${ENV}_*.backup" -mtime +7 -delete

# List recent backups
echo ""
echo "✅ Backup completed!"
echo ""
echo "📊 Recent backups:"
ls -lh $BACKUP_DIR | tail -n 10

# Calculate backup size
TOTAL_SIZE=$(du -sh $BACKUP_DIR | cut -f1)
echo ""
echo "💾 Total backup size: $TOTAL_SIZE"
