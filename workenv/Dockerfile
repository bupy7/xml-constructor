FROM php:8.1-cli-alpine

RUN apk add --no-cache $PHPIZE_DEPS

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN rm -f /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY 50_xdebug.ini /usr/local/etc/php/conf.d/

RUN curl -sS https://getcomposer.org/installer | php -- --2 --install-dir=/usr/bin --filename=composer

RUN export COMPOSER_DISABLE_XDEBUG_WARN=1

CMD ["php", "-a"]
