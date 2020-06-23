#!/bin/bash

service apache2 start
service ssh start


/scripts/mysql.sh

service mysql start

exec supervisord -n
