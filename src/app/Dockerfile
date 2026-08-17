FROM  php:8.3-fpm

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /user/bin/composer

RUN docker-php-ext-install pdo_mysql

COPY  ./src/composer.json ./src/composer ./

RUN composer install --no-interaction 

COPY ./src .

CMD ["php:fpm"]