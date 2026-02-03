# 🎯 Comment Accéder au Dashboard Standalone

## 📍 URL d'Accès

J'ai créé une route spéciale pour accéder au dashboard standalone (`dashboard.blade.php`):

```
http://localhost:8000/demo-dashboard
```

---

## 🚀 Étapes pour y Accéder

### 1. Démarrer le serveur
```bash
php artisan serve
```

### 2. Se connecter
Allez sur `http://localhost:8000/login` et connectez-vous avec n'importe quel compte:
- `admin@edupass.mg` / `password`
- `etudiant1@edupass.mg` / `password`
- etc.

### 3. Accéder au dashboard standalone
Une fois connecté, allez sur:
```
http://localhost:8000/demo-dashboard
```

---

## 🔄 Différence entre les Dashboards

Vous avez maintenant **2 types de dashboards**:

### 1. Dashboard Standalone (`/demo-dashboard`)
- **Fichier**: `resources/views/dashboard.blade.php`
- **URL**: `/demo-dashboard`
- **Design**: Dashboard moderne avec sidebar, multi-langue, mode sombre
- **Bouton**: ✅ **"Changer de Rôle"** présent en haut à droite
- **Usage**: Démo, test, interface moderne

### 2. Dashboards par Rôle (`/dashboard`)
- **Fichiers**: 
  - `resources/views/dashboard/student.blade.php`
  - `resources/views/dashboard/admin.blade.php`
  - `resources/views/dashboard/comptable.blade.php`
  - `resources/views/dashboard/scolarite.blade.php`
- **URL**: `/dashboard` (redirige selon le rôle)
- **Design**: Dashboards spécifiques avec données réelles
- **Bouton**: ✅ **"Changer de Rôle"** ajouté en haut
- **Usage**: Production, dashboards fonctionnels

---

## 🎯 Scénario d'Utilisation

### Pour tester le Dashboard Standalone:

```bash
# 1. Démarrer serveur
php artisan serve

# 2. Ouvrir navigateur
http://localhost:8000/login

# 3. Se connecter
Email: etudiant1@edupass.mg
Password: password

# 4. Aller sur le dashboard standalone
http://localhost:8000/demo-dashboard

# 5. Cliquer sur "Changer de Rôle" (bouton violet en haut à droite)

# 6. Choisir un autre rôle

# 7. Vous serez redirigé vers le dashboard de ce rôle
```

---

## 🔗 Navigation entre les Dashboards

### Depuis le Dashboard Standalone → Dashboard par Rôle
1. Cliquez sur **"Changer de Rôle"** (bouton violet)
2. Sélectionnez un rôle
3. Vous arrivez sur `/dashboard` (dashboard spécifique au rôle)

### Depuis Dashboard par Rôle → Dashboard Standalone
1. Tapez manuellement dans l'URL: `/demo-dashboard`
2. Ou ajoutez un lien dans le menu

---

## 📝 Route Créée

J'ai ajouté cette route dans `routes/web.php`:

```php
// Dashboard moderne standalone (pour démo/test)
Route::get('/demo-dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('demo.dashboard');
```

---

## 🎨 Fonctionnalités du Dashboard Standalone

- ✅ **Sidebar** avec menu de navigation
- ✅ **Multi-langue** (FR, MG, EN)
- ✅ **Mode sombre** (toggle dans paramètres)
- ✅ **Switcher de compte** (modale JavaScript)
- ✅ **Bouton "Changer de Rôle"** (vers `/switch-account`) ← NOUVEAU
- ✅ **Responsive** (mobile-friendly)
- ✅ **Recherche** dans le header
- ✅ **Notifications** (badge)

---

## 🔧 Personnalisation

### Pour rendre le Dashboard Standalone comme dashboard par défaut:

Modifiez `DashboardController.php`:

```php
public function index()
{
    // Utiliser le dashboard standalone pour tous
    return view('dashboard');
}
```

### Pour ajouter un lien dans le menu:

Dans `resources/views/layouts/navigation.blade.php`:

```blade
<a href="{{ route('demo.dashboard') }}">
    Dashboard Moderne
</a>
```

---

## 📊 Récapitulatif des URLs

| URL | Dashboard | Fichier |
|-----|-----------|---------|
| `/dashboard` | Par rôle (dynamique) | `dashboard/{role}.blade.php` |
| `/demo-dashboard` | Standalone moderne | `dashboard.blade.php` |
| `/switch-account` | Switcher de compte | `account-switcher.blade.php` |

---

## ✅ Checklist de Test

- [ ] Accéder à `/demo-dashboard`
- [ ] Voir le dashboard moderne s'afficher
- [ ] Cliquer sur "Changer de Rôle" (bouton violet)
- [ ] Sélectionner un rôle
- [ ] Vérifier la redirection vers `/dashboard`
- [ ] Retourner sur `/demo-dashboard`
- [ ] Tester le mode sombre
- [ ] Tester le changement de langue

---

**Créé par**: Antigravity AI  
**Date**: 10 décembre 2025  
**Route ajoutée**: `/demo-dashboard`  
**Fichier modifié**: `routes/web.php`
