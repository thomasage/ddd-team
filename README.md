# DDD Team

A very simple example of DDD (Clean Architecture oriented)

## Prerequisites

* PHP >= 7.4 (https://www.php.net)
* Symfony binary (https://symfony.com/download)
* Docker (https://www.docker.com/)
* Docker Compose (https://docs.docker.com/compose/)
* Composer (https://getcomposer.org/)

## Installation

* Check `docker-composer.yaml`
* Run `docker-compose up -d`
* Run `make installdb` (create schema and load fixtures)
* Run tests with `make test`
* Run code style checker `make cs`
* Run phpstan with `make phpstan`
* Run a cli command with `symfony php cli/display-team.php [UUID]` (UUID is provided my `make installdb`)
