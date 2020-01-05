FROM shrikeh/php7.4-base-dev:latest as phpspec

ENV XDEBUG_FILTER="./build/xdebug-filter.php"

WORKDIR /code
ENTRYPOINT php ./vendor/bin/phpspec run tests/spec

