#!/bin/bash
echo "Starting to Apache"
service apache2 start

if [ ! -z "$MYSQL_CONNECTION_STRING" ]
then
    echo "-> Parsing MySql connection string..."
    host=$(cut -d "," -f 1 <<< $MYSQL_CONNECTION_STRING)
    user=$(cut -d "," -f 2 <<< $MYSQL_CONNECTION_STRING)
    pass=$(cut -d "," -f 3 <<< $MYSQL_CONNECTION_STRING)
    port=$(cut -d "," -f 4 <<< $MYSQL_CONNECTION_STRING)

    echo "-> Adding connection parameter into php.ini"
    echo "mysqli.default_host = $host" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini
    echo "mysqli.default_user = $user" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini
    echo "mysqli.default_pw = $pass" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini
    echo "mysqli.default_port = $port" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini

    while ! mysqladmin ping -h"$host" --silent
    do
        echo "-> Waiting for MySql Server"
        sleep 1
    done

    if [ -z "$pass" ]
    then
        mycon="mysql -h $host -u$user --port=$port"
    else
        mycon="mysql -h $host -u$user -p$pass --port=$port"
    fi

    echo "-> Checking database scripts..."
    for sql in /scripts/databases/*.sql
    do
        filename=$(basename -- "$sql")
        dbname="${filename%.*}"
        echo "File control $sql to $dbname in mysql-server"
        result=$($mycon -e "SHOW DATABASES LIKE '$dbname'")
        if [[ $result != *"$dbname"* ]]
        then
            echo "-> Deoploying SQL script $sql"
            $($mycon < $sql )
            if [ $? -eq 0 ]; then
                echo "-> Script $sql executed successfuly"
            else
                exit 1
            fi        
        else
            echo "-> Database $dbname is already exist"
        fi
    done
else
    echo "-> Environment Variable MYSQL_CONNECTION_STRING is not found"
    exit 1
fi

echo "-->> It seems all done. Good luck!!!"
exec supervisord -n
