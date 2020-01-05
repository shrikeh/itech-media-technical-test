ARG PHP_TAG="7.4.1-fpm-alpine3.11"

FROM php:${PHP_TAG} as base

RUN apk add rabbitmq-c rabbitmq-c-dev
RUN apk add --no-cache $PHPIZE_DEPS && pecl install amqp ds && docker-php-ext-enable amqp ds

COPY ./tools/docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY ./tools/docker/php-fpm/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

