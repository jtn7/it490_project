FROM composer:latest as vendor

COPY composer.json /app

RUN apk update \
    && apk add \
        rabbitmq-c-dev \
        libssh-dev \
        libssh2-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \
    && composer install

FROM php:7-apache

RUN apt-get update \
    && apt-get install -y \
        librabbitmq-dev \
        libssh-dev \
    && docker-php-ext-install \
        bcmath \
        sockets

RUN mkdir /var/log/dnd && chmod 777 /var/log/dnd

COPY --from=vendor /app/vendor /var/www/html/vendor
