# 📱 Guide Complet : Build APK et Déploiement Play Store

## 🎯 Objectifs

1. **APK pour téléchargement direct** (.apk) - Installer sur n'importe quel téléphone Android
2. **AAB pour Play Store** (.aab) - Publication officielle sur Google Play
3. **Site de téléchargement** - Page web pour télécharger l'APK

---

## 📦 Option 1: Build APK (Installation Directe)

### Avantages
- ✅ Téléchargement direct
- ✅ Installation immédiate
- ✅ Pas besoin de Play Store
- ✅ Gratuit

### Commandes

```bash
# 1. Installer EAS CLI (si pas déjà fait)
npm install -g eas-cli

# 2. Login Expo (créer compte gratuit sur expo.dev)
eas login

# 3. Configurer le projet
cd c:\Users\STAN\edupass-mobile
eas build:configure

# 4. Build APK
eas build --platform android --profile preview
```

**Durée**: 10-20 minutes

**Résultat**: Lien de téléchargement de l'APK (ex: `edupass-mg-v1.0.0.apk`)

### Installation sur Téléphone

1. Télécharger l'APK depuis le lien Expo
2. Sur votre téléphone Android:
   - Aller dans **Paramètres → Sécurité**
   - Activer **Sources inconnues** (ou **Installer des apps inconnues**)
3. Ouvrir le fichier APK téléchargé
4. Appuyer sur **Installer**
5. Lancer **EduPass-MG** !

---

## 🏪 Option 2: Déploiement Play Store (Publication Officielle)

### Avantages
- ✅ Distribution officielle
- ✅ Mises à jour automatiques
- ✅ Confiance des utilisateurs
- ✅ Statistiques de téléchargement

### Prérequis

1. **Compte Google Play Console** (25$ one-time fee)
   - Créer sur: https://play.google.com/console
   
2. **Compte Expo** (gratuit)
   - Créer sur: https://expo.dev

### Étapes Complètes

#### 1. Créer Compte Play Console

1. Aller sur https://play.google.com/console
2. Cliquer sur **Créer un compte développeur**
3. Payer 25$ (frais unique, valable à vie)
4. Remplir les informations (nom, adresse, etc.)

#### 2. Build AAB pour Play Store

```bash
# Login Expo
eas login

# Build AAB production
cd c:\Users\STAN\edupass-mobile
eas build --platform android --profile production
```

**Durée**: 15-25 minutes

**Résultat**: Fichier `.aab` (Android App Bundle)

#### 3. Créer Application sur Play Console

1. Aller sur https://play.google.com/console
2. Cliquer sur **Créer une application**
3. Remplir:
   - **Nom**: EduPass-MG
   - **Langue par défaut**: Français
   - **Type**: Application
   - **Gratuit/Payant**: Gratuit

#### 4. Remplir les Informations

**Fiche du Store**:
- **Titre**: EduPass-MG - Plateforme Éducative
- **Description courte**: Gestion des paiements scolaires et convocations d'examens
- **Description complète**: (voir ci-dessous)
- **Icône**: `edupass-mobile/assets/icon.png` (512x512px)
- **Screenshots**: Minimum 2 (à capturer depuis l'app)
- **Bannière**: Optionnel

**Description Complète**:
```
EduPass-MG est la plateforme éducative officielle pour la gestion des paiements scolaires et des convocations d'examens à Madagascar.

Fonctionnalités principales :

✅ PAIEMENTS SÉCURISÉS
• Paiement via MVola, Orange Money, Airtel Money
• Consultation de l'historique des paiements
• Téléchargement des reçus PDF

✅ CONVOCATIONS D'EXAMENS
• Consultation des convocations en temps réel
• Téléchargement des convocations PDF
• Vérification par QR Code

✅ TABLEAU DE BORD
• Vue d'ensemble de votre situation académique
• Statistiques de paiements
• Notifications importantes

✅ SÉCURITÉ MAXIMALE
• Authentification OTP/2FA par email
• Données cryptées
• Conformité aux normes de sécurité

Pour qui ?
• Étudiants : Gérez vos paiements et consultez vos convocations
• Personnel administratif : Supervision et gestion

Support : contact@edupass-mg.com
```

**Catégorie**: Éducation

**Politique de confidentialité**: (URL à créer - voir section suivante)

#### 5. Upload AAB

1. Dans Play Console, aller dans **Production**
2. Cliquer sur **Créer une version**
3. Upload le fichier `.aab` téléchargé depuis Expo
4. Remplir les notes de version:
   ```
   Version 1.0.0 - Première version
   - Authentification OTP/2FA
   - Paiements mobiles (MVola, Orange, Airtel)
   - Convocations PDF avec QR Code
   - Dashboard personnalisé
   ```

#### 6. Soumettre pour Review

1. Vérifier toutes les informations
2. Cliquer sur **Envoyer pour examen**
3. Attendre 1-3 jours pour validation Google

---

## 🌐 Option 3: Héberger APK sur Site Web

### Créer Page de Téléchargement

```html
<!-- download.html -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Télécharger EduPass-MG</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }
        .download-btn {
            background: #6366F1;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 18px;
            display: inline-block;
            margin: 20px 0;
        }
        .version {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>📱 EduPass-MG</h1>
    <p>Plateforme Éducative Madagascar</p>
    
    <a href="edupass-mg-v1.0.0.apk" class="download-btn">
        📥 Télécharger APK
    </a>
    
    <p class="version">Version 1.0.0 | 15 MB</p>
    
    <h3>Installation</h3>
    <ol style="text-align: left;">
        <li>Télécharger le fichier APK</li>
        <li>Activer "Sources inconnues" dans Paramètres</li>
        <li>Ouvrir le fichier APK</li>
        <li>Installer l'application</li>
    </ol>
</body>
</html>
```

### Hébergement Options

**Option A: GitHub Releases** (Gratuit)
1. Créer repo GitHub
2. Aller dans **Releases**
3. Upload APK
4. Partager le lien

**Option B: Google Drive** (Gratuit)
1. Upload APK sur Drive
2. Partager avec "Tout le monde"
3. Copier le lien de téléchargement

**Option C: Serveur Web** (VPS)
1. Upload APK sur serveur
2. Créer page HTML
3. Partager l'URL

---

## 📋 Checklist Complète

### Préparation
- [x] Code mobile complet
- [x] Assets générés (icône, splash)
- [x] Configuration EAS (app.json, eas.json)

### Build APK
- [ ] EAS CLI installé
- [ ] Compte Expo créé
- [ ] Build APK lancé
- [ ] APK téléchargé
- [ ] APK testé sur téléphone

### Play Store
- [ ] Compte Play Console créé (25$)
- [ ] Build AAB lancé
- [ ] Application créée sur Play Console
- [ ] Informations remplies
- [ ] Screenshots ajoutés
- [ ] Politique de confidentialité créée
- [ ] AAB uploadé
- [ ] Soumis pour review

### Distribution
- [ ] APK hébergé (GitHub/Drive/Serveur)
- [ ] Page de téléchargement créée
- [ ] Lien partagé

---

## 🚀 Commandes Rapides

```bash
# Build APK (téléchargement direct)
cd c:\Users\STAN\edupass-mobile
eas login
eas build --platform android --profile preview

# Build AAB (Play Store)
eas build --platform android --profile production

# Soumettre au Play Store (automatique)
eas submit --platform android
```

---

## 📞 Support

**Problèmes de build ?**
- Vérifier connexion internet
- Vérifier compte Expo actif
- Consulter logs: https://expo.dev

**Questions Play Store ?**
- Documentation: https://support.google.com/googleplay/android-developer
- Support: https://support.google.com/googleplay/android-developer/contact

---

**Prêt à builder !** 🎉
