FROM php:8.3-cli

RUN apt-get update && apt-get install -y unzip curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN pecl install redis && docker-php-ext-enable redis

RUN apt-get install -y libssl-dev pkg-config
RUN pecl install mongodb && docker-php-ext-enable mongodb

WORKDIR /var/www/html

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html/public"]
