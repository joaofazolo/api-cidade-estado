FROM php:7-alpine

RUN apk add build-base
RUN apk add autoconf
RUN apk add curl-dev openssl-dev
RUN pecl install mongodb