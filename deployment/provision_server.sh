#!/bin/bash

# 🛠️ EduPass-MG Server Provisioning Script
# Target System: Ubuntu 22.04 / 24.04 LTS
# Run as ROOT

set -e

# Configuration
DOMAIN="edupass.madagascar.mg"
DB_NAME="edupass"
DB_USER="edupass_user"
# Generate a random password for DB
DB_PASS=$(openssl rand -base64 12)

echo "--- 🚀 Starting Server Provisioning for $DOMAIN ---"

# 1. Update System
echo "--- 🔄 Updating System ---"
apt update && apt upgrade -y

# 2. Install Dependencies
echo "--- 📦 Installing Dependencies (Nginx, PHP 8.2, PgSQL, Redis) ---"
apt install -y nginx zip unzip git curl acl
apt install -y php8.2-fpm php8.2-cli php8.2-common php8.2-pgsql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath php8.2-intl php8.2-redis
apt install -y postgresql postgresql-contrib redis-server supervisor

# 3. Configure Database
echo "--- 🗄️ Configuring PostgreSQL ---"
# Check if user exists, if not create
sudo -u postgres psql -tAc "SELECT 1 FROM pg_roles WHERE rolname='$DB_USER'" | grep -q 1 || sudo -u postgres psql -c "CREATE USER $DB_USER WITH PASSWORD '$DB_PASS';"
# Check if db exists, if not create
sudo -u postgres psql -tAc "SELECT 1 FROM pg_database WHERE datname='$DB_NAME'" | grep -q 1 || sudo -u postgres psql -c "CREATE DATABASE $DB_NAME OWNER $DB_USER;"

echo "✅ Database configured."
echo "📝 DB Credentials saved to /root/db_credentials.txt"
echo "DB_DATABASE=$DB_NAME" > /root/db_credentials.txt
echo "DB_USERNAME=$DB_USER" >> /root/db_credentials.txt
echo "DB_PASSWORD=$DB_PASS" >> /root/db_credentials.txt

# 4. Install Composer
echo "--- 🎼 Installing Composer ---"
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

# 5. Install Node.js & NPM
echo "--- 🟢 Installing Node.js ---"
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt install -y nodejs
fi

# 6. Configure Nginx
echo "--- 🌐 Configuring Nginx ---"
cat > /etc/nginx/sites-available/$DOMAIN <<EOF
server {
    listen 80;
    server_name $DOMAIN;
    root /var/www/edupass/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    # Proxy for Reverb (WebSockets)
    location /app {
        proxy_http_version 1.1;
        proxy_set_header Host \$http_host;
        proxy_set_header Scheme \$scheme;
        proxy_set_header SERVER_PORT \$server_port;
        proxy_set_header REMOTE_ADDR \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header Upgrade \$http_upgrade;
        proxy_set_header Connection "Upgrade";

        proxy_pass http://127.0.0.1:8080;
    }
}
EOF

ln -sf /etc/nginx/sites-available/$DOMAIN /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx

# 7. Setup Project Directory
echo "--- 📂 Setting up Project Directory ---"
mkdir -p /var/www/edupass
chown -R www-data:www-data /var/www/edupass
chmod -R 775 /var/www/edupass

# 8. Install Certbot
echo "--- 🔒 Installing Certbot (SSL) ---"
apt install -y certbot python3-certbot-nginx

echo "--- ✅ Provisioning Complete! ---"
echo "Next steps:"
echo "1. Clone your repository into /var/www/edupass"
echo "2. Copy .env.example to .env and update it with DB credentials from /root/db_credentials.txt"
echo "3. Run 'composer install' and 'npm install && npm run build'"
echo "4. Run 'php artisan migrate'"
echo "5. Run 'certbot --nginx -d $DOMAIN' to enable HTTPS"
