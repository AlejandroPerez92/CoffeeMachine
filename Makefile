up:
	docker compose up -d --build

sh:
	docker compose exec php bash

install:
	docker compose exec php composer install

test:
	docker compose exec php vendor/bin/phpunit
