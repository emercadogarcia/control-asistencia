#!/bin/bash

# Script de Prueba del Sistema de Control de Asistencia
# Este script prueba los endpoints principales del sistema

echo "🔧 Iniciando pruebas del Sistema de Control de Asistencia..."
echo ""

# Variables
BASE_URL="http://localhost:8000"
EMAIL="admin@asistencia.local"
PASSWORD="demo@123" # Cambiar según tu contraseña en Supabase

# Color para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}1. Probando acceso al Login...${NC}"
curl -s -o /dev/null -w "Status: %{http_code}\n" "$BASE_URL/login"
echo ""

echo -e "${YELLOW}2. Probando Login API...${NC}"
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/api/login" \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"$EMAIL\",\"password\":\"$PASSWORD\"}")

echo "Respuesta: $LOGIN_RESPONSE"
TOKEN=$(echo $LOGIN_RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)
echo "Token obtenido: ${TOKEN:0:20}..."
echo ""

if [ -z "$TOKEN" ]; then
  echo -e "${RED}❌ Error: No se pudo obtener token. Verifica credenciales.${NC}"
  exit 1
fi

echo -e "${GREEN}✓ Login exitoso${NC}"
echo ""

echo -e "${YELLOW}3. Probando Dashboard...${NC}"
curl -s -o /dev/null -w "Status: %{http_code}\n" "$BASE_URL/dashboard"
echo ""

echo -e "${YELLOW}4. Probando obtención de datos del usuario...${NC}"
curl -s -H "Authorization: Bearer $TOKEN" "$BASE_URL/api/user"
echo ""
echo ""

echo -e "${YELLOW}5. Probando búsqueda de personal...${NC}"
curl -s -H "Authorization: Bearer $TOKEN" "$BASE_URL/asistencia/buscar-personal?ci=1234567890"
echo ""
echo ""

echo -e "${GREEN}✓ Pruebas completadas${NC}"
echo ""
echo "Accede a: $BASE_URL"
echo "Email: $EMAIL"
