DC=docker compose --env-file .env -f .docker/docker-compose.yaml
APP=$(DC) exec app
MYSQL=$(DC) exec mysql

.PHONY: help init up down restart ps logs shell mysql install key migrate seed fresh test pint analyse quality routes clear

help:
	@echo "Доступные команды:"
	@echo "  make init      Первый запуск проекта"
	@echo "  make up        Запустить контейнеры"
	@echo "  make down      Остановить контейнеры"
	@echo "  make restart   Перезапустить контейнеры"
	@echo "  make ps        Показать контейнеры"
	@echo "  make logs      Смотреть логи"
	@echo "  make shell     Зайти в app-контейнер"
	@echo "  make mysql     Зайти в MySQL"
	@echo "  make install   Установить зависимости"
	@echo "  make migrate   Запустить миграции"
	@echo "  make seed      Запустить seeders"
	@echo "  make fresh     Пересоздать базу с demo-данными"
	@echo "  make test      Запустить тесты"
	@echo "  make pint      Запустить Laravel Pint"
	@echo "  make analyse   Запустить PHPStan/Larastan"
	@echo "  make quality   Pint + tests + analyse"
	@echo "  make routes    Показать маршруты"
	@echo "  make clear     Очистить Laravel cache"

init:
	$(DC) up -d
	$(APP) composer install
	$(APP) php artisan key:generate
	$(APP) php artisan migrate --seed

up:
	$(DC) up -d

down:
	$(DC) down

restart:
	$(DC) down
	$(DC) up -d

ps:
	$(DC) ps

logs:
	$(DC) logs -f

shell:
	$(APP) bash

mysql:
	$(MYSQL) mysql -ularavel_url -p'pass123!' laravel_url

install:
	$(APP) composer install

key:
	$(APP) php artisan key:generate

migrate:
	$(APP) php artisan migrate

seed:
	$(APP) php artisan db:seed

fresh:
	$(APP) php artisan migrate:fresh --seed

test:
	$(APP) php artisan test

pint:
	$(APP) ./vendor/bin/pint

analyse:
	$(APP) composer analyse

quality:
	$(APP) ./vendor/bin/pint
	$(APP) php artisan test
	$(APP) composer analyse

routes:
	$(APP) php artisan route:list

clear:
	$(APP) php artisan optimize:clear
