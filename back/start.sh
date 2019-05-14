#!/bin/sh

php authentication/login.php &
php authentication/register.php &

php messageBoard/getForums.php &
php messageBoard/createStuff.php &

php user/userRetrieve.php &
php user/userStore.php

