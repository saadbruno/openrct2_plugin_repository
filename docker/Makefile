.PHONY: build run restart

build-network:
	docker network create proxy.openrct2plugins.test

build:
	docker-compose build

run:
	docker-compose up -d

run-nginx:
	docker-compose start nginx

stop:
	docker-compose down

stop-nginx:
	docker-compose stop nginx

clean:
	docker system prune -f

redeploy: stop clean build-network build run

restart-nginx: stop-nginx run-nginx

manage-php:
	docker exec -it orct2p_php-fpm bash

logs:
	docker-compose logs -f

logs-php:
	docker-compose logs -f php-fpm

logs-nginx:
	docker-compose logs -f nginx