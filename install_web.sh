#!/bin/bash

## install web server with php
IP=$(hostname -I | awk '{print $2}')

APT_OPT="-o Dpkg::Progress-Fancy="0" -q -y"
LOG_FILE="/vagrant/logs/install_web.log"
DEBIAN_FRONTEND="noninteractive"
DOCUMENT_ROOT="/var/www/html/roombooker/public"

echo "START - install web Server - "$IP

echo "=> [1]: Installing required packages..."


apt-get -y install apt-transport-https lsb-release ca-certificates curl >> $LOG_FILE 2>&1
wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg >> $LOG_FILE 2>&1
sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list' >> $LOG_FILE 2>&1
apt-get update >> $LOG_FILE 2>&1

# Installation de PHP 8.1
apt-get install $APT_OPT -y php8.1 >> $LOG_FILE 2>&1
# Installation des extensions PHP pour PHP8.1
apt-get install $APT_OPT -y php8.1-{mysql,mysqli,xml,intl,curl,xmlrpc,soap,gd,cli,xsl,zip,mbstring,xmlwriter}>> $LOG_FILE 2>&1

# Installation de PHP7.4 pour Moodle et PhpMyAdmin
apt install $APT_OPT php7.4 >> $LOG_FILE 2>&1
# Installation des extensions PHP
apt install $APT_OPT -y php7.4-{mysql,mysqli,xml,intl,curl,xmlrpc,soap,gd,json,cli,xsl,zip,mbstring,xmlwriter}>> $LOG_FILE 2>&1

# Installation de Apache
apt install $APT_OPT apache2 libapache2-mod-php7.4 libapache2-mod-php8.1 >> $LOG_FILE 2>&1


echo "=> [BONUS] Activation des extensions PHP"
phpenmod -v 8.1 pdo_mysql >> $LOG_FILE 2>&1
phpenmod -v 8.1 xml >> $LOG_FILE 2>&1
phpenmod -v 7.4 mysqli >> $LOG_FILE 2>&1

echo "=> [2]: Apache2 configuration"
	# Add configuration of /etc/apache2
	# Ajoute fichiers php LD
	

echo "END - install web Server"
