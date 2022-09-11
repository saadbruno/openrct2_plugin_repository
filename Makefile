.PHONY: run
include .env

# Build commands
build: build-sass build-php
build-php:
	docker-compose build php-fpm
build-sass:
	docker run --rm  -v `pwd`/code/scss:/usr/src/app/scss  -v `pwd`/code/public/styles:/usr/src/app/css cscheide/node-sass node-sass -r -o /usr/src/app/css/ /usr/src/app/scss/custom/

# Run commands
run:
	docker-compose up -d
run-nginx:
	docker-compose up -d nginx
run-php:
	docker-compose up -d php-fpm
run-mysql:
	docker-compose up -d mysql
# Stop containers
stop:
	docker-compose down
stop-nginx:
	docker-compose stop nginx
stop-sass:
	docker-compose stop nginx
stop-php:
	docker-compose stop php-fpm
stop-mysql:
	docker-compose stop mysql
# Clean and redeploy
clean:
	docker system prune -f
prune-db:
	docker volume rm openrct2_plugin_repository_orct2p-db-dev
redeploy: stop clean build run

redeploy-web: stop-nginx stop-php clean build-php run-php run-nginx

restart-nginx: stop-nginx run-nginx

redeploy-mysql: stop-mysql clean run-mysql

# Attach to command line inside container
manage-php:
	docker exec -it orct2p_php-fpm bash
manage-nginx:
	docker exec -it orct2p_nginx bash
manage-mysql:
	docker exec -it orct2p_mysql mysql -u admin -p orct2p

# logs
logs:
	docker-compose logs -f
logs-php:
	docker-compose logs -f php-fpm
logs-nginx:
	docker-compose logs -f nginx
logs-mysql:
	docker-compose logs -f mysql

# database management
backup-mysql:
	docker exec -it orct2p_mysql mysqldump -u admin -p$(MYSQL_PASSWORD) orct2p | tail -n +2 > ./docker/mysql/backup/orct2p_dev_restore.sql;  cp ./docker/mysql/backup/orct2p_dev_restore.sql ./docker/mysql/backup/orct2p_dev_backup_`date +%Y%m%d_%H%M%S`.sql
restore-mysql:
	cat ./docker/mysql/backup/orct2p_dev_restore.sql | docker exec -i orct2p_mysql mysql -u admin -p$(MYSQL_PASSWORD) orct2p 

# other tools
update-plugins:
	docker exec orct2p_php-fpm php /var/www/code/bin/update_plugins.php

#### PRODUCTION ####
build-nginx-prod:
	docker-compose -f docker-compose.prod.yml build nginx
build-php-prod:
	docker-compose -f docker-compose.prod.yml build php-fpm
build-prod:
	docker-compose -f docker-compose.prod.yml build
build-nginx-prod-no-cache:
	docker-compose -f docker-compose.prod.yml build --no-cache nginx
build-php-prod-no-cache:
	docker-compose -f docker-compose.prod.yml build --no-cache php-fpm
build-prod-no-cache:
	docker-compose -f docker-compose.prod.yml build --no-cache


run-mysql-prod:
	docker-compose -f docker-compose.prod.yml up -d mysql
run-nginx-prod:
	docker-compose -f docker-compose.prod.yml up -d nginx
run-php-prod:
	docker-compose -f docker-compose.prod.yml up -d php-fpm
run-prod:
	docker-compose -f docker-compose.prod.yml up -d

stop-mysql-prod:
	docker-compose -f docker-compose.prod.yml stop mysql
stop-nginx-prod:
	docker-compose -f docker-compose.prod.yml stop nginx
stop-php-prod:
	docker-compose -f docker-compose.prod.yml stop php-fpm
stop-prod:
	docker-compose -f docker-compose.prod.yml down

redeploy-prod: build-sass build-prod run-prod

redeploy-all-prod: build-sass build-prod stop-prod clean run-prod

redeploy-nginx-prod: build-nginx-prod stop-nginx-prod clean run-nginx-prod

redeploy-php-prod: build-php-prod stop-php-prod clean run-php-prod

redeploy-web-prod: redeploy-prod

backup-mysql-prod:
	docker exec -it orct2p_mysql mysqldump -u admin -p$(MYSQL_PASSWORD) orct2p | tail -n +2 > ./docker/mysql/backup/orct2p_restore.sql;  cp ./docker/mysql/backup/orct2p_restore.sql ./docker/mysql/backup/orct2p_backup_`date +%Y%m%d_%H%M%S`.sql
restore-mysql-prod:
	cat ./docker/mysql/backup/orct2p_restore.sql | docker exec -i orct2p_mysql mysql -u admin -p$(MYSQL_PASSWORD) orct2p 

# aliases
sass: build-sass
scss: build-sass
css: build-sass
manage-db: manage-mysql
backup-db: backup-mysql
restore-db: restore-mysql
backup-db-prod: backup-mysql-prod
restore-db-prod: restore-mysql-prod