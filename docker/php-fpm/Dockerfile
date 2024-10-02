FROM php:8.3-fpm-alpine

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash
RUN apk add --update linux-headers

# Xdebug
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
      pecl install xdebug && docker-php-ext-enable xdebug; \
    fi;

COPY ./docker/php-fpm/php.ini /usr/local/etc/php/
COPY ./docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Postgres
RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_pgsql
RUN apk add --update sudo

# Nginx
RUN apk add --update --no-cache nginx
COPY ./docker/php-fpm/nginx.conf /etc/nginx/

# Supervisor
RUN apk add --no-cache supervisor
COPY ./docker/php-fpm/supervisord.conf /etc/supervisord.conf

# Source code
COPY ./ /var/www

WORKDIR /var/www

RUN if [ ! -f .env ]; then touch .env; fi

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ARG BUILD_MODE=dev
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN if [ "${BUILD_MODE}" = "dev" ]; then \
      composer install --no-interaction; \
    else \
      composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader; \
    fi


EXPOSE 8080

CMD ["/bin/sh", "./docker/php-fpm/run.sh"]