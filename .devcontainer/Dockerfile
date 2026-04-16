FROM php:8.3-fpm
# Dependencias del sistema
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Extensiones PHP (Corregido: ctype y json ya vienen en el core de PHP 8.3)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar dependencias para PostgreSQL y herramientas de red
RUN apt-get update && apt-get install -y libpq-dev postgresql-client \
    && docker-php-ext-install pdo_pgsql
    
# Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Composer (Corregido el comando de instalación) [cite: 3]
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js 20 [cite: 4]
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /workspace

EXPOSE 8000 5173 9003
