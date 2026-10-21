FROM nginx:1.31

VOLUME /var/www/code
WORKDIR /var/www/code

COPY docker/nginx/prod/nginx.prod.conf /etc/nginx/conf.d/default.conf
COPY /code /var/www/code