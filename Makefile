build:
	docker compose build --pull --no-cache

build-prod:
	docker compose -f docker-compose.yml build --pull --no-cache

up:
	docker compose --env-file .env.dev.docker up

up-prod:
	docker compose -f docker-compose.yml --env-file .env.dev.docker up

down:
	docker compose down --remove-orphans

tag:
	docker image tag ogrine-rates_app skkay/ogrine-rates:$(major)
	docker image tag ogrine-rates_app skkay/ogrine-rates:$(minor)
	docker image tag ogrine-rates_app skkay/ogrine-rates:$(patch)
	docker image tag ogrine-rates_app skkay/ogrine-rates:latest

push:
	docker image push --all-tags skkay/ogrine-rates
