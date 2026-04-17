FROM php:8.3-fpm

# Dependencias del sistema
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    libpq-dev postgresql-client \
    && rm -rf /var/lib/apt/lists/*

# Extensiones PHP para MySQL y PostgreSQL (Supabase)
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Instalar dependencias para PostgreSQL y herramientas de red
RUN apt-get update && apt-get install -y libpq-dev postgresql-client \
    && docker-php-ext-install pdo_pgsql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /workspace

EXPOSE 8000 5173 9003

