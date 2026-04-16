#!/bin/bash

# Script de utilidades para desarrollo
set -e

WORKSPACE="/workspace"
cd "$WORKSPACE"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

if [ -z "$1" ]; then
    echo -e "${BLUE}════════════════════════════════════════════════════════════════${NC}"
    echo "🚀 Control de Asistencia - Script de Desarrollo"
    echo -e "${BLUE}════════════════════════════════════════════════════════════════${NC}"
    echo ""
    echo "Comandos disponibles:"
    echo ""
    echo -e "${GREEN}Setup:${NC}"
    echo "  install         Instalar dependencias"
    echo "  setup           Ejecutar setup completo"
    echo ""
    echo -e "${GREEN}Base de Datos:${NC}"
    echo "  migrate         Ejecutar migraciones"
    echo "  seed            Ejecutar seeders"
    echo ""
    echo -e "${GREEN}Servidor:${NC}"
    echo "  serve           Iniciar Laravel (8000)"
    echo "  vite            Iniciar Vite (5173)"
    echo ""
    echo -e "${GREEN}Utilidades:${NC}"
    echo "  clean           Limpiar cachés"
    echo "  tinker          PHP REPL"
    echo ""
    exit 0
fi

case "$1" in
    install)
        composer install && npm install
        ;;
    setup)
        bash .devcontainer/post-create.sh
        ;;
    migrate)
        php artisan migrate
        ;;
    seed)
        php artisan db:seed
        ;;
    serve)
        php artisan serve --host=0.0.0.0
        ;;
    vite)
        npm run dev
        ;;
    clean)
        php artisan config:clear
        php artisan cache:clear
        php artisan view:clear
        echo -e "${GREEN}✅ Cachés limpios${NC}"
        ;;
    tinker)
        php artisan tinker
        ;;
    *)
        echo -e "${RED}Comando desconocido: $1${NC}"
        exit 1
        ;;
esac
