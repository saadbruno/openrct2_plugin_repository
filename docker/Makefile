.PHONY: build run restart

build-network:
	docker network create proxy.openrct2plugins.test

build:
	docker-compose build

run:
	docker-compose up -d

stop:
	docker-compose down

clean:
	docker system prune -f

redeploy: stop clean build-network build run