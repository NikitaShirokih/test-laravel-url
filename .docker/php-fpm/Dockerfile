FROM php:8.3-fpm-alpine

ARG UID=1000
ARG GID=1000

WORKDIR /var/www/app

RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    mysql-client \
    nodejs \
    npm \
    linux-headers

RUN addgroup -g "${GID}" app \
    && adduser -D -G app -u "${UID}" app \
    && sed -i 's/^user = .*/user = app/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/^group = .*/group = app/' /usr/local/etc/php-fpm.d/www.conf

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    intl \
    zip \
    bcmath \
    pcntl \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY .docker/php-fpm/php.ini /usr/local/etc/php/conf.d/custom.ini

CMD ["php-fpm"]
