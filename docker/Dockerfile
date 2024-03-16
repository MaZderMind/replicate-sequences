FROM php:8.3-apache

RUN docker-php-ext-install -j$(nproc) pdo_mysql

RUN apt-get update && apt-get install -y \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

COPY *.php /var/www/html/
COPY struct.sql /var/www/html/
COPY conf.php.env /var/www/html/conf.php
COPY docker/*.sh /

CMD ["sh", "/startup.sh"]
