#!/bin/bash

host=mysql
user=root
port=3306
pass=''

if [ -z "$pass" ]
then
    mycon="mysql -h $host -u$user --port=$port"
else
    mycon="mysql -h $host -u$user -p$pass --port=$port"
fi

echo $mycon

testr=$($mycon -e "SHOW DATABASES LIKE 'mysql'")

echo "Testing mysql connection"

if [[ $testr != *"mysql"* ]]
then
    echo "We have proble with MySql Connection"
    exit 1
else
    echo "MySql connection OK!"
fi

for sql in /scripts/databases/*.sql
do
    filename=$(basename -- "$sql")
    dbname="${filename%.*}"
    echo "File control $sql to $dbname in mysql-server"
    result=$($mycon -e "SHOW DATABASES LIKE '$dbname'")
    if [[ $result != *"$dbname"* ]]
    then
        echo "Deoploying SQL script $sql"
        $($mycon < $sql )
        if [ $? -eq 0 ]; then
            echo "Script $sql executed successfuly"
        else
            exit 1
        fi        
    else
        echo "Database $dbname is already exist"
    fi
done

echo "Adding connection parameter into php.ini"
echo "mysqli.default_host = $host" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini
echo "mysqli.default_user = $user" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini
echo "mysqli.default_pw = $pass" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini
echo "mysqli.default_port = $pass" >> /etc/php/7.4/apache2/conf.d/20-mysqli.ini

service apache2 start

echo "it seems all done. Good luck!"
exec supervisord -n
