.PHONY: help build up start down destroy stop restart logs logs-api ps login-timescale login-api db-shell

chmod:
	chmod 644 ./docker/volume/init.sql
env:
	mv /src/.env.example /src/.env
build:
	docker compose build
up:
	chmod 644 ./docker/volume/init.sql
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

# для миграции и сидирования баз данных
PHP=php
ARTISAN=php artisan

# Основная БД
MIGRATE_MAIN=$(ARTISAN) migrate --database=mysql
SEED_MAIN=$(ARTISAN) db:seed --database=mysql

# Тестовая БД
MIGRATE_TEST=$(ARTISAN) migrate --database=mysql_test
SEED_TEST=$(ARTISAN) db:seed --database=mysql_test

db:
	@echo "Running migrations and seed for main database..."
	docker exec -it php $(MIGRATE_MAIN)
	docker exec -it php $(SEED_MAIN)
	@echo "Running migrations and seed for test database..."
	docker exec -it php $(MIGRATE_TEST)
	docker exec -it php $(SEED_TEST)
	@echo "Migrations and seed completed."
