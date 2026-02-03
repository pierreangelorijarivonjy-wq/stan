@echo off
echo ==========================================
echo   EduPass-MG - Installation VPS Automatisée
echo ==========================================
echo.
echo Ce script va :
echo 1. Copier le script d'installation sur votre VPS
echo 2. Lancer l'installation automatique (Nginx, PHP, Postgres, etc.)
echo.

set /p VPS_IP="Entrez l'adresse IP du VPS : "
set /p VPS_USER="Entrez l'utilisateur (defaut: root) : "
if "%VPS_USER%"=="" set VPS_USER=root

echo.
echo --- Etape 1 : Copie des scripts ---
scp -o StrictHostKeyChecking=no deployment/provision_server.sh deployment/deploy_prod.sh deployment/edupass-worker.conf %VPS_USER%@%VPS_IP%:/root/

echo.
echo --- Etape 2 : Lancement de l'installation ---
echo Une fois connecte, l'installation va demarrer automatiquement.
echo.
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "chmod +x /root/provision_server.sh && /root/provision_server.sh"

echo.
echo Installation terminee.
pause
