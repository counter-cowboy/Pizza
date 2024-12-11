.PHONY: help build up start down destroy stop restart logs logs-api ps login-timescale login-api db-shell

chmod:
	chmod 644 ./docker/volume/init.sql
build:
	docker compose build
up:
	docker compose up -d
down:
	docker compose down
logs:
	docker compose  logs --tail=100 -f $(c)
ps:
	docker ps -a
migrate:
	docker exec -it php php artisan migrate
fresh:
	docker exec -it php php artisan migrate:fresh
seed:
	docker exec -it php php artisan db:seed
fresh-seed:
	docker exec -it php php artisan migrate:fresh --seed
test:
	docker exec -it php php artisan test
fix:
	tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src

# Makefile для миграции и сидирования баз данных

# Переменные для команд
PHP=php
ARTISAN=php artisan

# Основная база данных
MIGRATE_MAIN=$(ARTISAN) migrate --database=mysql
SEED_MAIN=$(ARTISAN) db:seed --database=mysql

# Тестовая база данных
MIGRATE_TEST=$(ARTISAN) migrate --database=mysql_test
SEED_TEST=$(ARTISAN) db:seed --database=mysql_test

# Правило для миграции и сидирования для обеих баз данных
db:
	@echo "Running migrations and seed for main database..."
	docker exec -it php $(MIGRATE_MAIN)
	docker exec -it php $(SEED_MAIN)
	@echo "Running migrations and seed for test database..."
	docker exec -it php $(MIGRATE_TEST)
	docker exec -it php $(SEED_TEST)
	@echo "Migrations and seed completed."