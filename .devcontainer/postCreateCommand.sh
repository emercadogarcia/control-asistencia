#!/bin/bash

set -e

echo "🚀 Iniciando configuración del devcontainer..."

# 1. Configuración de seguridad para Git
git config --global --add safe.directory /workspace

# 2. ESPERA ACTIVA: Esto soluciona el desfase de montaje del volumen
echo "⏳ Esperando a que el sistema de archivos esté disponible..."
while [ ! -f "/workspace/artisan" ]; do
  sleep 1
done

# Navegar al directorio del workspace
cd /workspace

# --- MEJORA 3: PERMISOS Y FORMATO DE ARTISAN (Crucial para estabilidad) ---
echo "🛠️  Corrigiendo formato y permisos de Artisan..."
sed -i 's/\r$//' artisan
chmod +x artisan

# Crear directorio de caché de composer
mkdir -p /root/.composer

# Mostrar información del entorno
echo "ℹ️  Información del entorno:"
echo "   PHP: $(php -v | head -1)"
echo "   Composer: $(composer --version)"
echo "   Node: $(node -v)"
echo "   npm: $(npm -v)"
echo ""

# Instalar dependencias de PHP con Composer
echo "📥 Instalando dependencias de Composer..."
if [ -f "composer.json" ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
    composer dump-autoload --optimize --no-interaction
    echo "✅ Dependencias de Composer instaladas"
else
    echo "⚠️ No se encontró composer.json"
fi

# Instalar dependencias de Node.js
echo "📥 Instalando dependencias de npm..."
if [ -f "package.json" ]; then
    npm install --legacy-peer-deps
    
    # --- MEJORA 2: SOPORTE ESM PARA VITE (Evita error de require) ---
    echo "📦 Configurando soporte ESM para el Frontend..."
    sed -i 's/"private": true,/"private": true,\n    "type": "module",/' package.json
    [ -f vite.config.js ] && mv vite.config.js vite.config.mjs || true
    
    echo "✅ Dependencias y módulos de npm configurados"
else
    echo "⚠️ No se encontró package.json"
fi

# Crear archivo .env si no existe
if [ ! -f ".env" ]; then
    echo "🔧 Creando archivo .env..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "✅ Archivo .env creado desde .env.example"
    else
        cat > .env << 'EOF'
APP_NAME="Control de Asistencia"
APP_ENV=local
APP_DEBUG=true
APP_KEY=
APP_URL=http://localhost:8000
APP_TIMEZONE=America/La_Paz

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=asistencia
DB_USERNAME=postgres
DB_PASSWORD=postgres

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

VITE_API_BASE_URL=http://localhost:8000
EOF
        echo "✅ Archivo .env creado con valores por defecto"
    fi
fi

# Generar clave de aplicación si no existe o está vacía
echo "🔑 Configurando clave de aplicación..."
if ! grep -q "APP_KEY=base64:" .env || grep -q "APP_KEY=$" .env; then
    php artisan key:generate --no-interaction
    echo "✅ Clave de aplicación generada"
fi

# Crear directorios de almacenamiento
echo "📁 Creando directorios necesarios..."
mkdir -p storage/app storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs
echo "✅ Directorios creados"

# Limpiar caché
echo "🧹 Limpiando cachés..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
echo "✅ Cachés limpios"

echo ""
echo "════════════════════════════════════════════════════════════════"
echo "✅ ¡Configuración completada para Edgar Mercado!"
echo "════════════════════════════════════════════════════════════════"
echo ""
echo "📝 Próximos pasos:"
echo "  1. ✅ PHP, Node, Composer y npm configurados"
echo "  2. ✅ Dependencias instaladas"
echo "  3. ✅ Vite configurado como Módulo (.mjs)"
echo "  4. Asegúrate de que .env tiene las credenciales de Supabase"
echo "  5. Ejecuta: php artisan migrate (si es necesario) y php artisan key:generate"
echo "  6. Terminal 1: php artisan serve --host=0.0.0.0 --port=8000"
echo "  7. Terminal 2: npm run dev"
echo ""