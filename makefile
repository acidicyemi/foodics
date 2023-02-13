all:
	echo open makefile to list all commands

setup_docker:
	docker network create foodics_network && \
	docker volume create foodics_mysql

copy_env:
	cp .env.example .env

start:
	docker-compose up -d

install_packages:
	docker-compose exec foodics_php composer install

run_test:
	docker-compose exec foodics_php php artisan test

migrate:
	docker-compose exec foodics_php php artisan migrate

generate_key:
	docker-compose exec foodics_php php artisan key:generate

seed:
	docker-compose exec foodics_php php artisan db:seed

db_bash: 
	docker-compose exec foodics_mysql bash

php_bash:
	docker-compose exec foodics_php sh

cleanup:
	docker-compose  down
	docker network remove foodics_network && \
	docker volume remove foodics_mysql
