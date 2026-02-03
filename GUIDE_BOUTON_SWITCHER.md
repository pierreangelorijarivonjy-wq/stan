# 🎯 Bouton "Changer de Rôle" - Guide Rapide

## ✅ Modification Effectuée

J'ai ajouté un bouton **"Changer de Rôle"** dans le dashboard standalone (`resources/views/dashboard.blade.php`).

---

## 📍 Emplacement du Bouton

Le bouton se trouve dans le **header en haut à droite**, entre la cloche de notifications et le bouton de compte:

```
┌──────────────────────────────────────────────────────────┐
│  ☰  Tableau de bord  [Recherche...]  🔔 [Changer de Rôle] 👤 │
└──────────────────────────────────────────────────────────┘
                                           ↑
                                    NOUVEAU BOUTON
```

---

## 🎨 Design du Bouton

- **Couleur**: Gradient indigo-purple (comme les autres boutons importants)
- **Icône**: Flèches de changement ⇄
- **Texte**: "Changer de Rôle" (visible sur desktop, caché sur mobile)
- **Effet**: Hover avec scale et ombre

---

## 🚀 Comment l'Utiliser

### Étape 1: Accéder au Dashboard
```bash
php artisan serve
```
Puis connectez-vous avec n'importe quel compte.

### Étape 2: Cliquer sur "Changer de Rôle"
Le bouton gradient violet en haut à droite.

### Étape 3: Choisir un Rôle
Vous arrivez sur la page `/switch-account` avec tous les comptes disponibles:
- 👨‍💼 Admin
- 💰 Comptable
- 📚 Scolarité
- 🎓 Étudiant 1-5

### Étape 4: Cliquer sur le Rôle Souhaité
Vous êtes automatiquement connecté et redirigé vers le dashboard correspondant.

---

## 🔄 Différence avec le Bouton Avatar

Maintenant vous avez **2 options** pour changer de compte:

### Option 1: Bouton "Changer de Rôle" (NOUVEAU)
- **Action**: Redirige vers `/switch-account`
- **Affichage**: Page complète avec tous les rôles
- **Avantage**: Vue d'ensemble, facile à utiliser

### Option 2: Bouton Avatar (Existant)
- **Action**: Ouvre une modale JavaScript
- **Affichage**: Popup avec liste de comptes
- **Avantage**: Rapide, pas de rechargement de page

---

## 📱 Responsive

- **Desktop**: Affiche "Changer de Rôle" avec icône
- **Mobile**: Affiche uniquement l'icône ⇄

---

## 🎯 Scénario d'Utilisation

**Vous êtes Étudiant et voulez tester le dashboard Comptable:**

1. Cliquez sur **"Changer de Rôle"** (bouton violet)
2. Page `/switch-account` s'ouvre
3. Cliquez sur la carte **"💰 Comptable"**
4. Vous êtes redirigé vers le dashboard Comptable
5. Pour revenir: Cliquez à nouveau sur **"Changer de Rôle"**

---

## ✅ Fichier Modifié

**Un seul fichier modifié:**
- `resources/views/dashboard.blade.php` (ligne 125-131)

**Aucun autre fichier touché** ✅

---

## 🎨 Code Ajouté

```html
<!-- BOUTON CHANGER DE RÔLE (vers page switcher) -->
<a href="{{ route('account.switcher') }}"
   class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 px-4 py-2.5 rounded-full shadow-md hover:shadow-lg hover:scale-105 transition-all duration-200 text-white font-semibold text-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
    </svg>
    <span class="hidden md:inline">Changer de Rôle</span>
</a>
```

---

## 🧪 Test Rapide

```bash
# 1. Démarrer le serveur
php artisan serve

# 2. Aller sur
http://localhost:8000/dashboard

# 3. Chercher le bouton violet "Changer de Rôle" en haut à droite

# 4. Cliquer dessus

# 5. Vous devriez voir la page avec tous les rôles
```

---

**Créé par**: Antigravity AI  
**Date**: 10 décembre 2025  
**Fichier modifié**: `resources/views/dashboard.blade.php` uniquement
