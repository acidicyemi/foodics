start:
	docker-compose up -d

bash:
	docker-compose exec foodics_php sh

setup:
	docker network create foodics_network && \
	docker volume create foodics_mysql

cleanup:
	docker network remove foodics_network && \
	docker volume remove foodics_mysql
