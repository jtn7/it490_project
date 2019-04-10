#!/bin/bash
#This script file is for the pulling the latest images and creating container of all the components


#Introduction
echo "\nWelcome to the docker helper\n"


#Pulling the latest images
sleep 1; echo "Let us get the latest docker image. . .\n"
docker pull jtn7/php-backend ; echo ""
docker pull jtn7/php-apache ; echo ""
docker pull jtn7/rabbit ; echo ""
docker pull jtn7/db ; echo ""
docker pull mongo:4 ; echo ""


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


#Editing the IP address in PHP file automatically
rabbitIP=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' rabbit)
mydbIP=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' mydb)
mymongoIP=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' mymongo)

echo "\nEditing PHP files to have the correct IP Addresses!\n"
sleep 1; echo "RabbitMQ IP address $rabbitIP\n"
sed -i -e "s/172.17.0.*/$rabbitIP\'\, \/\/ host/g" $PWD/back/rabbit/RabbitMQConnection.php
sed -i -e "s/172.17.0.*/$rabbitIP\'\, \/\/ host/g" $PWD/front/RPC.php
sleep 1; echo "MYSQL database IP address $mydbIP\n"
sed -i -e "s/172.17.0.*/$mydbIP\'\;/g" $PWD/back/databases/AuthDB.php
sed -i -e "s/172.17.0.*/$mydbIP\'\;/g" $PWD/back/databases/ForumsDB.php
sleep 1; echo "Mongo database IP address $mymongoIP\n"
sed -i -e "s/172.17.0.*/$mymongoIP:27017\'\;/g" $PWD/back/databases/MongoConnector.php

sleep 1; echo -n "Applying patches"; sleep 2; echo -n ". "; sleep 2; echo -n ". "; sleep 2; echo -n ". "; sleep 2; echo -n ". "; sleep 2; echo -n ". \n"
sleep 3; echo "Patching task completed!\n"


#Back End
sleep 1; echo "Polishing the back-end.\n"
sleep 5; docker run --rm -d -v $PWD/back:/step2/back -w /step2/back --name back jtn7/php-backend ./start.sh


#Executing SQL script on the daemon
echo "\nWait 5 seconds so MySQL service starts in the container. . . \n"
sleep 5; docker exec -i mydb mysql -u 'root' -p'pass' < $PWD/db/db.sql
echo "->You are insecure mySQL!\n"
echo "Done with the docker set-up! Thank you!\n"
