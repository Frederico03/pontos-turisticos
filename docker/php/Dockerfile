# syntax=docker/dockerfile:1.4
FROM php:8.4-fpm

# Instalar dependências do sistema (Debian usa apt)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libpng-dev \
    libxml2-dev \
    libonig-dev \
    supervisor \
    autoconf \
    g++ \
    make \
    rsync \
    && apt-get clean && rm -rf /var/lib/apt/lists/*


# Instalar extensões PHP nativas
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Instalar extensões PECL
RUN pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis

# Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar configurações customizadas
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html
