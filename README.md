# 📋 Sistema de Control de Asistencia Laboral

## Descripción

Sistema integral de control de asistencia laboral construido con **Laravel 11**, **Vue 3**, **Inertia.js** en el frontend y **Supabase** como backend.

## Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| **Frontend** | Laravel 11, Inertia.js, Vue 3, Tailwind CSS, Vite |
| **Backend** | Supabase (PostgreSQL) |
| **DevContainer** | Docker, PHP 8.3, Node.js 20 |

## 🚀 Instalación Rápida

### 1. Clonar el proyecto
```bash
cd /ruta/del/proyecto
```

### 2. Abrir en DevContainer (VS Code)
- Presiona `Ctrl+Shift+P`
- Busca "Remote Containers: Reopen in Container"

### 3. Instalar dependencias
```bash
composer install
npm install
php artisan key:generate
```

### 4. Configurar BD
Copia el contenido de `database/schema.sql` y ejecuta en Supabase SQL Editor

### 5. Ejecutar
```bash
# Terminal 1
php artisan serve --host=0.0.0.0

# Terminal 2
npm run dev
```

Accede a: **http://localhost:8000**

## 📁 Estructura

```
control-asistencia/
├── .devcontainer/          # Docker
├── app/                    # Backend
├── resources/js            # Frontend Vue
├── database/               # Schema SQL
├── routes/                 # Rutas
└── public/                 # Assets públicos
```

## 📊 Módulos

1. **Dashboard** - Estadísticas en tiempo real
2. **Asistencia** - Registro entrada/salida
3. **Personal** - Gestión de empleados
4. **Configuración** - Sucursales, turnos, feriados

## 🔐 Características de Seguridad

- Row Level Security (RLS)
- Validaciones en BD
- Auditoría completa
- Soft deletes

---

**¡Proyecto listo para usar! 🎉**
