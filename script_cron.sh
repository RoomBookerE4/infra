#!/bin/sh

(crontab -l; echo "* * * * * /vagrant/scripts/install_backup.sh") | crontab -
