# 🚀 Guide de Démarrage Rapide - EduPass-MG

## ✅ Ce qui est prêt

### Backend API
- ✅ PostgreSQL configuré
- ✅ Laravel Sanctum installé
- ✅ API REST `/api/v1` complète
- ✅ Contrôleurs: Auth, Payment, Convocation, Dashboard

### Application Mobile
- ✅ Structure React Native complète
- ✅ Navigation configurée (Login → OTP → Dashboard)
- ✅ API Client avec URL dynamique
- ✅ Assets Play Store générés (icône + splash screen)

### Documentation
- ✅ `DEPLOYMENT_GUIDE.md` - Guide complet
- ✅ `PLAY_STORE_ASSETS.md` - Checklist assets
- ✅ `walkthrough.md` - Documentation transformation

---

## 🏃 Démarrage Rapide

### 1. Tester le Backend (API)

```bash
# Démarrer le serveur Laravel
cd c:\Users\STAN\EduPass-MG
php artisan serve --port=8000

# Tester l'API (dans un autre terminal)
curl http://localhost:8000/api/v1/auth/login -X POST -H "Content-Type: application/json" -d "{\"identifier\":\"test@example.com\"}"
```

**Note**: Si le port 8000 est occupé, essayez 8001, 8002, etc.

### 2. Tester l'Application Mobile

```bash
# Installer les dépendances (si pas encore fait)
cd c:\Users\STAN\edupass-mobile
npm install

# Démarrer Expo
npm start
```

**Ensuite**:
1. Scanner le QR code avec **Expo Go** sur votre téléphone Android
2. Ou appuyer sur `a` pour ouvrir dans l'émulateur Android

### 3. Vérifier les Assets

Les assets Play Store sont dans:
- `edupass-mobile/assets/icon.png` (icône app)
- `edupass-mobile/assets/adaptive-icon.png` (icône adaptive)
- `edupass-mobile/assets/splash.png` (splash screen)

---

## 📱 Déploiement Play Store

### Prérequis
1. **Compte Google Play Console** (25$ one-time)
2. **Compte Expo** (gratuit - créer sur expo.dev)
3. **EAS CLI** installé

### Étapes

#### 1. Installer EAS CLI
```bash
npm install -g eas-cli
```

#### 2. Login Expo
```bash
eas login
```

#### 3. Configurer le Projet
```bash
cd c:\Users\STAN\edupass-mobile
eas build:configure
```

#### 4. Build APK pour Test
```bash
eas build --platform android --profile preview
```
Cela prendra 10-20 minutes. Vous recevrez un lien pour télécharger l'APK.

#### 5. Build AAB pour Production
```bash
eas build --platform android --profile production
```

#### 6. Soumettre au Play Store

**Option A: Automatique**
```bash
eas submit --platform android
```

**Option B: Manuel**
1. Télécharger le fichier `.aab` depuis Expo
2. Aller sur [Google Play Console](https://play.google.com/console)
3. Créer une nouvelle application
4. Upload le fichier `.aab`
5. Remplir les informations (voir `PLAY_STORE_ASSETS.md`)
6. Soumettre pour review

---

## 🌐 Déploiement Web

### Option 1: VPS (Recommandé)

**Providers**: DigitalOcean, Linode, Vultr (5$/mois)

**Installation complète**: Voir `DEPLOYMENT_GUIDE.md`

**Résumé**:
```bash
# Sur le serveur
apt update && apt upgrade -y
apt install -y nginx postgresql php8.2-fpm git composer

# Clone et setup
cd /var/www
git clone https://github.com/your-repo/EduPass-MG.git edupass-mg
cd edupass-mg
composer install --no-dev
php artisan migrate --force

# Configuration Nginx (voir DEPLOYMENT_GUIDE.md)
# SSL avec Let's Encrypt
certbot --nginx -d your-domain.com
```

### Option 2: Hébergement Partagé

**Providers**: Hostinger, Namecheap (2-3$/mois)

**Limitations**: Pas de contrôle total, PostgreSQL peut ne pas être disponible

---

## 🔧 Configuration URL Dynamique (Mobile)

L'app mobile peut changer d'URL API sans recompilation:

```javascript
// Dans l'app, aller dans Settings
await AsyncStorage.setItem('API_URL', 'https://your-domain.com/api/v1');
```

**URLs suggérées**:
- Dev: `http://localhost:8000/api/v1`
- Staging: `http://server-ip/api/v1`
- Production: `https://api.edupass-mg.com/api/v1`

---

## 📋 Checklist Finale

### Backend
- [x] PostgreSQL configuré
- [x] API REST créée
- [x] Routes protégées
- [ ] Tests API passés
- [ ] Déployé sur serveur

### Mobile
- [x] Structure créée
- [x] Navigation configurée
- [x] Assets générés
- [ ] npm install terminé
- [ ] Testé avec Expo Go
- [ ] Build APK créé
- [ ] Soumis au Play Store

### Documentation
- [x] DEPLOYMENT_GUIDE.md
- [x] PLAY_STORE_ASSETS.md
- [x] walkthrough.md
- [x] QUICK_START.md (ce fichier)

---

## 🆘 Dépannage

### Port déjà utilisé
```bash
# Essayer un autre port
php artisan serve --port=8001
```

### npm install échoue
```bash
# Nettoyer et réinstaller
rm -rf node_modules package-lock.json
npm install
```

### Expo ne démarre pas
```bash
# Vérifier Node.js version (minimum 18)
node --version

# Réinstaller Expo CLI
npm install -g expo-cli
```

---

## 📞 Support

- **Email**: contact@edupass-mg.com
- **Documentation**: Voir `DEPLOYMENT_GUIDE.md`
- **Issues**: GitHub Issues (à créer)

---

**Prêt pour production !** 🎉
