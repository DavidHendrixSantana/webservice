FROM php:8.2-cli
RUN mkdir -p /var/www/html/code
RUN chmod +x /var/www/html/code
COPY /code /var/www/html/code
WORKDIR /var/www/html/code

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
