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
    echo "It seems MySql side done. Good luck..."
done

