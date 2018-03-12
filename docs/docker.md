# Docker

## Setup

Prepare `docker-compose` environment

    cp .env-dist .env
    cp docker-compose.override.yml-dist docker-compose.override.yml

and application    
    
    cp src/app.env-dist src/app.env
    chmod a+w web/assets/ runtime/

Start stack

    docker-compose up -d

Run composer installation

    docker-compose exec php composer install
    docker-compose exec php yii migrate --interactive=0

Access the app at `https://timesheet.127.0.0.1.xip.io/`, the default login is:

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



