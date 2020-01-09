SHELL := /usr/bin/env bash

.DEFAULT: help
.PHONY: help
ifndef VERBOSE
.SILENT:
endif

init:
	cp vagrantuser.example.yml .vagrantuser
	cp .env.dist .env.local

vagrant-rebuild:
	vagrant halt
	vagrant destroy -f
	vagrant up

run: down run-app

run-app:
  # doing it in this order solves race condition but it isn't a great solution.
  # containers should wait until amqp is available ideally.
	docker volume prune -f
	docker-compose build --no-cache --parallel app web-server
	docker-compose up web-server

composer: base-dev
	./tools/bin/run_test.sh composer

build-docker: down base-dev
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml build --parallel

test: base-dev security-check phpcs phpspec infection behat

down:
	docker-compose down

base-build:
	echo 'Running base PHP image...';
	docker-compose --log-level ERROR run base-php

base-dev: base-build
	echo 'Running base dev PHP image...'
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml --log-level ERROR run dev-php-base

phpspec:
	./tools/bin/run_test.sh phpspec

# Runs infection. Depends on phpunit so we run that first
infection: phpunit
	./tools/bin/run_test.sh infection

phpcs:
	./tools/bin/run_test.sh phpcs

# Runs phpunit
phpunit:
	./tools/bin/run_test.sh phpunit

security-check: symfony-cli
	./tools/bin/run_test.sh security-check

symfony-cli: base-dev
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml run symfony-cli

behat:
	./tools/bin/run_test.sh behat
