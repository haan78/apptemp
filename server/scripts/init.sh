#!/bin/bash

basedir=$(dirname "$0")
createsql="$basedir/create.sql"
updatesql="$basedir/update.sql"

echo "Starting to Apache"
service apache2 start

if [ ! -z "$MYSQL_CONNECTION_STRING" ]
then
    echo "--> Parsing MySql connection string..."
    host=$(cut -d "," -f 1 <<< $MYSQL_CONNECTION_STRING)
    user=$(cut -d "," -f 2 <<< $MYSQL_CONNECTION_STRING)
    pass=$(cut -d "," -f 3 <<< $MYSQL_CONNECTION_STRING)
    dbname=$(cut -d "," -f 4 <<< $MYSQL_CONNECTION_STRING)
    port=$(cut -d "," -f 5 <<< $MYSQL_CONNECTION_STRING)

    echo "--> Adding connection parameter into php.ini"
    echo "mysqli.default_host = $host" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini
    echo "mysqli.default_user = $user" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini
    echo "mysqli.default_pw = $pass" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini
    echo "mysqli.default_port = $port" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini

    while ! mysqladmin ping -h"$host" --silent
    do
        echo "--> Waiting for MySql Server"
        sleep 1
    done

    if [ -z "$pass" ]
    then
        mycon="mysql -h $host -u$user --port=$port"
    else
        mycon="mysql -h $host -u$user -p$pass --port=$port"
    fi

    echo "--> Checking database..."
    result=$($mycon -e "SHOW DATABASES LIKE '$dbname'")
    if [[ $result != *"$dbname"* ]]
    then
        if [ -f "$createsql" ]
        then
            echo "--> Deoploying Creation Script $createsql ..."
            $($mycon < $createsql )
            if [ $? -ne 0 ]
            then
                exit 1
            fi
        else
            echo "--> ERROR: $createsql not found!"
            exit 1
        fi
    fi

    if [ -f "$updatesql" ]
    then
        echo "--> Deoploying Update Script $updatesql ..."
        $($mycon < $updatesql )
        if [ $? -ne 0 ]
        then
            exit 1
        fi
    else
        echo "--> $updatesql not found. OK! No Problem"
    fi
else
    echo "--> Environment Variable MYSQL_CONNECTION_STRING is not found"
    exit 1
fi

echo "---> It seems all done. Good luck!!!"
exec supervisord -n
