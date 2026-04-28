#!/bin/bash
cd /home/site/wwwroot
php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan db:seed --force
