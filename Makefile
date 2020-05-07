app_name = cyberNewsApi

export HTTP_PORT = 8091
export MYSQL_PORT = 3310
export PROJECT_NAME = cyberNewsApi
export WORK_DIR = $(shell pwd)

init:
	make composer-install
	sudo chown -R $(USER):$(USER) $(WORK_DIR)
	make up

composer-install:
	docker run --rm -v $(WORK_DIR):/app composer install

up:
	docker-compose up

ssh:
	docker exec -it cybernews-app /bin/bash

stop:
	docker-compose down


