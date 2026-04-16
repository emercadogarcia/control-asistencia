#!/bin/bash

# Script para ejecutar Laravel dev en el contenedor

# Esperar a que PostgreSQL esté disponible
echo "⏳ Esperando a PostgreSQL..."
until pg_isready -h db -U postgres >/dev/null 2>&1; do
  sleep 2
done
echo "✅ PostgreSQL disponible"

# Ejecutar migraciones si no existen
echo "🗄️  Ejecutando migraciones..."
php artisan migrate --force 2>/dev/null || true

# Iniciar servicios en paralelo
echo "🚀 Iniciando servicios..."

# PHP-FPM ya está ejecutándose

# Iniciar Laravel server
echo "📦 Iniciando Laravel server en puerto 8000..."
php artisan serve --host=0.0.0.0 --port=8000 &
LARAVEL_PID=$!

# Iniciar Vite server
echo "⚡ Iniciando Vite en puerto 5173..."
npm run dev &
VITE_PID=$!

echo ""
echo "✅ Servidores iniciados:"
echo "   Laravel: http://localhost:8000"
echo "   Vite: http://localhost:5173"
echo ""

# Mantener los procesos activos
wait
