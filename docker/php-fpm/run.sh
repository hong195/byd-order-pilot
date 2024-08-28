#!/bin/bash

# Update nginx to match worker_processes to no. of cpu's
procs=$(cat /proc/cpuinfo | grep processor | wc -l)
sed -i -e "s/worker_processes  1/worker_processes $procs/" /etc/nginx/nginx.conf

chown -R www-data:www-data /var/lib/nginx /var/log/nginx

# Start supervisord and services
/usr/bin/supervisord -n -c /etc/supervisord.conf
