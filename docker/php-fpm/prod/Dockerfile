FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql

COPY docker/php-fpm/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini
COPY /code /var/www/code

RUN chmod 777 /var/www/code/lib/htmlpurifier-4.13.0/library/HTMLPurifier/DefinitionCache/Serializer

WORKDIR "/var/www"
