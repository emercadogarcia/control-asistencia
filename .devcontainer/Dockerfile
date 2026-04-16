FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    curl git zip unzip postgresql-client libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

WORKDIR /workspace
EXPOSE 8000 5173
