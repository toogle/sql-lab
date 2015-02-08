FROM php:5.6-apache

RUN docker-php-ext-install mysqli

COPY . /var/www/html/
