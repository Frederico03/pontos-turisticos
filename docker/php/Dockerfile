# syntax=docker/dockerfile:1.4
FROM php:8.4-fpm-alpine

# Instalar dependências do sistema (Alpine usa apk)
RUN --mount=type=cache,target=/var/cache/apk,sharing=locked \
    apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    postgresql-dev \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    supervisor \
    # Dependências para compilação (serão necessárias)
    autoconf \
    g++ \
    make \
    linux-headers

# Instalar extensões PHP nativas (aproveita cache)
RUN --mount=type=cache,target=/tmp/pear \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Instalar extensões PECL (aproveita cache)
RUN --mount=type=cache,target=/tmp/pear \
    pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis

# Copiar Composer (camada leve)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar configurações customizadas (alterações aqui não refazem layers acima)
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html
