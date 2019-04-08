#!/bin/bash
#The Docker Set-up Script
#Please pull the latest docker image BEFORE using this script file


#Introduction
echo "\nWelcome to the docker set-up!\n"
echo "Let me help you with that!\n"


#MYSQL Database
echo "Let's start with the MYSQL Database!\n"
sleep 1; docker run --rm -d -e MYSQL_ROOT_PASSWORD=pass --name mydb jtn7/db


#Mongo Database
echo "\nMongo Database next! \n"
sleep 1; docker run --rm -d -e MONGO_INITDB_ROOT_USERNAME=root -e MONGO_INITDB_ROOT_PASSWORD=pass --name mymongo mongo:4


#RabbitMQ
echo "\nCatching rabbits to run rabbitMQ. . . \n"
sleep 1; docker run --rm -d -p 15672:15672 --name rabbit jtn7/rabbit


#Front End
echo "\nHere comes front-end. \n"
sleep 1; docker run --rm -d -v $PWD/front:/var/www/html/front/ -p 80:80 --name front jtn7/php-apache


#Assign IP addresss so back-end container runs
rabbitIP=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' rabbit)
mydbIP=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' mydb)
mymongoIP=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' mymongo)

echo "\nEditing 5 files to have the correct IP Addresses!\n"
sleep 1; echo "RabbitMQ IP address $rabbitIP\n"
sed -i -e "s/172.17.0.*/$rabbitIP\'\, \/\/ host/g" $PWD/back/rabbit/RabbitMQConnection.php
sed -i -e "s/172.17.0.*/$rabbitIP\'\, \/\/ host/g" $PWD/front/RPC.php
sleep 1; echo "MYSQL database IP address $mydbIP\n"
sed -i -e "s/172.17.0.*/$mydbIP\'\;/g" $PWD/back/databases/AuthDB.php
sed -i -e "s/172.17.0.*/$mydbIP\'\;/g" $PWD/back/databases/ForumsDB.php
sleep 1; echo "Mongo database IP address $mymongoIP\n"
sed -i -e "s/172.17.0.*/$mymongoIP:27017\'\;/g" $PWD/back/databases/MongoConnector.php
sleep 1; echo -n "Applying patches"; sleep 3; echo -n ". "; sleep 3; echo -n ". "; sleep 3; echo -n ". "; sleep 3; echo -n ". "; sleep 3; echo -n ". \n"
sleep 5; echo "Patching task completed!\n"

#Back End
sleep 2; echo "Polishing the back-end. \n"
sleep 10; docker run --rm -d -v $PWD/back:/step2/back -w /step2/back --name back jtn7/php-backend ./start.sh


#Executing SQL script on the daemon
echo "\nWait 10 seconds so MySQL service starts in the container. . . \n"
sleep 10; docker exec -i mydb mysql -u 'root' -p'pass' < $PWD/db/db.sql
echo "->You are insecure mySQL!\n"
echo "Done with the docker set-up! Thank you!"
