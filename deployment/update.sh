#!/bin/bash

# Script de mise à jour rapide pour EduPass-MG
# Usage: ./update.sh

set -e

cd /home/deploy/edupass-mg

echo "🔄 Mise à jour d'EduPass-MG..."
echo ""

# Pull les dernières modifications (si Git est configuré)
if [ -d ".git" ]; then
    echo "📥 Récupération des dernières modifications..."
    git pull origin main
fi

# Pull les nouvelles images Docker
echo "🐳 Téléchargement des nouvelles images..."
docker compose -f docker-compose.prod.yml pull

# Arrêter les anciens conteneurs
echo "🛑 Arrêt des anciens conteneurs..."
docker compose -f docker-compose.prod.yml down

# Démarrer les nouveaux conteneurs
echo "▶️  Démarrage des nouveaux conteneurs..."
docker compose -f docker-compose.prod.yml up -d

# Attendre que les services soient prêts
echo "⏳ Attente du démarrage des services..."
sleep 15

# Exécuter les migrations
echo "🗄️  Exécution des migrations..."
docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force

# Optimiser les caches
echo "⚡ Optimisation des caches..."
docker compose -f docker-compose.prod.yml exec -T app php artisan config:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan route:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan view:cache

# Redémarrer les queue workers
echo "🔄 Redémarrage des workers..."
docker compose -f docker-compose.prod.yml exec -T app php artisan queue:restart

# Nettoyer les anciennes images
echo "🧹 Nettoyage des anciennes images..."
docker image prune -af

echo ""
echo "✅ Mise à jour terminée avec succès !"
echo ""
echo "🏥 Vérification de la santé de l'application..."
sleep 3

# Health check
if curl -f http://localhost:${APP_PORT:-8080}/health > /dev/null 2>&1; then
    echo "✅ Application en bonne santé !"
else
    echo "⚠️  Attention: Le health check a échoué. Vérifiez les logs:"
    echo "   docker compose -f docker-compose.prod.yml logs -f"
fi

echo ""
echo "📊 État des conteneurs:"
docker compose -f docker-compose.prod.yml ps
