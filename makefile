all:
	echo open makefile to list all command

setup_docker:
	docker network create foodics_network && \
	docker volume create foodics_mysql

start:
	docker-compose up -d

db_bash: 
	docker-compose exec foodics_mysql bash

php_bash:
	docker-compose exec foodics_php sh

migrate:
	docker-compose exec foodics_php php artisan migrate

generate_key:
	docker-compose exec foodics_php php artisan key:generate

seed:
	docker-compose exec foodics_php php artisan db:seed

cleanup:
	docker-compose  down
	docker network remove foodics_network && \
	docker volume remove foodics_mysql
