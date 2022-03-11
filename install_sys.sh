#!/bin/bash
## install base system
## [JCG] creation d'un script system de base commun à toutes les VMS

IP=$(hostname -I | awk '{print $2}')
#Utilisateur a créer (si vide pas de création)
NOM=""
MDP=""
HDIR=""

APT_OPT="-o Dpkg::Progress-Fancy="0" -q -y"
LOG_FILE="/vagrant/logs/install_sys.log"
DEBIAN_FRONTEND=noninteractive

echo "START - Install Base System on "$IP

echo "=> [1]: Installing required packages..."
apt-get update $APT_OPT \
  >> $LOG_FILE 2>&1

apt-get install $APT_OPT \
  wget \
  gnupg \
  unzip \
  >> $LOG_FILE 2>&1

echo "=> [2]: Server configuration"
# Ajout de contrib et non-free pour les depots
sed -i 's/main/main contrib non-free/g' /etc/apt/sources.list
# Ajout de la ligne pour le proxy ESEO mais descativé par défaut
echo "#Acquire::http::Proxy \"http://scully.eseo.fr:9999\";" >> /etc/apt/apt.conf
# Pour avoir le clavier en français dans la console VB
# Actif au prochain redémarrage
# sed -i 's/XKBLAYOUT=\"us\"/XKBLAYOUT=\"fr\"/g' /etc/default/keyboard
# sed -i 's/XKBVARIANT=\"\"/XKBVARIANT=\"latin9\" /g' /etc/default/keyboard
/usr/bin/localectl set-keymap fr

echo "=> [3]: Ajout utilisateur"
# ajout utilisateur et autres
if [ -n "$NOM" ] ;then
  mkdir -p $HDIR
  adduser --home $HDIR --disabled-password --no-create-home $NOM
  echo $NOM:$MDP | chpasswd
  chown $NOM $HDIR
  chmod 755 $HDIR
fi

echo "END - Install Base System on "$IP
