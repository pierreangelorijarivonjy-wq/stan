# 🚀 Déploiement Rapide - EduPass-MG sur VPS

## Option 1: Installation Automatique (Recommandé)

### Sur votre VPS (Ubuntu 22.04)

```bash
# Se connecter en SSH
ssh root@VOTRE_IP_VPS

# Télécharger et exécuter le script d'installation
curl -sSL https://raw.githubusercontent.com/YOUR_REPO/main/deployment/install-vps.sh | sudo bash

# Suivre les instructions affichées
```

---

## Option 2: Installation Manuelle

### 1. Préparer le VPS

```bash
# Mise à jour
sudo apt update && sudo apt upgrade -y

# Installer Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Créer l'utilisateur deploy
sudo adduser deploy
sudo usermod -aG docker deploy
sudo usermod -aG sudo deploy
```

### 2. Copier les fichiers

```bash
# Depuis votre ordinateur Windows
scp -r C:\Users\STAN\EduPass-MG deploy@VOTRE_IP:/home/deploy/edupass-mg
```

### 3. Configurer et démarrer

```bash
# Sur le VPS
cd /home/deploy/edupass-mg

# Configurer .env
cp deployment/.env.production.example .env
nano .env  # Remplir les valeurs

# Générer APP_KEY
php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
# Copier dans .env

# Démarrer
docker compose -f docker-compose.prod.yml up -d

# Initialiser la DB
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LmsPermissionsSeeder
```

---

## 🌐 Accéder à votre application

```
http://VOTRE_IP_VPS:8080
```

---

## 🔄 Mettre à jour

```bash
cd /home/deploy/edupass-mg
./deployment/update.sh
```

---

## 📊 Commandes Utiles

```bash
# Voir les logs
docker compose -f docker-compose.prod.yml logs -f

# Redémarrer
docker compose -f docker-compose.prod.yml restart

# Arrêter
docker compose -f docker-compose.prod.yml down

# Backup DB
docker compose -f docker-compose.prod.yml exec postgres pg_dump -U edupass_user edupass_prod > backup.sql
```

---

## 🆘 Problèmes Courants

### Port 8080 déjà utilisé
```bash
# Changer le port dans .env
APP_PORT=8081

# Redémarrer
docker compose -f docker-compose.prod.yml down
docker compose -f docker-compose.prod.yml up -d
```

### Erreur de connexion DB
```bash
# Vérifier les logs
docker compose -f docker-compose.prod.yml logs postgres

# Vérifier .env
cat .env | grep DB_
```

---

## 💰 Coût: ~$12/mois

- VPS 2GB RAM: $10-12/mois
- Domaine: ~$1/mois
- SSL: Gratuit (Let's Encrypt)

---

**Guide complet**: [GUIDE_DEPLOIEMENT_VPS.md](GUIDE_DEPLOIEMENT_VPS.md)
