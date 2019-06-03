# Advanced migrations

This module contains some examples of more advanced migrations:

* Funky process plugin to import hierarchical terms
* Migrate tools data fetcher plugin for scanning a directory of JSON files
* Migration templates to import entity translations

Just setup the project following the steps below using Docker and you can run the migrations:

```
drush ms
drush migrate:import --all
```

## Development setup

2. Run the following commands:

```
$ docker-compose up -d
$ docker-compose exec php composer install
$ docker-compose exec php ./vendor/bin/run drupal:site-install
```

3. Go to [http://localhost:8080/build](http://localhost:8080/build) and you have a Drupal site running with the module and its dependencies!

#### Requirements:

- [Docker](https://www.docker.com/get-docker)
- [Docker Compose](https://docs.docker.com/compose/)
