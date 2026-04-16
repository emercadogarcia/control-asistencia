#!/bin/bash
set -e

echo "🚀 Iniciando servicios para el Sistema de Asistencia..."

# En lugar de esperar a un host 'db' local, solo verificamos si hay conexión 
# si decides usar una DB local. Para Supabase, Laravel lo gestionará al conectar.

# Iniciar Laravel server en segundo plano
php artisan serve --host=0.0.0.0 --port=8000 &
LARAVEL_PID=$!

# Iniciar Vite (Frontend Vue 3) en segundo plano
npm run dev &
VITE_PID=$!

echo "✅ Servidores listos para el desarrollo."
wait