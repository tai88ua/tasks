FROM php:8.1-apache
RUN a2enmod rewrite

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql