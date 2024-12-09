FROM php:8.2.1-fpm

RUN apt-get -y update && apt-get -y upgrade
#RUN apt-get install -y \
#    libpq-dev
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN pecl install xdebug-3.2.0 && docker-php-ext-enable xdebug

COPY docker/php/conf.d/* $PHP_INI_DIR/conf.d/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#COPY ../../src /var/www/html
#RUN composer install

#RUN pecl install redis && docker-php-ext-enable redis
#WORKDIR /var/www/html
#RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php-fpm"]

