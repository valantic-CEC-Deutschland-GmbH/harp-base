ARG RR_VERSION
ARG RR_IMAGE=ghcr.io/roadrunner-server/roadrunner:${RR_VERSION}
ARG PHP_IMAGE_VERSION
ARG PHP_IMAGE=php:${PHP_IMAGE_VERSION}


FROM ${RR_IMAGE} as rr


FROM ${PHP_IMAGE} AS base

RUN \
  apt-get update \
  && apt-get install --assume-yes --no-install-recommends bash libzip-dev unzip

RUN \
  docker-php-ext-install opcache \
  && docker-php-ext-enable opcache \
  && docker-php-ext-install sockets \
  && docker-php-ext-install zip

COPY --from=rr         /usr/bin/rr             /usr/bin/rr
COPY --from=composer:2 /usr/bin/composer       /usr/bin/composer

# Install Symfony CLI.
# See https://symfony.com/download.
RUN \
  curl -sS https://get.symfony.com/cli/installer | bash && \
  mv /root/.symfony5/bin/symfony /usr/local/bin/symfony


FROM base AS development

RUN \
  pecl install xdebug \
  && docker-php-ext-enable xdebug

COPY config/usr/local/etc/php/conf.d/99-valantic.ini /usr/local/etc/php/conf.d/99-valantic.ini
COPY config/.rr.dev.yaml                             /etc/rr.yaml

CMD ["/usr/bin/rr", "serve", "-d", "-c", "/etc/rr.yaml"]


FROM base AS production

COPY config/.rr.yaml /etc/rr.yaml

CMD ["/usr/bin/rr", "serve", "-d", "-c", "/etc/rr.yaml"]
