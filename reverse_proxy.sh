#!bin/bash
sudo sed -i "23i\        ProxyPass / http://192.168.56.90:3000/\n" /etc/apache2/sites-available/000-default.conf


sudo a2enmod proxy
sudo a2enmod proxy_http
sudo systemctl restart apache2


