FROM composer:latest as vendor

COPY composer.json /app

RUN apk update \
    && apk --no-cache add alpine-sdk \
        openssl-dev \
        php7-pear \
        php7-dev \
    && pecl install mongodb \
    && pecl clear-cache \
    && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini \
    && docker-php-ext-install \
        bcmath \
        sockets \
    && composer install

FROM php:7-cli-alpine

RUN apk update \
    && apk --no-cache add \
        rabbitmq-c-dev \
        libssh-dev \
        libssh2-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \
        pdo \
        pdo_mysql \
    && mkdir -p /step2/vendor

RUN apk --no-cache add alpine-sdk \
        openssl-dev \
        php7-pear \
        php7-dev \
    && pecl install mongodb \
    && pecl clear-cache \
    && echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini \
    && docker-php-ext-enable mongodb

RUN mkdir /var/log/dnd && chmod 777 /var/log/dnd

COPY --from=vendor /app/vendor /step2/vendor

WORKDIR /step2