FROM php:8.2-apache


RUN docker-php-ext-install mysqli

COPY . /var/www/html

RUN mkdir -p /var/www/html/uploads

RUN chmod 777 /var/www/html/uploads
