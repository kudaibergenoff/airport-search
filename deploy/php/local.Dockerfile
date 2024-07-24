FROM php:8.3.0-fpm-alpine

# Install packages
RUN apk add --update linux-headers
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash

#Postgres
RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_pgsql

# Source code
COPY ./ /var/www
WORKDIR /var/www

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN composer install --no-interaction

CMD php-fpm

EXPOSE 9000
