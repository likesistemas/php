#!/bin/bash
cd /var/www/
mkdir -p temp/
chown -v www-data:www-data temp/
chmod +x cli.php
run-as-www cli.php
chmod +x createPreload.php
sudo php createPreload.php