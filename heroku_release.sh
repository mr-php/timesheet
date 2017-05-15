#!/usr/bin/env bash
#initially tried in Procfile release:
#did not work because it replaced PHP default build-pack
#which does many things including populated env files and composer
composer install
php yii migrate --interactive=0
vendor/bin/heroku-php-apache2 web/