FROM php:7.2-fpm-alpine

ARG UID=1001
ARG GID=1001

RUN apk --no-cache add shadow && \
  usermod -u $UID www-data && \
  groupmod -g $GID www-data

RUN set -ex && apk --no-cache add postgresql-dev
RUN docker-php-ext-install pgsql pdo_pgsql && \
  docker-php-ext-enable pgsql pdo_pgsql