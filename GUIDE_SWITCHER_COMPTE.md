# 🔄 Guide: Changer de Rôle avec le Switcher de Compte

## 🎯 Comment accéder aux différents dashboards

Votre application a déjà un **switcher de compte** intégré dans le dashboard ! Voici comment l'utiliser pour voir les dashboards de chaque rôle.

---

## 📍 Localisation du Switcher

Le bouton de changement de compte se trouve en **haut à droite** du dashboard :
- 👤 **Avatar** avec nom d'utilisateur
- 🔽 **Flèche** pour ouvrir le menu

---

## 🚀 Méthode 1: Utiliser le Switcher de Compte (Recommandé)

### Étape 1: Ouvrir le Switcher
1. Connectez-vous avec n'importe quel compte
2. Cliquez sur votre **avatar en haut à droite**
3. Une modale s'ouvre avec la liste des comptes

### Étape 2: Ajouter les comptes de test

Pour chaque rôle, ajoutez le compte:

**1. Cliquez sur "Ajouter un compte"**

**2. Ajoutez Admin:**
- Nom: `Admin EduPass`
- Email: `admin@edupass.mg`
- Cliquez "Ajouter"

**3. Ajoutez Comptable:**
- Nom: `Comptable EduPass`
- Email: `comptable@edupass.mg`
- Cliquez "Ajouter"

**4. Ajoutez Scolarité:**
- Nom: `Scolarité EduPass`
- Email: `scolarite@edupass.mg`
- Cliquez "Ajouter"

**5. Ajoutez Étudiant:**
- Nom: `Étudiant Test`
- Email: `etudiant1@edupass.mg`
- Cliquez "Ajouter"

### Étape 3: Changer de compte

1. Cliquez sur votre avatar
2. Dans la liste, cliquez sur **"Utiliser"** à côté du compte souhaité
3. Le dashboard se recharge avec le nouveau rôle

---

## 🎭 Méthode 2: Se Déconnecter et Reconnecter

Si le switcher ne fonctionne pas, utilisez la méthode classique:

### 1. Se déconnecter
- Cliquez sur votre avatar
- Cliquez sur "Déconnexion"

### 2. Se reconnecter avec un autre compte

**Comptes de test disponibles:**

| Rôle | Email | Mot de passe | Dashboard |
|------|-------|--------------|-----------|
| 👨‍🎓 **Étudiant** | `etudiant1@edupass.mg` | `password` | `dashboard.student` |
| 👔 **Admin** | `admin@edupass.mg` | `password` | `dashboard.admin` |
| 💰 **Comptable** | `comptable@edupass.mg` | `password` | `dashboard.comptable` |
| 📚 **Scolarité** | `scolarite@edupass.mg` | `password` | `dashboard.scolarite` |

---

## 📊 Ce que vous verrez pour chaque rôle

### 👨‍🎓 Dashboard Étudiant (`dashboard.student`)
- ✅ Mes paiements
- ✅ Mes convocations
- ✅ Historique
- ✅ Prochaines échéances

### 👔 Dashboard Admin (`dashboard.admin`)
- ✅ Vue d'ensemble complète
- ✅ Statistiques globales
- ✅ Activité récente
- ✅ Tous les utilisateurs

### 💰 Dashboard Comptable (`dashboard.comptable`)
- ✅ Revenu total
- ✅ Paiements en attente
- ✅ Taux d'appariement bancaire
- ✅ Revenus par type
- ✅ Boutons: Rapprochement, Rapports, Exceptions

### 📚 Dashboard Scolarité (`dashboard.scolarite`)
- ✅ Total étudiants
- ✅ Sessions d'examen
- ✅ Convocations générées/envoyées
- ✅ Étudiants par statut

---

## 🔧 Configuration du Switcher

Le switcher de compte fonctionne via:

### 1. Route de changement de compte

Vérifiez que cette route existe dans `routes/web.php`:

```php
Route::post('/switch-account', [AccountSwitcherController::class, 'switch'])
    ->name('account.switch');
```

### 2. Contrôleur AccountSwitcherController

Le fichier devrait être dans `app/Http/Controllers/AccountSwitcherController.php`

### 3. Stockage local

Les comptes sont stockés dans le `localStorage` du navigateur sous la clé `shulepay_accounts`

---

## 🐛 Dépannage

### Le switcher ne s'affiche pas

**Vérifiez:**
1. Vous êtes sur le bon dashboard (pas `dashboard.blade.php` mais `dashboard.student`, etc.)
2. Le JavaScript est chargé
3. Pas d'erreurs dans la console (F12)

**Solution:** Utilisez la Méthode 2 (déconnexion/reconnexion)

### Le bouton "Utiliser" ne fonctionne pas

**Problème:** La route `/switch-account` n'existe pas ou le contrôleur manque

**Solution temporaire:** Utilisez la Méthode 2

**Solution permanente:** Je peux créer le `AccountSwitcherController` si nécessaire

### Les dashboards ne s'affichent pas correctement

**Vérifiez:**
1. Les vues existent:
   - `resources/views/dashboard/student.blade.php` ✅
   - `resources/views/dashboard/admin.blade.php` ✅
   - `resources/views/dashboard/comptable.blade.php` ✅
   - `resources/views/dashboard/scolarite.blade.php` ✅

2. Le `DashboardController` retourne les bonnes vues ✅ (corrigé)

---

## ✅ Checklist de Test

Pour tester tous les dashboards:

- [ ] **Étudiant**: Se connecter avec `etudiant1@edupass.mg`
  - [ ] Voir mes paiements
  - [ ] Voir mes convocations
  
- [ ] **Admin**: Se connecter avec `admin@edupass.mg`
  - [ ] Voir statistiques globales
  - [ ] Voir activité récente

- [ ] **Comptable**: Se connecter avec `comptable@edupass.mg`
  - [ ] Voir revenu total
  - [ ] Accéder au rapprochement bancaire
  - [ ] Voir exceptions

- [ ] **Scolarité**: Se connecter avec `scolarite@edupass.mg`
  - [ ] Voir total étudiants
  - [ ] Voir sessions d'examen
  - [ ] Voir convocations

---

## 🎯 Résumé Rapide

**Pour changer de rôle:**

1. **Méthode Rapide** (Switcher):
   ```
   Avatar → Ajouter compte → Utiliser
   ```

2. **Méthode Classique** (Déconnexion):
   ```
   Avatar → Déconnexion → Login avec autre compte
   ```

**Comptes de test:**
- `admin@edupass.mg` / `password`
- `comptable@edupass.mg` / `password`
- `scolarite@edupass.mg` / `password`
- `etudiant1@edupass.mg` / `password`

---

**Créé par**: Antigravity AI  
**Date**: 10 décembre 2025  
**Fichiers modifiés**: `DashboardController.php`
