#!/bin/bash

# Update nginx to match worker_processes to no. of cpu's
procs=$(cat /proc/cpuinfo | grep processor | wc -l)
sed -i -e "s/worker_processes  1/worker_processes $procs/" /etc/nginx/nginx.conf

chown -R www-data:www-data /var/lib/nginx /var/log/nginx

# Start supervisord and services
/usr/bin/supervisord -n -c /etc/supervisord.conf
(521647.0 - 315253)  + (191200.0 - 191000) + (40400.0 - 38315) + (513879.04 - 379411)

6191 + 6 + 62 + 4034



15649.41 + 15416.48 + 5736.00 + 4260.00 + 1149.45