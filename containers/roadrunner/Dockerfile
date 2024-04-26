ARG RR_VERSION
ARG RR_IMAGE=ghcr.io/roadrunner-server/roadrunner:${RR_VERSION}
ARG PHP_IMAGE_VERSION
ARG PHP_IMAGE=php:${PHP_IMAGE_VERSION}

FROM ${RR_IMAGE} as rr

FROM ${PHP_IMAGE} AS base

RUN \
  apt-get update \
  && apt-get install --assume-yes --no-install-recommends libzip-dev
#   zlib1g-dev \
#    \
#   libcurl4-openssl-dev \
#   libonig-dev \
#   unzip \
#   bash

# Install PHP Extensions
RUN \
  docker-php-ext-install sockets \
  && docker-php-ext-install zip

# Copy RoadRunner
COPY --from=rr /usr/bin/rr /usr/bin/rr

# Install Symfony CLI.
# See https://symfony.com/download.
RUN \
  curl -sS https://get.symfony.com/cli/installer | bash && \
  mv /root/.symfony5/bin/symfony /usr/local/bin/symfony


FROM base AS development

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY config/.rr.dev.yaml /etc/rr.yaml

CMD ["/usr/bin/rr", "serve", "-d", "-c", "/etc/rr.yaml"]


FROM base AS production

COPY config/.rr.yaml /etc/rr.yaml

CMD ["/usr/bin/rr", "serve", "-d", "-c", "/etc/rr.yaml"]
