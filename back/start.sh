#!/bin/sh

php authentication/login.php &
php authentication/register.php &

php messageBoard/getForums.php &
php messageBoard/createStuff.php &

php userFlows/userRetrieve.php &
php userFlows/userStore.php

