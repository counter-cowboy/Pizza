FROM php:8.2.1-fpm

RUN apt-get -y update && apt-get -y upgrade
RUN apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip unzip \
    git \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN pecl install xdebug-3.2.0 && docker-php-ext-enable xdebug

#xDebug
COPY docker/php/conf.d/* $PHP_INI_DIR/conf.d/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY ../../src /var/www/html

RUN composer install
RUN composer --version
#RUN pecl install redis && docker-php-ext-enable redis




RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

#RUN php artisan migrate --database=mysql && php artisan db:seed --database=mysql
#
#RUN php artisan migrate --database=mysql_test && php artisan db:seed --database=mysql_test


CMD ["php-fpm"]

