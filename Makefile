DOCKER_COMPOSE=/usr/local/bin/docker-compose
COMPOSE=${DOCKER_COMPOSE} -f devops/docker-compose/docker-compose.yml
OS := $(shell uname -s)
ARCH := $(shell uname -m)

help: ## print this help
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

phpstan: ## execute phpstan
	vendor/bin/phpstan analyze --level 8 src

phpcsfixer: ## execute phpcsfixer
	vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix

lint: phpcsfixer phpstan ## execute all quality tools

setup: ## install docker compose
	sudo curl -Ls https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(OS)-$(ARCH) -o ${DOCKER_COMPOSE}
	sudo chmod +x /usr/local/bin/docker-compose

up: ## start your local env (https://localhost)
	${COMPOSE} up --build -d

down: ## stop your local env
	${COMPOSE} down

restart: ## restart your local env
	${COMPOSE} restart

reset: ## reset your local env
	${COMPOSE} rm -f -v -s
	${COMPOSE} up --build -d

exec: ## execute a shell inside your php container
	${COMPOSE} exec php /bin/bash

generate_jwt_key: ## generate the JWT keys
	${COMPOSE} exec -T php /bin/bash -c "php bin/console lexik:jwt:generate-keypair --skip-if-exists"

migration_fixtures: ## Doctrine fixtures
	${COMPOSE} exec -T php /bin/bash -c "php bin/console doctrine:migration:migrate --no-interaction"
	${COMPOSE} exec -T php /bin/bash -c "php bin/console doctrine:fixtures:load --purge-with-truncate"
