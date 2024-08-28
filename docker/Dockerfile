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

COPY ./docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Postgres
RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_pgsql

# Configure non-root user.
ARG PUID=1000
ARG PGID=1000

RUN set -x \
    && addgroup -g ${PGID} www-data || true \
    && adduser -D -u ${PUID} -G www-data www-data || true

# Nginx
RUN apk add --update --no-cache nginx
COPY ./docker/php-fpm/nginx.conf /etc/nginx/

RUN mkdir -p /var/lib/nginx/tmp /var/log/nginx \
    && chown -R www-data:www-data /var/lib/nginx /var/log/nginx \
    && chmod -R 755 /var/lib/nginx /var/log/nginx

# Supervisor
RUN apk add --no-cache supervisor
COPY ./docker/php-fpm/supervisord.conf /etc/supervisord.conf

# Source code
COPY --chown=www-data:www-data ./ /var/www
RUN mkdir /var/www/var
RUN chown -R www-data:www-data /var/www/var
WORKDIR /var/www

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ARG BUILD_MODE=dev
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN if [ "${BUILD_MODE}" = "dev" ]; then \
      composer install --no-interaction; \
    else \
      composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader; \
    fi

RUN chown -R www-data:www-data /var/www/vendor/

EXPOSE 8080
 
CMD ["/bin/sh", "./docker/php-fpm/run.sh"]