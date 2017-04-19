# Docker

## Requirements

- [Docker Toolbox](https://www.docker.com/products/docker-toolbox)
  - Docker `>=1.10`
  - docker-compose `>=1.7.0`


## Setup

Prepare `docker-compose` environment

    cp .env-dist .env

and application    
    
    cp src/app.env-dist src/app.env
    cp src/config/local.php-dist src/config/local.php
    mkdir web/assets

Start stack

    docker-compose up -d

Run composer installation

    docker-compose run --rm php composer install

Access the app at `http://127.0.0.1:20080`, the default login is:

    username: admin
    password: secret


## Develop

Show containers

    docker-compose ps

Create bash    
    
    docker-compose exec php bash

Run package update in container    
    
    $ composer update -v

...

    $ yii help

      
### CLI
    
    docker run dmstr/yii2-app yii


## Resources
    
- [Docker documentation](https://docs.docker.com)

