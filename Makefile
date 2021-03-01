.PHONY: help
.DEFAULT_GOAL = help

## —— Docker 🐳———————————————————————————————————————————————————————————————
build: ## build les container docker
	docker-compose up --build -d

start: ## lancer les container docker
	docker-compose start

stop: ## Arreter les container docker
	docker-compose stop

rm: stop ## Supprimer les container docker
	docker-compose rm -f

## —— Composer ———————————————————————————————————————————————————————————————
install: ## On install composer
	composer install

update: ## on update composer
	composer update

## —— Symfony 🎶———————————————————————————————————————————————————————————————
cc: ## on vide le cache
	php bin/conole c:c

controller: ## On crée un controller
	php bin/console make:controller

form: ## On crée un formulaire
	docker-compose exec web sh -c 'php bin/console make:form'

crud: ## On crée un formulaire
	docker-compose exec web sh -c 'php bin/console make:crud'

entity: ## On crée une entité
	docker-compose exec web sh -c 'php bin/console make:entity'

migration: ## on crée une migration
	docker-compose exec web sh -c 'php bin/console make:migration'

migrate: ## on migre les migrations
	docker-compose exec web sh -c 'php bin/console d:m:m' --no-interaction

login: ## on crée la partie conexion
	php bin/console make:auth

register: ## on crée la partie enregistrement
	php bin/console make:registration-form

user: ## On crée l'entitée user
	php bin/console make:user

## —— Others 🛠️️ ———————————————————————————————————————————————————————————————
help: ## Liste des commandes
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


