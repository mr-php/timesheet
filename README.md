# TimeSheet

:octocat: [`mr-php/timesheet`](https://github.com/mr-php/timesheet)

## Introduction

This is a timesheet application intended to generate invoices on [Saasu](https://www.saasu.com/) based on time entries at [Toggl](https://toggl.com/). 

The application is dockerized and built using Yii 2.0 Framework.


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

      
## Test

    cd tests
    cp .env-dist .env

Run tests in codeception (`forrest`) container
      
    docker-compose run forrest run
          
> :info: This is equivalent to `codecept run` inside the tester container          
  

### CLI
    
    docker run dmstr/yii2-app yii


## Resources
    
- [Yii 2.0 Framework guide](http://www.yiiframework.com/doc-2.0/guide-index.html)
- [Docker documentation](https://docs.docker.com)

