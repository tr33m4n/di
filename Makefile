list: # Lists all available commands, the default command when running `make` with no arguments
	@grep -v -e "^\t" Makefile | grep -Ev '(HEADLESS|ENV)' | grep . | awk -F":.+?#" '{ print $$1 " #" $$2 }' | column -t -s '#'

test: # Run tests
	./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
