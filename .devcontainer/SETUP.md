# DevContainer Setup - Control de Asistencia

## Instalación Automática ✅

Cuando abres el proyecto con DevContainer, se ejecutan automáticamente:

1. **post-create.sh** - Instala todas las dependencias:
   - ✅ Composer (dependencias PHP)
   - ✅ npm (dependencias JavaScript)
   - ✅ Genera `.env` si no existe
   - ✅ Genera clave de aplicación Laravel
   - ✅ Crea directorios necesarios
   - ✅ Limpia cachés

## Contenedor Incluye

- **PHP 8.3** con todas las extensiones para Laravel
- **Composer** - Gestor de dependencias PHP
- **Node.js 20** con npm y pnpm
- **PostgreSQL 15** - Base de datos
- **Xdebug** - Debugger para PHP

## Próximos Pasos

### 1. Esperar a PostgreSQL
Los logs mostrarán cuando esté listo

### 2. Ejecutar Migraciones
```bash
php artisan migrate
```

### 3. Iniciar Servidor (Terminal 1)
```bash
php artisan serve
```

### 4. Iniciar Vite (Terminal 2)
```bash
npm run dev
```

## Comandos Útiles

```bash
php artisan tinker              # PHP REPL
php artisan migrate             # Migraciones
php artisan cache:clear        # Limpiar caché
php artisan route:list         # Ver rutas

npm install                     # Instalar dependencias
npm run dev                     # Vite dev
npm run build                   # Compilar producción
```

## Puertos

| Puerto | Servicio |
|--------|----------|
| 8000 | Laravel |
| 5173 | Vite |
| 5432 | PostgreSQL |
| 9003 | Xdebug |
