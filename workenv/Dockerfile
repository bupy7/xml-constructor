FROM php:8.4-cli-alpine

ARG GITHUB_OAUTH_TOKEN=""
ARG USER_UID=""
ARG USER_GID=""

# common
# ---
RUN apk add --no-cache $PHPIZE_DEPS && apk add --update --no-cache linux-headers

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY xdebug.ini /usr/local/etc/php/conf.d/zxy-50_xdebug.ini

# new user
# ---
RUN addgroup -g $USER_GID php-cli \
    && adduser -u $USER_UID -G php-cli -s /bin/sh -D php-cli

# login as new user
# ---
USER php-cli

# env's
# ---
ENV PATH="/home/php-cli/.local/bin:/home/php-cli/.composer/vendor/bin:${PATH}"

RUN mkdir -p /home/php-cli/.local/bin && \
    curl -sS https://getcomposer.org/installer | \
    /usr/local/bin/php -- --2 --install-dir=/home/php-cli/.local/bin --filename=composer

# composer
# ---
RUN if [ -n ${GITHUB_OAUTH_TOKEN} ]; then \
  composer config -g github-oauth.github.com ${GITHUB_OAUTH_TOKEN} \
;fi

# other tools
# ---
RUN composer global require --dev friendsofphp/php-cs-fixer "^3.65.0"

CMD ["php", "-a"]
