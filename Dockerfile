FROM php:7.2-fpm

RUN apt-get update

RUN docker-php-ext-install pdo_mysql 

WORKDIR /var/www/html/

USER 1000
