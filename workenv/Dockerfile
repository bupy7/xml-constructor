FROM php:8.3-cli-alpine

RUN apk add --no-cache $PHPIZE_DEPS && apk add --update --no-cache linux-headers

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN rm -f /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY 50_xdebug.ini /usr/local/etc/php/conf.d/

RUN curl -sS https://getcomposer.org/installer | php -- --2 --install-dir=/usr/bin --filename=composer
ARG GITHUB_OAUTH_TOKEN=false
RUN if [ ${GITHUB_OAUTH_TOKEN} != false ]; then \
  composer config -g github-oauth.github.com ${GITHUB_OAUTH_TOKEN} \
;fi
RUN export COMPOSER_DISABLE_XDEBUG_WARN=1

RUN composer global require friendsofphp/php-cs-fixer "^3.43.1"

CMD ["php", "-a"]
