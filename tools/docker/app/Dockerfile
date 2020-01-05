FROM shrikeh/php7.4-base:latest as build-app

ARG COMPOSER_BUILD='--no-dev'

RUN apk add --no-cache git
WORKDIR /bin

COPY ./tools/bin/install-composer.sh .

RUN sh ./install-composer.sh

WORKDIR /build
COPY ./.env.dist .
COPY ./composer.json .
COPY ./composer.lock .
COPY ./application ./application
RUN php /bin/composer.phar install ${COMPOSER_BUILD} --classmap-authoritative

FROM shrikeh/php7.4-base:latest as app

WORKDIR /code

COPY --from=build-app /build .

ENTRYPOINT ["php", "application/bin/console", "shrikeh:helloworld"]
