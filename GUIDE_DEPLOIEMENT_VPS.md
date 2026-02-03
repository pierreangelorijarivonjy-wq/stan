# 🌐 Guide de Déploiement Production - EduPass-MG

## 🎯 Objectif
Déployer EduPass-MG sur un serveur VPS pour qu'il soit accessible 24/7 sur Internet, sans dépendre de votre ordinateur local.

---

## 📋 Prérequis

### 1. Serveur VPS
Vous avez besoin d'un serveur VPS avec :
- **OS** : Ubuntu 22.04 LTS (recommandé)
- **RAM** : Minimum 2GB (4GB recommandé)
- **CPU** : 2 cores minimum
- **Stockage** : 20GB minimum
- **IP publique** : Pour accès Internet

**Fournisseurs recommandés** :
- **DigitalOcean** (~$12/mois) - Simple et fiable
- **Vultr** (~$10/mois) - Bon rapport qualité/prix
- **Linode** (~$12/mois) - Performant
- **OVH** (~€6/mois) - Moins cher, serveurs en Europe

### 2. Nom de Domaine
- Acheter un domaine (ex: `edupass.mg` ou `edupass.com`)
- Fournisseurs : Namecheap, GoDaddy, OVH, etc.
- Prix : ~$10-15/an

---

## 🚀 Étape 1 : Créer et Configurer le VPS

### A. Créer le VPS (Exemple DigitalOcean)

1. **Créer un compte** sur https://digitalocean.com
2. **Créer un Droplet** :
   - Image : Ubuntu 22.04 LTS
   - Plan : Basic - $12/mois (2GB RAM, 1 CPU)
   - Datacenter : Choisir le plus proche (Europe/Afrique)
   - Authentication : SSH Key (recommandé) ou Password

3. **Noter l'IP publique** : Ex: `123.45.67.89`

### B. Première Connexion SSH

```bash
# Depuis votre ordinateur
ssh root@123.45.67.89

# Si vous utilisez une clé SSH
ssh -i ~/.ssh/id_rsa root@123.45.67.89
```

---

## 🔧 Étape 2 : Préparer le Serveur

### A. Mettre à jour le système

```bash
apt update && apt upgrade -y
```

### B. Créer un utilisateur non-root

```bash
# Créer l'utilisateur 'deploy'
adduser deploy

# Ajouter aux sudoers
usermod -aG sudo deploy

# Autoriser SSH pour deploy
rsync --archive --chown=deploy:deploy ~/.ssh /home/deploy
```

### C. Se connecter avec le nouvel utilisateur

```bash
# Depuis votre ordinateur
ssh deploy@123.45.67.89
```

---

## 🐳 Étape 3 : Installer Docker

```bash
# Installer les dépendances
sudo apt install -y apt-transport-https ca-certificates curl software-properties-common

# Ajouter la clé GPG Docker
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# Ajouter le repository Docker
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Installer Docker
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# Ajouter l'utilisateur au groupe docker
sudo usermod -aG docker deploy

# Redémarrer la session (ou se reconnecter)
newgrp docker

# Vérifier l'installation
docker --version
docker compose version
```

---

## 📦 Étape 4 : Déployer EduPass-MG

### A. Cloner le projet

```bash
# Créer le dossier de déploiement
mkdir -p /home/deploy/edupass-mg
cd /home/deploy/edupass-mg

# Cloner depuis GitHub (si votre repo est privé, utilisez un token)
git clone https://github.com/VOTRE_USERNAME/EduPass-MG.git .

# OU copier les fichiers depuis votre ordinateur
# scp -r C:\Users\STAN\EduPass-MG deploy@123.45.67.89:/home/deploy/edupass-mg
```

### B. Configurer les variables d'environnement

```bash
# Copier le template
cp deployment/.env.production.example .env

# Éditer le fichier .env
nano .env
```

**Remplir les valeurs importantes** :

```env
APP_NAME="EduPass-MG"
APP_ENV=production
APP_KEY=base64:GENERER_AVEC_php_artisan_key:generate
APP_DEBUG=false
APP_URL=http://123.45.67.89  # Votre IP (ou domaine plus tard)
APP_PORT=80

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=edupass_prod
DB_USERNAME=edupass_user
DB_PASSWORD=CHOISIR_MOT_DE_PASSE_FORT_ICI

REDIS_HOST=redis
REDIS_PASSWORD=CHOISIR_MOT_DE_PASSE_REDIS

# Mail (Gmail exemple)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@edupass.mg

# MVola (si vous avez les credentials)
MVOLA_CLIENT_ID=votre_client_id
MVOLA_CLIENT_SECRET=votre_client_secret
MVOLA_MERCHANT_MSISDN=034XXXXXXX
```

### C. Générer la clé d'application

```bash
# Installer PHP temporairement pour générer la clé
sudo apt install -y php8.2-cli

# Générer la clé
php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"

# Copier la clé générée dans .env à la ligne APP_KEY
```

### D. Démarrer les services

```bash
# Démarrer avec Docker Compose
docker compose -f docker-compose.prod.yml up -d

# Vérifier que les conteneurs tournent
docker compose -f docker-compose.prod.yml ps

# Voir les logs
docker compose -f docker-compose.prod.yml logs -f
```

### E. Initialiser la base de données

```bash
# Attendre que les services soient prêts (30 secondes)
sleep 30

# Exécuter les migrations
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Créer les permissions et rôles
docker compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LmsPermissionsSeeder

# Optimiser pour production
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache
```

---

## 🌐 Étape 5 : Configurer le Pare-feu

```bash
# Installer UFW (Uncomplicated Firewall)
sudo apt install -y ufw

# Autoriser SSH (IMPORTANT !)
sudo ufw allow 22/tcp

# Autoriser HTTP
sudo ufw allow 80/tcp

# Autoriser HTTPS (pour plus tard)
sudo ufw allow 443/tcp

# Activer le pare-feu
sudo ufw enable

# Vérifier le status
sudo ufw status
```

---

## ✅ Étape 6 : Tester l'Application

### A. Accéder depuis votre navigateur

```
http://123.45.67.89
```

Vous devriez voir la page d'accueil d'EduPass-MG !

### B. Vérifier le health check

```
http://123.45.67.89/health
```

Devrait retourner :
```json
{
  "status": "healthy",
  "checks": {
    "app": "ok",
    "database": "ok",
    "redis": "ok"
  }
}
```

---

## 🔒 Étape 7 : Configurer le Nom de Domaine (Optionnel mais Recommandé)

### A. Configurer les DNS

Dans votre registrar de domaine (Namecheap, GoDaddy, etc.) :

1. Créer un enregistrement **A** :
   - Nom : `@` (ou vide)
   - Type : `A`
   - Valeur : `123.45.67.89` (votre IP VPS)
   - TTL : 3600

2. Créer un enregistrement **A** pour www :
   - Nom : `www`
   - Type : `A`
   - Valeur : `123.45.67.89`
   - TTL : 3600

**Attendre 1-24h** pour la propagation DNS.

### B. Installer SSL (HTTPS) avec Let's Encrypt

```bash
# Installer Certbot
sudo apt install -y certbot python3-certbot-nginx

# Installer Nginx (proxy inverse)
sudo apt install -y nginx

# Créer la configuration Nginx
sudo nano /etc/nginx/sites-available/edupass
```

**Contenu du fichier** :

```nginx
server {
    listen 80;
    server_name edupass.mg www.edupass.mg;

    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

```bash
# Activer le site
sudo ln -s /etc/nginx/sites-available/edupass /etc/nginx/sites-enabled/

# Tester la configuration
sudo nginx -t

# Redémarrer Nginx
sudo systemctl restart nginx

# Obtenir le certificat SSL
sudo certbot --nginx -d edupass.mg -d www.edupass.mg

# Suivre les instructions (entrer votre email, accepter les termes)
```

Maintenant votre site est accessible en **HTTPS** : `https://edupass.mg` 🎉

---

## 🔄 Étape 8 : Automatiser les Mises à Jour

### A. Créer un script de mise à jour

```bash
nano /home/deploy/edupass-mg/update.sh
```

**Contenu** :

```bash
#!/bin/bash
cd /home/deploy/edupass-mg

# Pull les dernières modifications
git pull origin main

# Rebuild et redémarrer
docker compose -f docker-compose.prod.yml pull
docker compose -f docker-compose.prod.yml up -d --build

# Migrations
docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force

# Optimisations
docker compose -f docker-compose.prod.yml exec -T app php artisan config:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan route:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan view:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan queue:restart

echo "✅ Mise à jour terminée !"
```

```bash
# Rendre exécutable
chmod +x /home/deploy/edupass-mg/update.sh

# Utiliser pour mettre à jour
./update.sh
```

---

## 📊 Étape 9 : Monitoring et Maintenance

### A. Voir les logs

```bash
# Logs de l'application
docker compose -f docker-compose.prod.yml logs -f app

# Logs de la base de données
docker compose -f docker-compose.prod.yml logs -f postgres

# Logs Redis
docker compose -f docker-compose.prod.yml logs -f redis
```

### B. Redémarrer les services

```bash
# Redémarrer tous les services
docker compose -f docker-compose.prod.yml restart

# Redémarrer seulement l'app
docker compose -f docker-compose.prod.yml restart app
```

### C. Backup automatique

```bash
# Créer un cron job pour backup quotidien
crontab -e

# Ajouter cette ligne (backup à 2h du matin)
0 2 * * * /home/deploy/edupass-mg/deployment/backup.sh production
```

---

## 🎉 Résultat Final

Votre application EduPass-MG est maintenant :

✅ **Accessible 24/7** sur Internet  
✅ **Indépendante** de votre ordinateur local  
✅ **Sécurisée** avec HTTPS (si domaine configuré)  
✅ **Performante** avec Docker + PostgreSQL + Redis  
✅ **Sauvegardée** automatiquement  
✅ **Facile à mettre à jour** avec le script update.sh

**URL d'accès** :
- Par IP : `http://123.45.67.89`
- Par domaine : `https://edupass.mg` (si configuré)

---

## 🆘 Dépannage

### L'application ne démarre pas

```bash
# Vérifier les logs
docker compose -f docker-compose.prod.yml logs app

# Vérifier que les services tournent
docker compose -f docker-compose.prod.yml ps

# Redémarrer
docker compose -f docker-compose.prod.yml down
docker compose -f docker-compose.prod.yml up -d
```

### Erreur de connexion à la base de données

```bash
# Vérifier PostgreSQL
docker compose -f docker-compose.prod.yml exec postgres psql -U edupass_user -d edupass_prod

# Vérifier les credentials dans .env
cat .env | grep DB_
```

### Le site est lent

```bash
# Vérifier les ressources
docker stats

# Optimiser les caches
docker compose -f docker-compose.prod.yml exec app php artisan optimize
```

---

## 💰 Coûts Mensuels Estimés

| Service | Coût | Notes |
|---------|------|-------|
| VPS (2GB RAM) | $10-12/mois | DigitalOcean, Vultr, Linode |
| Nom de domaine | ~$1/mois | (~$12/an) |
| SSL | Gratuit | Let's Encrypt |
| **TOTAL** | **~$11-13/mois** | |

---

**Votre plateforme est maintenant en production !** 🚀
