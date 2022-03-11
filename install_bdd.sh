#!/bin/bash

## install server postgres

IP=$(hostname -I | awk '{print $2}')
APT_OPT="-o Dpkg::Progress-Fancy="0" -q -y"
LOG_FILE="/vagrant/logs/install_bdd.log"
DEBIAN_FRONTEND="noninteractive"

#Utilisateur a créer (si un vide alors pas de création)
DBNAME="roombooker"
DBUSER="roombooker_user"
DBPASSWD="network"
#Fichier sql à injecter (présent dans un sous répertoire)
DBFILE="/vagrant/files/creation_tables.sql"

echo "START - install MariaDB - "$IP

echo "=> [1]: Install required packages ..."
DEBIAN_FRONTEND=noninteractive
apt-get install -o Dpkg::Progress-Fancy="0" -q -y \
	mariadb-server \
	mariadb-client \
   >> $LOG_FILE 2>&1


echo "=> [2]: Configuration du service"
if [ -n "$DBNAME" ] && [ -n "$DBUSER" ] && [ -n "$DBPASSWD" ] ;then
  mysql -e "CREATE DATABASE $DBNAME" \
  >> $LOG_FILE 2>&1
  mysql -e "grant all privileges on $DBNAME.* to '$DBUSER'@'%' identified by '$DBPASSWD'" \
  >> $LOG_FILE 2>&1
fi


mysql -e "CREATE DATABASE phpmyadmin"
mysql -e "GRANT ALL PRIVILEGES ON phpmyadmin.* TO 'pma'@'%' IDENTIFIED BY 'pmapass'"

echo "=> [3]: Configuration de BDD"
if [ -f "$DBFILE" ] ;then
  echo "salut, ça fonctionne :)"
  mysql $DBNAME < $DBFILE \
  >> $LOG_FILE 2>&1
fi

sed -i "s/127.0.0.1/192.168.56.81/" /etc/mysql/mariadb.conf.d/50-server.cnf

ssh-keygen -t rsa -f /home/vagrant/.ssh/id_rsa -q -P "" <<< echo "vagrant"
scp -i ~/.ssh/id_rsa.pub vagrant@192.168.56.82:~/.ssh

/etc/init.d/mariadb restart

echo "END - install MariaDB"

