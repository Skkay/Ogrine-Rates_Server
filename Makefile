build:
	docker compose build --pull --no-cache

up:
	docker compose --env-file .env.dev.docker up

down:
	docker compose down --remove-orphans
