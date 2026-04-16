#!/bin/bash

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate --force

echo "✓ Development environment initialized successfully"
