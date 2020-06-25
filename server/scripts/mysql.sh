#!/bin/bash

if [  ! -d "/var/lib/mysql" ] || [ -z "$(ls -A /var/lib/mysql)" ]
then
	echo "MySQL Server is Initializing"
	mysqld --initialize-insecure
	#chmod -R 770 /var/lib/mysql

	if [ -f /scripts/default.sql ]
	then
	
		mkdir -p /var/run/mysqld
		chown mysql:mysql /var/run/mysqld

		/usr/bin/mysqld_safe > /dev/null 2>&1 &
		echo "MySQL Starting For Injecting default_schema.sql"
		RET=1
		while [[ RET -ne 0 ]]; do
			echo "=> Waiting for confirmation of MySQL service startup"
			sleep 5
			mysql -uroot -e "status" > /dev/null 2>&1
			RET=$?
		done

		echo "Injecting default schema..."
		mysql -uroot < /script/default.sql
		rm -f /script/default.sql
		echo "Done!"
		mysqladmin -uroot shutdown
	else
		echo "There is no SQL file for injection"
	fi
else
	echo "MySQL databases are already exist"
fi



