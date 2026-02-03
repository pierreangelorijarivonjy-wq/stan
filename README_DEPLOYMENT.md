# 🚀 EduPass-MG - Déploiement & Infrastructure

## 📚 Documentation

### Guides Principaux
- **[Guide Complet CI/CD](GUIDE_DEPLOIEMENT_CICD.md)** - Documentation exhaustive (200+ lignes)
- **[Déploiement Rapide](DEPLOIEMENT_RAPIDE.md)** - Quick start guide avec checklist

### Fichiers de Configuration
- **[Dockerfile](docker/Dockerfile)** - Image Laravel optimisée
- **[docker-compose.prod.yml](docker-compose.prod.yml)** - Orchestration production
- **[GitHub Actions](.github/workflows/ci-cd.yml)** - Pipeline CI/CD
- **[Makefile](Makefile)** - Commandes simplifiées

---

## ⚡ Quick Start

### 1. Prérequis
```bash
# Sur votre machine locale
- Git
- Docker & Docker Compose
- Compte GitHub
- Compte Docker Hub

# Sur les serveurs (staging + production)
- Ubuntu 20.04+
- Docker & Docker Compose
- Utilisateur 'deploy' avec accès Docker
```

### 2. Configuration Initiale

```bash
# Cloner le repo
git clone https://github.com/YOUR_USERNAME/EduPass-MG.git
cd EduPass-MG

# Configurer les secrets GitHub
# Voir: DEPLOIEMENT_RAPIDE.md section "Configurer GitHub Secrets"

# Préparer les serveurs
# Voir: GUIDE_DEPLOIEMENT_CICD.md section "Étape 7"
```

### 3. Premier Déploiement

```bash
# Pousser sur main pour déclencher le pipeline
git push origin main

# Suivre l'exécution sur GitHub Actions
# https://github.com/YOUR_USERNAME/EduPass-MG/actions
```

---

## 🛠️ Commandes Utiles (Makefile)

```bash
# Développement local
make build              # Build l'image Docker
make up                 # Démarrer les conteneurs
make down               # Arrêter les conteneurs
make logs               # Voir les logs
make shell              # Ouvrir un shell dans le conteneur

# Base de données
make migrate            # Exécuter les migrations
make seed               # Exécuter les seeders
make backup-db          # Sauvegarder la DB
make restore-db FILE=backup.sql  # Restaurer la DB

# Cache & Optimisation
make cache-clear        # Vider tous les caches
make cache-optimize     # Optimiser pour production
make queue-restart      # Redémarrer les workers

# Tests
make test               # Exécuter les tests
make test-coverage      # Tests avec couverture

# Déploiement
make deploy-staging     # Déployer en staging
make deploy-prod        # Déployer en production
make health             # Vérifier la santé de l'app

# Maintenance
make clean              # Nettoyer les ressources Docker
make status             # Voir l'état des conteneurs
```

---

## 📊 Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      GitHub Actions                          │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │   Test   │→ │  Build   │→ │ Staging  │→ │   Prod   │   │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│                     Docker Hub                               │
│              karibo01/edupass-mg:latest                      │
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│                Production Server (VPS)                       │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Docker Compose                                       │  │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐          │  │
│  │  │   App    │  │PostgreSQL│  │  Redis   │          │  │
│  │  │  Nginx   │  │    15    │  │    7     │          │  │
│  │  │ PHP-FPM  │  │          │  │          │          │  │
│  │  │ Workers  │  │          │  │          │          │  │
│  │  └──────────┘  └──────────┘  └──────────┘          │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔐 Sécurité

### Secrets GitHub Requis
- `DOCKER_USERNAME` - Nom d'utilisateur Docker Hub
- `DOCKER_TOKEN` - Token d'accès Docker Hub
- `STAGING_HOST` - IP serveur staging
- `STAGING_USER` - Utilisateur SSH staging
- `STAGING_SSH_KEY` - Clé privée SSH staging
- `PRODUCTION_HOST` - IP serveur production
- `PRODUCTION_USER` - Utilisateur SSH production
- `PRODUCTION_SSH_KEY` - Clé privée SSH production

### Variables d'Environnement
Voir `deployment/.env.production.example` pour la liste complète.

---

## 📈 Monitoring & Santé

### Health Check
```bash
# Local
curl http://localhost:8080/health

# Production
curl https://edupass.mg/health
```

### Logs
```bash
# Application
make logs-app

# Base de données
make logs-db

# Redis
make logs-redis

# Tous les services
make logs
```

---

## 🔄 Workflow de Déploiement

### Branches
- `main` → Production (déploiement automatique après approbation)
- `develop` → Staging (déploiement automatique)
- `feature/*` → Tests uniquement

### Process
1. Développer sur branche `feature/*`
2. Créer PR vers `develop`
3. Tests automatiques s'exécutent
4. Merge → Déploiement automatique en staging
5. Validation en staging
6. Merge `develop` → `main`
7. Déploiement en production (avec approbation manuelle)

---

## 🆘 Dépannage

### Le build échoue
```bash
# Vérifier les logs GitHub Actions
# Vérifier composer.json et package.json
# Vérifier que .env.example existe
```

### Le déploiement échoue
```bash
# Vérifier la connexion SSH
ssh deploy@SERVER

# Vérifier Docker
docker --version
docker-compose --version

# Vérifier les permissions
ls -la /home/deploy/edupass-mg
```

### L'application ne démarre pas
```bash
# Voir les logs
make logs

# Vérifier le health check
make health

# Vérifier les variables d'environnement
docker-compose -f docker-compose.prod.yml exec app env | grep APP_
```

---

## 📞 Support

- **Documentation** : Voir les guides dans ce dossier
- **Issues** : GitHub Issues
- **Logs** : `make logs` ou GitHub Actions

---

## 📝 Changelog

### v1.0.0 (2026-01-16)
- ✅ Infrastructure CI/CD complète
- ✅ Docker multi-service (App, PostgreSQL, Redis)
- ✅ GitHub Actions (Test, Build, Deploy)
- ✅ Makefile avec commandes simplifiées
- ✅ Scripts de backup/restore automatiques
- ✅ Health check endpoint
- ✅ Documentation complète

---

**Prêt à déployer !** 🎉
