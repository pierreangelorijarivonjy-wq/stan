# 🔐 Mots de Passe des Rôles - EduPass-MG

## Comptes Staff (Personnel)

### 👨‍💼 Administrateur
- **Email**: `admin@edupass-mg.com`
- **Matricule**: `ADM-UF-2025-001`
- **Mot de passe**: `admin@edupass`
- **OTP/2FA**: Utiliser le matricule `ADM-UF-2025-001`

### 💰 Comptable
- **Email**: `comptable@edupass-mg.com`
- **Matricule**: `COM-UF-2025-001`
- **Mot de passe**: `comptable@edupass`
- **OTP/2FA**: Utiliser le matricule `COM-UF-2025-001`

### 🎓 Scolarité
- **Email**: `scolarite@edupass-mg.com`
- **Matricule**: `SCO-UF-2025-001`
- **Mot de passe**: `scolarite@edupass`
- **OTP/2FA**: Utiliser le matricule `SCO-UF-2025-001`

---

## Comptes Étudiants

### 👨‍🎓 Étudiant Test 1
- **Email**: `etudiant1@test.com`
- **Matricule**: `ETU-2025-001`
- **Mot de passe**: `password` (optionnel)
- **OTP/2FA**: Code envoyé par email (6 chiffres)

### 👩‍🎓 Étudiant Test 2
- **Email**: `etudiant2@test.com`
- **Matricule**: `ETU-2025-002`
- **Mot de passe**: `password` (optionnel)
- **OTP/2FA**: Code envoyé par email (6 chiffres)

---

## 📝 Notes Importantes

### Authentification Staff (Admin, Comptable, Scolarité)
1. **Login**: Email + Mot de passe **REQUIS**
2. **OTP**: Utiliser le **matricule** comme code OTP
3. **Account Switcher**: Mot de passe **REQUIS** pour changer de compte

### Authentification Étudiants
1. **Login**: Email ou Matricule (mot de passe **optionnel**)
2. **OTP**: Code à 6 chiffres envoyé par **email**
3. **Account Switcher**: Accès **direct** sans mot de passe

---

## 🔄 Changement de Compte (Account Switcher)

### Pour Staff → Staff
1. Cliquer sur le switcher
2. Sélectionner le rôle cible
3. **Entrer le mot de passe** du compte actuel
4. Entrer l'OTP (matricule)

### Pour Étudiant → Étudiant
1. Cliquer sur le switcher
2. Sélectionner l'étudiant
3. **Accès direct** sans mot de passe

---

## 🛠️ Commandes Utiles

### Créer un nouveau compte Staff
```bash
php artisan tinker

# Créer Admin
$user = User::create([
    'name' => 'Admin Principal',
    'email' => 'admin@edupass-mg.com',
    'password' => Hash::make('admin@edupass'),
    'matricule' => 'ADM-UF-2025-001',
]);
$user->assignRole('admin');

# Créer Comptable
$user = User::create([
    'name' => 'Comptable Principal',
    'email' => 'comptable@edupass-mg.com',
    'password' => Hash::make('comptable@edupass'),
    'matricule' => 'COM-UF-2025-001',
]);
$user->assignRole('comptable');

# Créer Scolarité
$user = User::create([
    'name' => 'Scolarité Principal',
    'email' => 'scolarite@edupass-mg.com',
    'password' => Hash::make('scolarite@edupass'),
    'matricule' => 'SCO-UF-2025-001',
]);
$user->assignRole('scolarite');
```

### Réinitialiser un mot de passe
```bash
php artisan tinker

$user = User::where('email', 'admin@edupass-mg.com')->first();
$user->password = Hash::make('nouveau_mot_de_passe');
$user->save();
```

### Vérifier les rôles
```bash
php artisan tinker

$user = User::where('email', 'admin@edupass-mg.com')->first();
$user->getRoleNames(); // Affiche les rôles
```

---

## 🔒 Sécurité

### Mots de passe par défaut
Les mots de passe ci-dessus sont pour **développement/test uniquement**.

### Pour production
1. Changer **tous** les mots de passe
2. Utiliser des mots de passe **forts** (12+ caractères)
3. Activer la **vérification email** pour tous les comptes
4. Configurer **2FA réel** (pas juste matricule)

### Recommandations
- **Admin**: `Admin@EduPass2025!Secure`
- **Comptable**: `Compta@EduPass2025!Secure`
- **Scolarité**: `Scola@EduPass2025!Secure`

---

## 📞 Support

Pour réinitialiser un mot de passe oublié:
- **Staff**: Utiliser la page `/staff-recovery` avec email + matricule
- **Étudiants**: Utiliser la page `/forgot-password` standard

---

**IMPORTANT**: Ces informations sont **confidentielles**. Ne pas partager publiquement.
