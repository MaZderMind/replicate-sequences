FROM php:7.0-apache

RUN docker-php-ext-install -j$(nproc) pdo_mysql

COPY *.php *.sql /var/www/html/
COPY conf.php.env /var/www/html/conf.php

CMD ["sh", "-c", "while true; do php ./update.php; sleep 120; done"]
