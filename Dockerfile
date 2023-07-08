FROM composer:latest as setup

RUN mkdir /guzzle

WORKDIR /guzzle

RUN set -xe \
    && composer init --name=guzzlehttp/test --description="Simple project for testing Guzzle scripts" --author="Márk Sági-Kazár <mark.sagikazar@gmail.com>" --no-interaction \
    && composer require guzzlehttp/guzzle:^5.0

FROM spittet/php-mysql

RUN mkdir -p /var/www/html/code
RUN chmod +x /var/www/html/code
COPY /code /var/www/html/code

RUN mkdir -p /var/www/html/code/guzzle

WORKDIR /guzzle

COPY --from=setup /guzzle /var/www/html/code/guzzle


WORKDIR /var/www/html/code
