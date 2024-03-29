SHELL := /bin/bash

tests-unit:
	php bin/phpunit --configuration phpunit.xml.dist --coverage-text --testsuite Unit
.PHONY: tests-unit

tests:
	php bin/console doctrine:database:drop --env=test --force
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:create --env=test
	php bin/console doctrine:fixtures:load --env=test --no-interaction
	php -dxdebug.mode=coverage bin/phpunit --configuration phpunit.xml.dist --coverage-text tests
.PHONY: tests

cc:
	symfony console cache:clear
.PHONY: cc

tr:
	symfony console translation:update ru --force --domain=app --dump-messages
.PHONY: tr