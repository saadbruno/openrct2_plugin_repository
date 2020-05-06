FROM php:7.4-fpm

COPY /code /var/www/code
COPY docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini

RUN docker-php-ext-install pdo_mysql

WORKDIR "/var/www"
