#!/bin/sh

FILE=coordonnees.sql.`date +"%Y%m%d"`
DBSERVER=192.168.56.82
DATABASE="roombooker"
USER="roombooker_user"
PASS="network"

#supprime l'ancienne version si la sauvegarde est faite le meme jour (donc meme nom)
unalias rm     2> /dev/null
rm ${FILE}     2> /dev/null
rm ${FILE}.gz  2> /dev/null

#creation du fichier de sauvegarde de la base de donnees
mysqldump --opt --user=${USER} --password=${PASS} ${DATABASE} > ${FILE}

#mysql -u '$USER' -p '$DATABASE' < '$DATABASE'.sql
#creation du fichier zip
gzip $FILE

echo "${FILE}.gz was created:"
ls -l ${FILE}.gz 

sudo chgrp vagrant ${FILE}.gz
sudo chmod 777 ${FILE}.gz

#envoyer la sauvegarde sur la backup (autre ordinateur)
scp ${FILE}.gz vagrant@192.168.4.181:/vagrant/scripts

echo "fichier envoye sur la backup"

#changer les droits id_rsa 
#cd .ssh
#sudo chgrp vagrant id_rsa
#sudo chmod 777 id_rsa
#cd /vagrant/scripts
#chmod 777 coordls


#back up creeer un utilisateur /bin/bash
#generer cles dans authorized key (cle publique)
#envoyer cle prive dans bdd-srv
#creer utilisateur sur base de bdd
