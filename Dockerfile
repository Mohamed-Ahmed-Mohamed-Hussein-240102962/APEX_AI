FROM php:8.3-fpm

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY ./src/composer.json ./src/composer.lock ./

COPY ./src .

RUN composer install --no-interaction 

