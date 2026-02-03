# Guide de Déploiement EduPass-MG

## 📱 Déploiement Play Store

### Prérequis

1. **Compte Google Play Console** (25$ one-time fee)
2. **EAS CLI installé**: `npm install -g eas-cli`
3. **Compte Expo** (gratuit)

### Étapes de Déploiement

#### 1. Configuration Initiale

```bash
cd edupass-mobile

# Login to Expo
eas login

# Configure project
eas build:configure
```

#### 2. Build APK pour Test

```bash
# Build APK de test
eas build --platform android --profile preview

# Télécharger et installer sur appareil Android
```

#### 3. Build AAB pour Production

```bash
# Build AAB pour Play Store
eas build --platform android --profile production
```

#### 4. Soumission au Play Store

**Option A: Automatique (recommandé)**
```bash
# Configurer service account
# 1. Créer service account dans Google Cloud Console
# 2. Télécharger JSON key
# 3. Placer dans google-play-service-account.json

# Soumettre
eas submit --platform android
```

**Option B: Manuel**
1. Télécharger le fichier `.aab` depuis Expo
2. Aller sur [Google Play Console](https://play.google.com/console)
3. Créer une nouvelle application
4. Remplir les informations (nom, description, screenshots)
5. Upload le fichier `.aab` dans "Production" ou "Internal Testing"
6. Soumettre pour review

### Configuration Play Store

**Informations requises:**
- **Nom**: EduPass-MG
- **Description courte**: Plateforme éducative pour la gestion des paiements et convocations
- **Description complète**: [Voir ci-dessous]
- **Catégorie**: Éducation
- **Screenshots**: Minimum 2 (voir section Assets)
- **Icône**: 512x512px (fourni dans assets/)

**Description complète suggérée:**
```
EduPass-MG est la plateforme éducative officielle pour la gestion des paiements scolaires et des convocations d'examens à Madagascar.

Fonctionnalités :
✅ Paiement sécurisé via MVola, Orange Money, Airtel Money
✅ Consultation des convocations d'examens
✅ Téléchargement des reçus de paiement
✅ Notifications en temps réel
✅ Interface simple et intuitive

Sécurité :
🔒 Authentification OTP/2FA
🔒 Données cryptées
🔒 Conformité RGPD
```

---

## 🌐 Déploiement Web (Serveur)

### Architecture Recommandée

```
┌─────────────────┐
│   Cloudflare    │ (DNS + CDN + SSL)
└────────┬────────┘
         │
┌────────▼────────┐
│   Nginx Proxy   │ (Reverse Proxy)
└────────┬────────┘
         │
    ┌────┴────┐
    │         │
┌───▼──┐  ┌──▼───┐
│ Web  │  │ API  │
│ App  │  │ v1   │
└──────┘  └──────┘
    │         │
    └────┬────┘
         │
    ┌────▼────┐
    │PostgreSQL│
    └─────────┘
```

### Option 1: VPS (Recommandé pour Production)

**Providers suggérés:**
- **DigitalOcean** (5$/mois - 1GB RAM)
- **Linode** (5$/mois - 1GB RAM)
- **Vultr** (6$/mois - 1GB RAM)

**Stack:**
- Ubuntu 22.04 LTS
- Nginx
- PHP 8.2-FPM
- PostgreSQL 15
- Supervisor (pour queues)

#### Installation Serveur

```bash
# 1. Connexion SSH
ssh root@your-server-ip

# 2. Mise à jour
apt update && apt upgrade -y

# 3. Installation Stack
apt install -y nginx postgresql php8.2-fpm php8.2-pgsql php8.2-mbstring \
  php8.2-xml php8.2-curl php8.2-zip git composer supervisor

# 4. Configuration PostgreSQL
sudo -u postgres psql
CREATE DATABASE edupass_mg;
CREATE USER edupass WITH PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE edupass_mg TO edupass;
\q

# 5. Clone projet
cd /var/www
git clone https://github.com/your-repo/EduPass-MG.git edupass-mg
cd edupass-mg

# 6. Installation dépendances
composer install --no-dev --optimize-autoloader
npm install && npm run build

# 7. Configuration .env
cp .env.example .env
nano .env
# Modifier DB_CONNECTION=pgsql, DB_DATABASE, DB_USERNAME, DB_PASSWORD
# Modifier APP_URL avec votre domaine/IP

# 8. Migrations
php artisan key:generate
php artisan migrate --force
php artisan db:seed --class=RolesPermissionsSeeder

# 9. Permissions
chown -R www-data:www-data /var/www/edupass-mg
chmod -R 755 /var/www/edupass-mg/storage
chmod -R 755 /var/www/edupass-mg/bootstrap/cache
```

#### Configuration Nginx

```nginx
# /etc/nginx/sites-available/edupass-mg
server {
    listen 80;
    server_name your-domain.com;  # ou votre IP
    root /var/www/edupass-mg/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Activer site
ln -s /etc/nginx/sites-available/edupass-mg /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

#### SSL avec Let's Encrypt

```bash
# Installation Certbot
apt install -y certbot python3-certbot-nginx

# Obtenir certificat SSL
certbot --nginx -d your-domain.com

# Auto-renouvellement (déjà configuré)
certbot renew --dry-run
```

### Option 2: Hébergement Partagé (Budget limité)

**Providers:**
- **Hostinger** (2$/mois)
- **Namecheap** (3$/mois)

**Limitations:**
- Pas de contrôle total sur le serveur
- PostgreSQL peut ne pas être disponible (utiliser MySQL)
- Pas de Supervisor pour queues

### Configuration URL Dynamique (Mobile App)

**Écran de configuration dans l'app:**

```javascript
// src/screens/Settings/ApiConfigScreen.js
import React, { useState, useEffect } from 'react';
import { View, TextInput, Button, Alert } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function ApiConfigScreen() {
  const [apiUrl, setApiUrl] = useState('');

  useEffect(() => {
    loadApiUrl();
  }, []);

  const loadApiUrl = async () => {
    const url = await AsyncStorage.getItem('API_URL');
    setApiUrl(url || 'http://localhost:9000/api/v1');
  };

  const saveApiUrl = async () => {
    await AsyncStorage.setItem('API_URL', apiUrl);
    Alert.alert('Succès', 'URL API mise à jour. Redémarrez l\'application.');
  };

  return (
    <View style={{ padding: 20 }}>
      <TextInput
        value={apiUrl}
        onChangeText={setApiUrl}
        placeholder="https://api.edupass-mg.com/api/v1"
        style={{ borderWidth: 1, padding: 10, marginBottom: 10 }}
      />
      <Button title="Sauvegarder" onPress={saveApiUrl} />
    </View>
  );
}
```

**URLs suggérées:**
- **Développement**: `http://localhost:9000/api/v1`
- **Staging**: `http://your-server-ip/api/v1`
- **Production**: `https://api.edupass-mg.com/api/v1`

---

## 🚀 CI/CD (Optionnel mais Recommandé)

### GitHub Actions

```yaml
# .github/workflows/deploy.yml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  deploy-web:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.SERVER_HOST }}
          username: ${{ secrets.SERVER_USER }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /var/www/edupass-mg
            git pull origin main
            composer install --no-dev
            php artisan migrate --force
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache

  build-mobile:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: 18
      
      - name: Install EAS CLI
        run: npm install -g eas-cli
      
      - name: Build Android
        run: |
          cd edupass-mobile
          eas build --platform android --non-interactive
        env:
          EXPO_TOKEN: ${{ secrets.EXPO_TOKEN }}
```

---

## 📊 Monitoring & Maintenance

### Logs

```bash
# Laravel logs
tail -f /var/www/edupass-mg/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# PostgreSQL logs
tail -f /var/log/postgresql/postgresql-15-main.log
```

### Backup Automatique

```bash
# Script backup PostgreSQL
# /usr/local/bin/backup-db.sh
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
pg_dump -U edupass edupass_mg > /backups/edupass_$DATE.sql
find /backups -name "edupass_*.sql" -mtime +7 -delete

# Cron job (tous les jours à 2h)
crontab -e
0 2 * * * /usr/local/bin/backup-db.sh
```

---

## 📝 Checklist Déploiement

### Web
- [ ] Serveur configuré (VPS ou hébergement)
- [ ] PostgreSQL installé et configuré
- [ ] Code déployé via Git
- [ ] .env configuré (DB, APP_URL, etc.)
- [ ] Migrations exécutées
- [ ] SSL configuré (Let's Encrypt)
- [ ] Nginx configuré
- [ ] Permissions fichiers correctes
- [ ] Backup automatique configuré

### Mobile
- [ ] Compte Google Play Console créé
- [ ] app.json configuré
- [ ] eas.json configuré
- [ ] Build APK testé
- [ ] Build AAB production créé
- [ ] Screenshots et assets préparés
- [ ] Description Play Store rédigée
- [ ] Application soumise pour review

### Post-Déploiement
- [ ] Tests de l'API depuis mobile
- [ ] Tests de paiement MVola
- [ ] Tests d'envoi email/SMS
- [ ] Monitoring configuré
- [ ] Documentation utilisateur créée
