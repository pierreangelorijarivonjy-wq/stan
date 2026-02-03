# 🚀 Guide Rapide de Déploiement CI/CD

## ✅ Fichiers Créés

### Configuration Docker
- ✅ `docker/Dockerfile` - Image Laravel optimisée
- ✅ `docker/nginx/default.conf` - Configuration Nginx
- ✅ `docker/supervisor/supervisord.conf` - Gestion des processus
- ✅ `docker/php/php.ini` - Configuration PHP production
- ✅ `docker-compose.prod.yml` - Orchestration production
- ✅ `.dockerignore` - Optimisation de l'image

### CI/CD
- ✅ `.github/workflows/ci-cd.yml` - Pipeline GitHub Actions
- ✅ `deployment/deploy.sh` - Script de déploiement
- ✅ `deployment/.env.production.example` - Variables d'environnement
- ✅ `routes/health.php` - Health check endpoint

---

## 📋 Checklist de Déploiement

### 1. Configurer GitHub Secrets

Aller sur : `Settings → Secrets and variables → Actions → New repository secret`

```
DOCKER_USERNAME=karibo01
DOCKER_TOKEN=dckr_pat_xxxxx
STAGING_HOST=192.168.1.100
STAGING_USER=deploy
STAGING_SSH_KEY=-----BEGIN RSA PRIVATE KEY-----...
STAGING_PORT=22
PRODUCTION_HOST=100.112.134.63
PRODUCTION_USER=deploy
PRODUCTION_SSH_KEY=-----BEGIN RSA PRIVATE KEY-----...
PRODUCTION_PORT=22
```

### 2. Créer un Repo Docker Hub

```bash
# Aller sur https://hub.docker.com/repositories
# Créer un nouveau repository : edupass-mg (Private)
```

### 3. Préparer les Serveurs

**Sur Staging ET Production** :

```bash
# Créer l'utilisateur deploy
sudo useradd -m -s /bin/bash deploy
sudo usermod -aG docker deploy

# Créer le dossier de déploiement
sudo mkdir -p /home/deploy/edupass-mg
sudo chown deploy:deploy /home/deploy/edupass-mg

# Copier les fichiers
scp docker-compose.prod.yml deploy@SERVER:/home/deploy/edupass-mg/
scp deployment/.env.production.example deploy@SERVER:/home/deploy/edupass-mg/.env

# Éditer .env sur le serveur
ssh deploy@SERVER
cd /home/deploy/edupass-mg
nano .env  # Remplir les valeurs
```

### 4. Générer la Clé SSH

```bash
# Sur votre machine locale
ssh-keygen -t rsa -b 4096 -C "github-actions" -f ~/.ssh/github_actions

# Copier la clé publique sur les serveurs
ssh-copy-id -i ~/.ssh/github_actions.pub deploy@STAGING_HOST
ssh-copy-id -i ~/.ssh/github_actions.pub deploy@PRODUCTION_HOST

# Copier la clé privée dans GitHub Secrets
cat ~/.ssh/github_actions  # Copier tout le contenu
```

### 5. Ajouter le Health Check aux Routes

Éditer `routes/web.php` et ajouter :

```php
require __DIR__.'/health.php';
```

### 6. Premier Déploiement

```bash
git add .
git commit -m "Setup CI/CD pipeline"
git push origin main
```

---

## 🔍 Vérification

### Tester localement

```bash
# Build l'image
docker build -t edupass-mg:test -f docker/Dockerfile .

# Lancer avec docker-compose
docker-compose -f docker-compose.prod.yml up -d

# Vérifier les logs
docker-compose -f docker-compose.prod.yml logs -f

# Tester le health check
curl http://localhost:8080/health
```

### Surveiller le déploiement

1. Aller sur GitHub → Actions
2. Observer les étapes : Test → Build → Deploy
3. Vérifier les logs en cas d'erreur

### Accéder aux applications

- **Staging** : `http://STAGING_HOST:8080`
- **Production** : `https://edupass.mg`

---

## 🛠️ Commandes Utiles

### Sur le serveur

```bash
cd /home/deploy/edupass-mg

# Voir les logs
docker-compose -f docker-compose.prod.yml logs -f app

# Redémarrer les services
docker-compose -f docker-compose.prod.yml restart

# Exécuter des commandes Artisan
docker-compose -f docker-compose.prod.yml exec app php artisan migrate
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear

# Voir l'état des conteneurs
docker-compose -f docker-compose.prod.yml ps
```

### Déploiement manuel

```bash
# Rendre le script exécutable
chmod +x deployment/deploy.sh

# Déployer en staging
./deployment/deploy.sh staging

# Déployer en production
./deployment/deploy.sh production
```

---

## 🚨 Dépannage

### Le build échoue

```bash
# Vérifier les logs GitHub Actions
# Vérifier que composer.json et package.json sont valides
# Vérifier que .env.example existe
```

### Le déploiement échoue

```bash
# Vérifier la connexion SSH
ssh deploy@SERVER

# Vérifier que Docker est installé
docker --version

# Vérifier les permissions
ls -la /home/deploy/edupass-mg
```

### L'application ne démarre pas

```bash
# Vérifier les logs
docker-compose -f docker-compose.prod.yml logs app

# Vérifier le health check
curl http://localhost:8080/health

# Vérifier les variables d'environnement
docker-compose -f docker-compose.prod.yml exec app env | grep APP_
```

---

## 📚 Ressources

- [Guide complet](GUIDE_DEPLOIEMENT_CICD.md)
- [Docker Hub](https://hub.docker.com/)
- [GitHub Actions Docs](https://docs.github.com/en/actions)

---

**Prêt à déployer !** 🎉
