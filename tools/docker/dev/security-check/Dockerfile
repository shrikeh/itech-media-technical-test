FROM shrikeh/php7.4-base-dev:latest as security-check

WORKDIR /security-check

ENTRYPOINT ["./application/bin/symfony", "security:check"]
