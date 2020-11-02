FROM php:7-alpine

RUN apk add build-base && \
    apk add autoconf && \
    apk add curl-dev openssl-dev && \
    pecl install mongodb && \
    touch /usr/local/etc/php/conf.d/php-mongodb.ini && \
    echo "extension=mongodb.so" > '/usr/local/etc/php/conf.d/php-mongodb.ini'