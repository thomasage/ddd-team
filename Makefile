EXEC_SYMFONY = symfony

.PHONY = cs deptrac help installdb phpstan test

.DEFAULT_GOAL := help

##

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

installdb: composer.lock ## Create database schema and load fixtures (see docker-compose.yaml for database config)
	@$(EXEC_SYMFONY) php fixtures/install.php

##

cs: ## Fix code style
	@$(EXEC_SYMFONY) run vendor/bin/php-cs-fixer fix

deptrac: ## Analyse dependencies
	@$(EXEC_SYMFONY) run vendor/bin/deptrac --formatter=graphviz

phpstan: ## Static analysis
	@$(EXEC_SYMFONY) run vendor/bin/phpstan analyse

test: ## Run tests suite
	@$(EXEC_SYMFONY) run vendor/bin/phpunit

##
