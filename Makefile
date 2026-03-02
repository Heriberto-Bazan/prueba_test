.PHONY: build up down restart logs shell-php shell-node shell-db test clean

## ---- Docker ----

build:
	docker compose build

up:
	docker compose up -d

up-logs:
	docker compose up

start: build up
	@echo "Proyecto levantado:"
	@echo "  Backend:  http://localhost:8000"
	@echo "  Frontend: http://localhost:3000"

down:
	docker compose down

restart: down up

## ---- Logs ----

logs:
	docker compose logs -f

logs-php:
	docker compose logs -f php

logs-node:
	docker compose logs -f node

logs-nginx:
	docker compose logs -f nginx

logs-db:
	docker compose logs -f database

## ---- Shell ----

shell-php:
	docker compose exec php bash

shell-node:
	docker compose exec node sh

shell-db:
	docker compose exec database psql -U invoice_user -d invoice_db

## ---- Backend ----

composer-install:
	docker compose exec php composer install

cache-clear:
	docker compose exec php php bin/console cache:clear

migrations:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

test:
	docker compose exec php vendor/bin/phpunit

## ---- Frontend ----

npm-install:
	docker compose exec node npm install

npm-build:
	docker compose exec node npm run build

## ---- Limpieza ----

clean:
	docker compose down -v --rmi local
	@echo "Contenedores, volumenes e imagenes eliminados"