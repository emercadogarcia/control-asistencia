# DevContainer Setup - Control de Asistencia

## 🚀 Instalación Automática

Cuando abres el proyecto con DevContainer, se ejecutan automáticamente:

1. **postCreateCommand.sh** - Instala todas las dependencias:
   - ✅ Composer (dependencias PHP)
   - ✅ npm (dependencias JavaScript)
   - ✅ Genera `.env` si no existe
   - ✅ Genera clave de aplicación Laravel
   - ✅ Crea directorios necesarios
   - ✅ Limpia cachés

## 📦 Contenedor Incluye

### Aplicación
- **PHP 8.3-FPM** con extensiones para Laravel:
  - bcmath, ctype, fileinfo, json, mbstring
  - pdo, pdo_mysql, tokenizer, xml
- **Composer** - Gestor de dependencias PHP
- **Node.js 20** con npm
- **Xdebug** - Debugger para PHP (puerto 9003)

### Base de Datos
- **Supabase PostgreSQL** (backend remoto)
  - Configurado en `.env` con variables de conexión

## 🔧 Flujo de Inicio

### 1️⃣ Primer Inicio (Automático)
Al abrir con DevContainer:
- Se construye la imagen Docker
- Se ejecuta postCreateCommand.sh
- Se instalan todas las dependencias
- Se crea .env (edita con credenciales de Supabase)
- Se genera APP_KEY

### 2️⃣ Configurar Credenciales Supabase
Edita `.env` y asegúrate que tiene:
```env
DB_CONNECTION=pgsql
DB_HOST=tu_supabase_host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=tu_password_supabase
```

### 3️⃣ Ejecutar Migraciones
```bash
php artisan migrate
```

### 4️⃣ Iniciar Servidor (Terminal 1)
```bash
php artisan serve
```

### 5️⃣ Iniciar Vite (Terminal 2)
```bash
npm run dev
```

## 📝 Comandos Útiles

```bash
# Laravel
php artisan tinker              # PHP REPL
php artisan migrate             # Migraciones
php artisan migrate:fresh       # Resetear BD
php artisan cache:clear        # Limpiar caché
php artisan route:list         # Ver rutas
php artisan db:seed            # Llenar BD con datos

# Node/NPM
npm install                     # Instalar dependencias
npm run dev                     # Vite dev
npm run build                   # Compilar producción
npm run preview                 # Preview producción

# Docker
docker-compose logs -f          # Ver logs
docker-compose ps               # Ver servicios
docker-compose restart          # Reiniciar
```

## 🌐 Puertos

| Puerto | Servicio | URL |
|--------|----------|-----|
| 8000 | Laravel | http://localhost:8000 |
| 5173 | Vite Dev | http://localhost:5173 |
| 9003 | Xdebug | - |

## 🗄️ Base de Datos (Supabase)

La base de datos está alojada en Supabase:
- **Host**: Tu proyecto Supabase host
- **Puerto**: `5432`
- **Base de datos**: `postgres`
- **Usuario**: `postgres`
- **Contraseña**: Tu contraseña de Supabase

⚠️ **Importante**: Edita `.env` con tus credenciales reales de Supabase después de que se cree.
Xdebug está preconfigurado y escucha en puerto 9003.

### Configurar en VS Code:
1. Instala extensión "PHP Debug"
2. Abre `.vscode/launch.json` o crea uno:

```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type": "php",
      "port": 9003,
      "pathMapping": {
        "/workspace": "${workspaceFolder}"
      }
    }
  ]
}
```

3. Presiona F5 para iniciar el debugger
4. Coloca breakpoints en tu código

## 🔄 Reiniciar el Contenedor

```bash
# Desde la terminal del devcontainer
docker-compose down
docker-compose up -d
```

O usa el menú de VS Code: **Dev Containers: Rebuild Container**

## ⚠️ Solución de Problemas

### PostgreSQL no está disponible
```bash
docker-compose logs db
```

### Errores de permisos en storage
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs
```

### Limpiar todo
```bash
docker-compose down -v
docker-compose up -d
```

### Composer fuera de memoria
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

## ✅ Checklist Inicial

- [ ] DevContainer se abrió correctamente
- [ ] postCreateCommand.sh se ejecutó sin errores
- [ ] Editaste `.env` con credenciales de Supabase
- [ ] Puedes conectar a Supabase (`php artisan tinker`)
- [ ] PHP server inicia (`php artisan serve`)
- [ ] Vite inicia (`npm run dev`)
- [ ] Puedes acceder a http://localhost:8000
- [ ] Las migraciones funcionan (`php artisan migrate`)

¡Todo listo! 🎉
