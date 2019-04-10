#!/bin/bash
#This script file is for the front end to pull the latest image and create its container


#Introduction
echo "\nWelcome to the docker helper - Front End Edition\n"


#Pulling the latest images
sleep 1; echo "Let us get the latest docker image for front end\n"
docker pull jtn7/php-apache ; echo ""


#Editing the IP address in PHP file manually
echo "Editing PHP files to have the correct IP Addresses!\n"
echo "What is the rabbitMQ's IP address? (Format is 172.17.0.0)"
read rabbitIP

sed -i -e "s/172.17.0.*/$rabbitIP\'\, \/\/ host/g" $PWD/front/RPC.php

sleep 1; echo -n "\nApplying patches"; sleep 2; echo -n ". "; sleep 2; echo -n ". "; sleep 2; echo -n ". "; sleep 2; echo -n ". "; sleep 2; echo -n ". \n"
sleep 3; echo "Patching task completed!\n"


#Front End
echo "Creating Front End container. . . \n"
sleep 1; docker run --rm -d -v $PWD/front:/var/www/html/front/ -p 80:80 --name front jtn7/php-apache


echo "\nDone with the docker set-up! Thank you!\n"
