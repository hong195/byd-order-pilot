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
RUN apk add --update sudo

# Configure non-root user.
ARG PUID=1000
ARG PGID=1000
ARG USER=www-data

RUN set -x \
    && addgroup -g ${PGID} $USER || true \
    && adduser -D -u ${PUID} -G $USER $USER || true \
    && echo '$USER ALL=(ALL) NOPASSWD: ALL' >> /etc/sudoers.d/$USER \
    && chmod 0440 /etc/sudoers.d/$USER


# Nginx
RUN apk add --update --no-cache nginx
COPY ./docker/php-fpm/nginx.conf /etc/nginx/
RUN chown -R $USER:$USER /var/lib/nginx
RUN chown -R $USER:$USER /var/log/nginx
RUN chown -R $USER:$USER /usr/lib/nginx/modules
RUN chown -R $USER:$USER /run/nginx
RUN touch /run/nginx.pid
RUN chown $USER:$USER /run/nginx.pid

# Supervisor
RUN apk add --no-cache supervisor
COPY ./docker/php-fpm/supervisord.conf /etc/supervisord.conf

# Source code
COPY ./ /var/www

USER $USER
WORKDIR /var/www

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ARG BUILD_MODE=dev
RUN if [ "${BUILD_MODE}" = "dev" ]; then \
      composer install --no-interaction; \
    else \
      composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader; \
    fi
RUN chown -R $USER:$USER /var/www

EXPOSE 8080

CMD ["/bin/sh", "./docker/php-fpm/run.sh"]


