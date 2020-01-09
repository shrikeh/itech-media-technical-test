FROM shrikeh/php7.4-base-dev:latest as security-check

WORKDIR /code

ENTRYPOINT ["./application/bin/symfony", "security:check"]
