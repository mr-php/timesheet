FROM dmstr/php-yii2:7.1-fpm-3.0-beta2-alpine-nginx

WORKDIR /app

COPY composer.lock composer.json /app/
RUN composer install --prefer-dist --optimize-autoloader

COPY yii /app/
COPY ./web /app/web/
COPY ./src /app/src/
COPY ./src/app.env-dist /app/src/app.env

RUN mkdir -p runtime web/assets && \
    chmod -R 775 runtime web/assets && \
    chown -R www-data:www-data runtime web/assets
