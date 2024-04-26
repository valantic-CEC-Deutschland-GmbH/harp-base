ARG RR_VERSION
ARG RR_IMAGE=ghcr.io/roadrunner-server/roadrunner:${RR_VERSION}
ARG PHP_IMAGE_VERSION
ARG PHP_IMAGE=php:${PHP_IMAGE_VERSION}

FROM ${RR_IMAGE} as rr

FROM ${PHP_IMAGE}

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y \
  vim \
  zlib1g-dev \
  libzip-dev \
  libcurl4-openssl-dev \
  libonig-dev \
  unzip \
  bash

# Install PHP Extensions
RUN docker-php-ext-install zip \
  && docker-php-ext-install sockets \
  && docker-php-ext-install opcache \
  && docker-php-ext-enable opcache

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy RoadRunner
COPY --from=rr /usr/bin/rr /usr/bin/rr

# Copy RoadRunner config
COPY --from=rr /etc/rr.yaml /etc/rr.yaml

CMD ["/usr/bin/rr", "serve", "-d", "-c", "/etc/rr.yaml"]
