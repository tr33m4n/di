.PHONY: help
.DEFAULT_GOAL := help

help: ## Lists all available commands, the default command when running `make` with no arguments
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

test: ## Run tests
	./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
