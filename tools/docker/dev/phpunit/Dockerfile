FROM shrikeh/php7.4-base-dev:latest as phpunit

ENV XDEBUG_FILTER="./build/xdebug-filter.php"

WORKDIR /code
ENTRYPOINT php ./vendor/bin/phpunit --prepend ${XDEBUG_FILTER} --configuration ./phpunit.xml --testsuite 'Technical Test Unit Tests'
