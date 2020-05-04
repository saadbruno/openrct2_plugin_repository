.PHONY: build run restart

# Build commands
build: build-network build-sass build-containers
build-network:
	docker network create proxy.openrct2plugins.test
build-containers:
	docker-compose build
build-sass:
	docker run --rm  -v `pwd`/../code/scss:/usr/src/app/scss  -v `pwd`/../code/public/styles:/usr/src/app/css cscheide/node-sass node-sass -r -o /usr/src/app/css/ /usr/src/app/scss/custom/

# Run commands
run:
	docker-compose up -d
run-nginx:
	docker-compose start nginx

# Stop containers
stop:
	docker-compose down
stop-nginx:
	docker-compose stop nginx
stop-sass:
	docker-compose stop nginx

# Clean and redeploy
clean:
	docker system prune -f

redeploy: stop clean build-network build run

restart-nginx: stop-nginx run-nginx

# Attach to command line inside container
manage-php:
	docker exec -it orct2p_php-fpm bash

# logs
logs:
	docker-compose logs -f
logs-php:
	docker-compose logs -f php-fpm
logs-nginx:
	docker-compose logs -f nginx

# aliases
sass: build-sass