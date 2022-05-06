#!/bin/bash
service postgresql start
service apache2 start
cd /var/www/tmt
php artisan websockets:serve