#!/bin/bash

sudo service apache2 restart
sudo cp -r ~/Downloads/rPi/domotique/ /var/www/html/
sudo chown -R www-data:www-data /var/www/html/domotique/
