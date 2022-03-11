#!/bin/bash
LOG_FILE="/vagrant/logs/install_roombooker.log"
WWW_ROOT="/var/www/html/"
DOCUMENT_ROOT="${WWW_ROOT}roombooker/public"
GITLAB_ACCESS_TOKEN="wr8BfPRCtLAK22pmXkHX"

echo "Install RoomBooker WebApp ..."
echo "[1] Dependencies first : Git, Composer"
# On veut avoir git pour cloner le dépôt sur la branche production ...
apt-get -y install git

# On veut aussi Composer ...
php8.1 -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" >> $LOG_FILE 2>&1
php8.1 -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" >> $LOG_FILE 2>&1
php8.1 composer-setup.php >> $LOG_FILE 2>&1
php8.1 -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer

echo "[2] Installation de l'application"
# Et puis on installe le projet dans /var/www/html/roombooker ...
cd /var/www/html/
git clone --branch=production https://halopeal:$GITLAB_ACCESS_TOKEN@172.24.0.69/e4e-fise/s7-projet-infra-logiciel/2021-2022/e11.git roombooker -c http.sslVerify=false >> $LOG_FILE 2>&1
cd /var/www/html/roombooker
# Installe l'application avec ses dépendances internes.
composer install --no-dev --optimize-autoloader >> $LOG_FILE 2>&1
composer dump-env prod -n>> $LOG_FILE 2>&1
chown www-data:www-data -R /var/www/html/roombooker
APP_ENV=prod APP_DEBUG=0 php8.1 bin/console cache:clear >> $LOG_FILE 2>&1
echo "[3] Configuration Apache pour l'appli RoomBooker ..."
a2enmod rewrite
cat << EOF >> /etc/apache2/sites-available/roombooker.conf
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot $DOCUMENT_ROOT
    Alias /myadmin /var/www/html/myadmin
    Alias /moodle /var/www/html/moodle
    
    <Directory "$DOCUMENT_ROOT">
        AllowOverride None
        Order Allow,Deny
        Allow from All
        # For a symfony application to work properly, you MUST store this .htaccess in
        # the same directory as your front controller, index.php, in a standard symfony
        # web application is under the "public" project subdirectory.

        # Use the front controller as index file.
        DirectoryIndex index.php

        # Uncomment the following line if you install assets as symlinks or if you 
        # experience problems related to symlinks when compiling LESS/Sass/CoffeScript.
        # Options +FollowSymlinks

        # Disabling MultiViews prevents unwanted negotiation, e.g. "/index" should not resolve
        # to the front controller "/index.php" but be rewritten to "/index.php/index".
        <IfModule mod_negotiation.c>
            Options -MultiViews
        </IfModule>

        <IfModule mod_rewrite.c>
            RewriteEngine On

            RewriteCond %{REQUEST_URI}::$0 ^(/.+)/(.*)::\2$
            RewriteRule .* - [E=BASE:%1]

            # Sets the HTTP_AUTHORIZATION header removed by Apache
            RewriteCond %{HTTP:Authorization} .+
            RewriteRule ^ - [E=HTTP_AUTHORIZATION:%0]

            # Removes the /index.php/ part from a URL, if present
            RewriteCond %{ENV:REDIRECT_STATUS} =""
            RewriteRule ^index\.php(?:/(.*)|$) %{ENV:BASE}/$1 [R=301,L]

            # If the requested filename exists, simply serve it.
            # Otherwise rewrite all other queries to the front controller.
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^ %{ENV:BASE}/index.php [L]
        </IfModule>

    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF
# On enlève la configuration par défaut
a2dissite 000-default
# On met la notre
a2ensite roombooker
systemctl restart apache2

cat <<EOF
Service installed at http://192.168.56.80/

EOF

echo "END - install RoomBooker webapp"
