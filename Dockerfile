FROM php:8.2-cli
RUN mkdir -p /var/www/html/code
RUN chmod +x /var/www/html/code
COPY /code /var/www/html/code
WORKDIR /var/www/html/app
CMD [ "php", "./code/index.php" ]