# 🆓 Déploiement Gratuit - EduPass-MG

## 🎯 Options d'Hébergement Gratuit

### ⭐ Option 1: Railway.app (Recommandé)
- **Gratuit** : $5 de crédit/mois (suffisant pour petit usage)
- **PostgreSQL** : Inclus
- **Déploiement** : Automatique depuis GitHub
- **Domaine** : Sous-domaine gratuit fourni
- **Limite** : 500h/mois, 512MB RAM

### ⭐ Option 2: Render.com
- **Gratuit** : Plan Free
- **PostgreSQL** : Inclus (expire après 90 jours)
- **Déploiement** : Automatique depuis GitHub
- **Domaine** : Sous-domaine gratuit
- **Limite** : Service s'endort après 15min d'inactivité

### ⭐ Option 3: Fly.io
- **Gratuit** : 3 machines gratuites
- **PostgreSQL** : Inclus
- **Déploiement** : Via CLI
- **Domaine** : Sous-domaine gratuit
- **Limite** : 160GB trafic/mois

---

## 🚀 Déploiement sur Railway.app (Le Plus Simple)

### Étape 1: Préparer le Projet

#### A. Créer un compte GitHub (si pas déjà fait)
1. Aller sur https://github.com
2. Créer un compte gratuit

#### B. Pousser votre code sur GitHub

```bash
# Depuis votre dossier EduPass-MG
cd C:\Users\STAN\EduPass-MG

# Initialiser Git (si pas déjà fait)
git init

# Ajouter tous les fichiers
git add .

# Commit
git commit -m "Initial commit for Railway deployment"

# Créer un repo sur GitHub
# Aller sur https://github.com/new
# Nom: EduPass-MG
# Private: Oui (recommandé)

# Lier au repo distant
git remote add origin https://github.com/VOTRE_USERNAME/EduPass-MG.git

# Pousser
git branch -M main
git push -u origin main
```

### Étape 2: Créer un Compte Railway

1. Aller sur https://railway.app
2. Cliquer sur **"Start a New Project"**
3. Se connecter avec GitHub
4. Autoriser Railway à accéder à vos repos

### Étape 3: Déployer l'Application

#### A. Créer le projet

1. Cliquer sur **"Deploy from GitHub repo"**
2. Sélectionner **EduPass-MG**
3. Railway va détecter automatiquement que c'est une app Laravel

#### B. Ajouter PostgreSQL

1. Dans votre projet Railway, cliquer sur **"+ New"**
2. Sélectionner **"Database"** → **"PostgreSQL"**
3. Railway va créer une base de données automatiquement

#### C. Configurer les Variables d'Environnement

1. Cliquer sur votre service **"EduPass-MG"**
2. Aller dans l'onglet **"Variables"**
3. Ajouter ces variables :

```env
APP_NAME=EduPass-MG
APP_ENV=production
APP_KEY=base64:GENERER_AVEC_COMMANDE_CI_DESSOUS
APP_DEBUG=false
APP_URL=https://votre-app.up.railway.app

# Railway fournit automatiquement DATABASE_URL
# Mais on doit aussi définir ces variables pour Laravel
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

QUEUE_CONNECTION=database
CACHE_DRIVER=file
SESSION_DRIVER=file

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@edupass.mg
```

#### D. Générer APP_KEY

```bash
# Sur votre ordinateur
php artisan key:generate --show

# Copier la clé générée (ex: base64:xxxxx)
# La coller dans Railway comme valeur de APP_KEY
```

#### E. Créer le fichier railway.json

Créer `railway.json` à la racine du projet :

```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "composer install --optimize-autoloader --no-dev && npm ci && npm run build"
  },
  "deploy": {
    "startCommand": "php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

#### F. Créer Procfile (pour Railway)

Créer `Procfile` à la racine :

```
web: php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

#### G. Pousser les changements

```bash
git add railway.json Procfile
git commit -m "Add Railway configuration"
git push origin main
```

Railway va automatiquement redéployer !

### Étape 4: Accéder à votre Application

1. Dans Railway, cliquer sur votre service
2. Aller dans **"Settings"** → **"Networking"**
3. Cliquer sur **"Generate Domain"**
4. Votre app sera accessible sur : `https://votre-app.up.railway.app`

---

## 🌐 Déploiement sur Render.com (Alternative)

### Étape 1: Créer un Compte

1. Aller sur https://render.com
2. S'inscrire avec GitHub

### Étape 2: Créer une Base de Données PostgreSQL

1. Dans le dashboard, cliquer sur **"New +"** → **"PostgreSQL"**
2. Nom : `edupass-db`
3. Plan : **Free**
4. Cliquer sur **"Create Database"**
5. Noter les informations de connexion

### Étape 3: Créer le Web Service

1. Cliquer sur **"New +"** → **"Web Service"**
2. Connecter votre repo GitHub **EduPass-MG**
3. Configuration :
   - **Name** : `edupass-mg`
   - **Environment** : `Docker`
   - **Plan** : `Free`

### Étape 4: Configurer le Dockerfile pour Render

Créer `Dockerfile.render` :

```dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libpq-dev libzip-dev zip unzip nginx supervisor \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html

# Copy files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Copy configs
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
```

### Étape 5: Variables d'Environnement

Dans Render, ajouter ces variables :

```env
APP_NAME=EduPass-MG
APP_ENV=production
APP_KEY=base64:VOTRE_CLE
APP_DEBUG=false
APP_URL=https://edupass-mg.onrender.com

DB_CONNECTION=pgsql
DB_HOST=COPIER_DEPUIS_RENDER_DB
DB_PORT=5432
DB_DATABASE=COPIER_DEPUIS_RENDER_DB
DB_USERNAME=COPIER_DEPUIS_RENDER_DB
DB_PASSWORD=COPIER_DEPUIS_RENDER_DB

QUEUE_CONNECTION=database
CACHE_DRIVER=file
SESSION_DRIVER=file
```

---

## 🎈 Déploiement sur Fly.io

### Étape 1: Installer Fly CLI

```bash
# Windows (PowerShell)
iwr https://fly.io/install.ps1 -useb | iex
```

### Étape 2: Se Connecter

```bash
fly auth login
```

### Étape 3: Lancer l'Application

```bash
cd C:\Users\STAN\EduPass-MG

# Initialiser Fly
fly launch

# Suivre les instructions :
# - Nom de l'app : edupass-mg
# - Région : Choisir la plus proche (Europe/Afrique)
# - PostgreSQL : Oui
# - Redis : Non (optionnel)
```

### Étape 4: Configurer fly.toml

Fly va créer `fly.toml`. Modifier :

```toml
app = "edupass-mg"
primary_region = "cdg"

[build]
  dockerfile = "docker/Dockerfile"

[env]
  APP_ENV = "production"
  LOG_CHANNEL = "stderr"
  LOG_LEVEL = "info"
  LOG_STDERR_FORMATTER = "Monolog\\Formatter\\JsonFormatter"

[http_service]
  internal_port = 80
  force_https = true
  auto_stop_machines = true
  auto_start_machines = true
  min_machines_running = 0

[[services]]
  protocol = "tcp"
  internal_port = 80

  [[services.ports]]
    port = 80
    handlers = ["http"]

  [[services.ports]]
    port = 443
    handlers = ["tls", "http"]
```

### Étape 5: Déployer

```bash
# Définir les secrets
fly secrets set APP_KEY=$(php artisan key:generate --show)

# Déployer
fly deploy

# Ouvrir dans le navigateur
fly open
```

---

## 📊 Comparaison des Options Gratuites

| Plateforme | Gratuit | PostgreSQL | Limite | Facilité |
|------------|---------|------------|--------|----------|
| **Railway** | $5/mois crédit | ✅ Inclus | 500h/mois | ⭐⭐⭐⭐⭐ |
| **Render** | ✅ Oui | ✅ 90 jours | Sleep après 15min | ⭐⭐⭐⭐ |
| **Fly.io** | ✅ Oui | ✅ Inclus | 3 machines | ⭐⭐⭐ |
| **Heroku** | ❌ Plus gratuit | - | - | - |

---

## ✅ Recommandation

**Pour débuter** : **Railway.app**
- Le plus simple à configurer
- Déploiement automatique depuis GitHub
- PostgreSQL inclus
- Pas de sleep (contrairement à Render)
- $5 de crédit gratuit/mois (largement suffisant pour tester)

**URL finale** : `https://edupass-mg.up.railway.app`

---

## 🆘 Problèmes Courants

### Erreur de migration

```bash
# Se connecter à Railway CLI
railway login

# Exécuter les migrations manuellement
railway run php artisan migrate --force
```

### APP_KEY manquante

```bash
# Générer localement
php artisan key:generate --show

# Copier dans Railway Variables
```

### Erreur 500

```bash
# Voir les logs dans Railway
# Onglet "Deployments" → Cliquer sur le déploiement → "View Logs"
```

---

## 💡 Conseils

1. **Commencer avec Railway** (le plus simple)
2. **Utiliser GitHub** pour le déploiement automatique
3. **Tester d'abord** avec Railway gratuit
4. **Migrer vers VPS payant** quand vous avez des utilisateurs réels

---

**Votre application sera en ligne gratuitement !** 🎉
