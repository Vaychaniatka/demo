up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose down && docker-compose up -d

build:
	cp .env.dist .env
	cp ./../app/.env.dist ./../app/.env
	cp ./../app/.env.test.dist ./../app/.env.test
	docker-compose up --build -d
	make composer-install
	docker exec -it php-fpm bin/console doctrine:migrations:migrate  --no-interaction
	docker exec -it php-fpm bin/console hautelook:fixtures:load --no-interaction

composer-install:
	docker exec -it php-fpm composer install

test:
	docker exec -it php-fpm bin/console doctrine:database:create --env=test
	docker exec -it php-fpm bin/console doctrine:schema:create --env=test
	docker exec -it php-fpm bin/phpunit
	docker exec -it php-fpm bin/console doctrine:database:drop --force --env=test
