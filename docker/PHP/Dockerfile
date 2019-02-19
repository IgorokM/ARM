from php:7.3-fpm
copy ./php.ini /usr/local/etc/php
run apt-get update && apt-get install -y \
    && docker-php-ext-install \
    pdo \
    pdo_mysql