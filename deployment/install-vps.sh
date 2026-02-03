#!/bin/bash

# Script d'installation automatique pour EduPass-MG sur VPS
# Usage: curl -sSL https://raw.githubusercontent.com/YOUR_REPO/main/deployment/install-vps.sh | bash

set -e

echo "🚀 Installation d'EduPass-MG sur VPS"
echo "===================================="
echo ""

# Vérifier que le script est exécuté en tant que root ou avec sudo
if [ "$EUID" -ne 0 ]; then 
    echo "❌ Ce script doit être exécuté en tant que root ou avec sudo"
    exit 1
fi

# Variables
DEPLOY_USER="deploy"
DEPLOY_DIR="/home/$DEPLOY_USER/edupass-mg"
REPO_URL="https://github.com/YOUR_USERNAME/EduPass-MG.git"

echo "📦 Étape 1/7: Mise à jour du système..."
apt update && apt upgrade -y

echo "👤 Étape 2/7: Création de l'utilisateur deploy..."
if id "$DEPLOY_USER" &>/dev/null; then
    echo "✓ L'utilisateur $DEPLOY_USER existe déjà"
else
    adduser --disabled-password --gecos "" $DEPLOY_USER
    usermod -aG sudo $DEPLOY_USER
    echo "$DEPLOY_USER ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers.d/$DEPLOY_USER
    echo "✓ Utilisateur $DEPLOY_USER créé"
fi

echo "🐳 Étape 3/7: Installation de Docker..."
if command -v docker &> /dev/null; then
    echo "✓ Docker est déjà installé"
else
    # Installer les dépendances
    apt install -y apt-transport-https ca-certificates curl software-properties-common

    # Ajouter la clé GPG Docker
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

    # Ajouter le repository Docker
    echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null

    # Installer Docker
    apt update
    apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

    # Ajouter l'utilisateur au groupe docker
    usermod -aG docker $DEPLOY_USER

    echo "✓ Docker installé avec succès"
fi

echo "🔥 Étape 4/7: Configuration du pare-feu..."
apt install -y ufw
ufw --force enable
ufw allow 22/tcp   # SSH
ufw allow 80/tcp   # HTTP
ufw allow 443/tcp  # HTTPS
echo "✓ Pare-feu configuré"

echo "📁 Étape 5/7: Création du dossier de déploiement..."
mkdir -p $DEPLOY_DIR
chown -R $DEPLOY_USER:$DEPLOY_USER $DEPLOY_DIR
echo "✓ Dossier créé: $DEPLOY_DIR"

echo "🔧 Étape 6/7: Installation des outils..."
apt install -y git nginx certbot python3-certbot-nginx php8.2-cli
echo "✓ Outils installés"

echo "📝 Étape 7/7: Configuration initiale..."
# Créer le fichier docker-compose.prod.yml
cat > $DEPLOY_DIR/docker-compose.prod.yml <<'EOF'
version: '3.8'

services:
  app:
    image: ${DOCKER_USERNAME:-karibo01}/edupass-mg:${IMAGE_TAG:-latest}
    container_name: edupass-app
    restart: unless-stopped
    ports:
      - "${APP_PORT:-8080}:80"
    environment:
      - APP_NAME=${APP_NAME}
      - APP_ENV=${APP_ENV:-production}
      - APP_KEY=${APP_KEY}
      - APP_DEBUG=${APP_DEBUG:-false}
      - APP_URL=${APP_URL}
      - DB_CONNECTION=pgsql
      - DB_HOST=postgres
      - DB_PORT=5432
      - DB_DATABASE=${DB_DATABASE}
      - DB_USERNAME=${DB_USERNAME}
      - DB_PASSWORD=${DB_PASSWORD}
      - REDIS_HOST=redis
      - REDIS_PASSWORD=${REDIS_PASSWORD:-null}
      - REDIS_PORT=6379
    depends_on:
      postgres:
        condition: service_healthy
      redis:
        condition: service_healthy
    networks:
      - edupass-network
    volumes:
      - storage-data:/var/www/html/storage/app
      - logs-data:/var/www/html/storage/logs

  postgres:
    image: postgres:15-alpine
    container_name: edupass-postgres
    restart: unless-stopped
    environment:
      POSTGRES_DB: ${DB_DATABASE}
      POSTGRES_USER: ${DB_USERNAME}
      POSTGRES_PASSWORD: ${DB_PASSWORD}
      PGDATA: /var/lib/postgresql/data/pgdata
    volumes:
      - postgres-data:/var/lib/postgresql/data
    networks:
      - edupass-network
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U ${DB_USERNAME} -d ${DB_DATABASE}"]
      interval: 10s
      timeout: 5s
      retries: 5

  redis:
    image: redis:7-alpine
    container_name: edupass-redis
    restart: unless-stopped
    command: redis-server --appendonly yes
    volumes:
      - redis-data:/data
    networks:
      - edupass-network
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 3s
      retries: 5

volumes:
  postgres-data:
  redis-data:
  storage-data:
  logs-data:

networks:
  edupass-network:
    driver: bridge
EOF

# Créer le fichier .env template
cat > $DEPLOY_DIR/.env.example <<'EOF'
APP_NAME="EduPass-MG"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://YOUR_IP_OR_DOMAIN
APP_PORT=8080

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=edupass_prod
DB_USERNAME=edupass_user
DB_PASSWORD=CHANGE_THIS_PASSWORD

REDIS_HOST=redis
REDIS_PASSWORD=
REDIS_PORT=6379

QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@edupass.mg
MAIL_FROM_NAME="${APP_NAME}"
EOF

chown -R $DEPLOY_USER:$DEPLOY_USER $DEPLOY_DIR

echo ""
echo "✅ Installation terminée !"
echo ""
echo "📋 Prochaines étapes:"
echo "1. Se connecter en tant que deploy:"
echo "   su - $DEPLOY_USER"
echo ""
echo "2. Aller dans le dossier:"
echo "   cd $DEPLOY_DIR"
echo ""
echo "3. Copier et configurer .env:"
echo "   cp .env.example .env"
echo "   nano .env"
echo ""
echo "4. Générer la clé d'application:"
echo "   php -r \"echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;\""
echo "   # Copier la clé dans .env (APP_KEY)"
echo ""
echo "5. Démarrer les services:"
echo "   docker compose -f docker-compose.prod.yml up -d"
echo ""
echo "6. Initialiser la base de données:"
echo "   docker compose -f docker-compose.prod.yml exec app php artisan migrate --force"
echo ""
echo "7. Accéder à l'application:"
echo "   http://$(curl -s ifconfig.me)"
echo ""
echo "📖 Guide complet: GUIDE_DEPLOIEMENT_VPS.md"
