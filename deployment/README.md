# 🚀 Kit de Déploiement Automatisé - EduPass MG

Ce dossier contient tous les scripts nécessaires pour installer et déployer l'application sur un VPS vierge.

## 📂 Contenu

- **`provision_server.sh`** : Script d'initialisation du serveur. Installe Nginx, PHP, PostgreSQL, Redis, etc.
- **`deploy_prod.sh`** : Script de déploiement du code. À utiliser pour les mises à jour futures.
- **`edupass-worker.conf`** : Configuration Supervisor pour les tâches de fond et les WebSockets.

## 🛠️ Comment utiliser (Si vous le faites vous-même)

1.  **Copier les scripts sur le VPS** :
    ```bash
    scp deployment/* root@votre-ip:/root/
    ```
2.  **Lancer l'installation** :
    ```bash
    ssh root@votre-ip
    chmod +x provision_server.sh
    ./provision_server.sh
    ```
3.  **Finaliser** :
    Le script vous donnera les instructions finales (clonage du repo, .env, etc.).

---

## 🤖 Si vous voulez que JE le fasse

J'ai besoin des informations de connexion au VPS pour exécuter ces scripts pour vous :
- **IP du serveur**
- **Utilisateur** (root)
- **Mot de passe** (ou assurez-vous que je peux me connecter via SSH)
