FROM php:8.1-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --2 --install-dir=/usr/bin --filename=composer

RUN export COMPOSER_DISABLE_XDEBUG_WARN=1

CMD ["php", "-a"]
