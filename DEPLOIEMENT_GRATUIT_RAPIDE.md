# 🆓 Déploiement Gratuit - Guide Rapide

## ⭐ Méthode Recommandée: Railway.app

### 1. Préparer GitHub

```bash
cd C:\Users\STAN\EduPass-MG

# Initialiser Git
git init
git add .
git commit -m "Initial commit"

# Créer repo sur https://github.com/new
# Nom: EduPass-MG, Private: Oui

# Pousser
git remote add origin https://github.com/VOTRE_USERNAME/EduPass-MG.git
git branch -M main
git push -u origin main
```

### 2. Déployer sur Railway

1. **Créer compte** : https://railway.app (connexion avec GitHub)
2. **Nouveau projet** : "Deploy from GitHub repo" → Sélectionner EduPass-MG
3. **Ajouter PostgreSQL** : "+ New" → "Database" → "PostgreSQL"
4. **Variables d'environnement** :

```env
APP_NAME=EduPass-MG
APP_ENV=production
APP_KEY=GENERER_AVEC_php_artisan_key:generate_--show
APP_DEBUG=false
APP_URL=https://votre-app.up.railway.app

DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

QUEUE_CONNECTION=database
CACHE_DRIVER=file
SESSION_DRIVER=file
```

5. **Générer domaine** : Settings → Networking → "Generate Domain"

### 3. Accéder à l'Application

```
https://votre-app.up.railway.app
```

---

## 📊 Autres Options Gratuites

### Render.com
- Gratuit mais service s'endort après 15min
- PostgreSQL gratuit 90 jours
- https://render.com

### Fly.io
- 3 machines gratuites
- PostgreSQL inclus
- Nécessite CLI
- https://fly.io

---

## 💰 Coût

**Railway** : $5 crédit/mois (gratuit pour commencer)  
**Render** : 100% gratuit (avec limitations)  
**Fly.io** : Gratuit jusqu'à 3 machines

---

## 🆘 Problèmes

### APP_KEY manquante
```bash
php artisan key:generate --show
# Copier dans Railway Variables
```

### Erreur 500
- Voir logs dans Railway Dashboard
- Vérifier que toutes les variables sont définies

---

**Guide complet** : [GUIDE_DEPLOIEMENT_GRATUIT.md](GUIDE_DEPLOIEMENT_GRATUIT.md)
