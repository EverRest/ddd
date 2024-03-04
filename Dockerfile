FROM php:8.2-fpm-alpine

WORKDIR "/var/www"

ARG DEBIAN_FRONTEND=noninteractive

RUN apk --no-cache add \
    postgresql-dev \
    libzip-dev \
    libpng \
    libpng-dev \
    oniguruma-dev \
    freetype \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    libwebp \
    libwebp-dev \
    zlib-dev \
    nginx \
    nodejs \
    npm \
    autoconf \
    g++ \
    make \
    pkgconfig

RUN docker-php-ext-install zip exif gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk --no-cache add \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    zlib-dev \
    && npm install -g npm

RUN apk --no-cache add \
    build-base \
    git \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del build-base git

COPY ../.. /var/www

EXPOSE 9000
