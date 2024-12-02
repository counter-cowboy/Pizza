.PHONY: help build up start down destroy stop restart logs logs-api ps login-timescale login-api db-shell

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