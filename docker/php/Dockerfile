FROM php:8.0-fpm-alpine

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && apk add --no-cache libpng  freetype-dev libjpeg-turbo-dev libpng-dev \
  && apk del pcre-dev ${PHPIZE_DEPS}

WORKDIR /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
