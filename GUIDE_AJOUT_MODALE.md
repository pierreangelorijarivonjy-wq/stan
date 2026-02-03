# 🔒 Guide: Sécurité par Mot de Passe pour Changement de Rôle

## ✅ Ce qui a été implémenté

### 1. Validation Backend
- ✅ `AccountSwitcherController` modifié pour exiger un mot de passe
- ✅ Vérification du mot de passe avec `Hash::check()`
- ✅ Messages d'erreur si mot de passe incorrect

### 2. Modale Frontend (À AJOUTER MANUELLEMENT)
Le fichier `account-switcher.blade.php` doit être modifié pour inclure la modale.

---

## 🛠️ Solution Rapide

### Étape 1: Ouvrir le fichier
```
resources/views/account-switcher.blade.php
```

### Étape 2: Remplacer la fin du fichier

**Cherchez ces lignes (vers la ligne 166):**
```html
            <div class="mt-8 text-center text-sm text-gray-600">
                <p>💡 <strong>Astuce :</strong> Utilisez cette page pour tester rapidement tous les dashboards</p>
                <p class="mt-2">Ajoutez cette page aux favoris : <code
                        class="bg-gray-100 px-2 py-1 rounded">/switch-account</code></p>
            </div>
        </div>
    </div>
</body>

</html>
```

**Remplacez par:**
```html
            <div class="mt-8 text-center text-sm text-gray-600">
                <p>🔒 <strong>Sécurité :</strong> Un mot de passe est requis pour changer de rôle</p>
                <p class="mt-2">💡 <strong>Mot de passe par défaut :</strong> <code class="bg-gray-100 px-2 py-1 rounded font-mono">password</code></p>
            </div>
        </div>
    </div>

    <!-- Modale de Mot de Passe -->
    <div id="passwordModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[9999] hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-20 h-20 mx-auto bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">🔒 Confirmation Requise</h3>
                <p class="text-gray-600 mt-2">Entrez le mot de passe pour <span id="targetRole" class="font-semibold text-indigo-600"></span></p>
            </div>
            <form id="passwordForm" method="POST">
                @csrf
                <input type="hidden" name="email" id="targetEmail">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                    <input type="password" name="password" id="passwordInput" required
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 bg-gray-50 text-gray-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 focus:outline-none text-lg"
                        placeholder="••••••••">
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="closePasswordModal()"
                        class="flex-1 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Annuler
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-bold transition shadow-lg">
                        ✓ Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentForm = null;

        function openPasswordModal(email, roleName) {
            document.getElementById('targetEmail').value = email;
            document.getElementById('targetRole').textContent = roleName;
            document.getElementById('passwordInput').value = '';
            document.getElementById('passwordModal').classList.remove('hidden');
            setTimeout(() => document.getElementById('passwordInput').focus(), 100);
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
            currentForm = null;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[action="{{ route(\'account.switch\') }}"]');
            
            const roleNames = {
                'admin@edupass.mg': 'Administrateur',
                'comptable@edupass.mg': 'Comptable',
                'scolarite@edupass.mg': 'Scolarité',
                'etudiant1@edupass.mg': 'Étudiant 1',
                'etudiant2@edupass.mg': 'Étudiant 2',
                'etudiant3@edupass.mg': 'Étudiant 3',
                'etudiant4@edupass.mg': 'Étudiant 4',
                'etudiant5@edupass.mg': 'Étudiant 5'
            };
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const email = form.querySelector('input[name="email"]').value;
                    currentForm = form;
                    openPasswordModal(email, roleNames[email] || email);
                });
            });

            document.getElementById('passwordForm').addEventListener('submit', function(e) {
                e.preventDefault();
                if (currentForm) {
                    const password = document.getElementById('passwordInput').value;
                    const passwordInput = document.createElement('input');
                    passwordInput.type = 'hidden';
                    passwordInput.name = 'password';
                    passwordInput.value = password;
                    currentForm.appendChild(passwordInput);
                    currentForm.submit();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closePasswordModal();
            });
        });
    </script>
</body>

</html>
```

---

## 🧪 Test

1. Rafraîchissez la page `/switch-account`
2. Cliquez sur n'importe quel rôle
3. Une modale devrait apparaître demandant le mot de passe
4. Entrez `password`
5. Vous serez redirigé vers le dashboard

---

## 📝 Mots de Passe par Défaut

Tous les comptes de test utilisent le même mot de passe:
```
password
```

---

**Fichier à modifier**: `resources/views/account-switcher.blade.php`  
**Lignes à remplacer**: 166-175
