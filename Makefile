build:
	docker compose build --pull --no-cache

build-prod:
	docker compose -f docker-compose.yml build --pull --no-cache

up:
	docker compose --env-file .env.dev.docker up

up-prod:
	docker compose -f docker-compose.yml --env-file .env.dev.docker up

down:
	docker compose  --env-file .env.dev.docker down --remove-orphans

bash:
	docker exec -it ogrine-rates_server /bin/bash
