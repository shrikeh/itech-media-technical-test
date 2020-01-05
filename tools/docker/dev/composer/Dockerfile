FROM shrikeh/php7.4-base-dev:latest as composer

WORKDIR /bin
COPY ./tools/bin/install-composer.sh .

RUN sh ./install-composer.sh

WORKDIR /code

CMD php /bin/composer.phar install --optimize-autoloader
