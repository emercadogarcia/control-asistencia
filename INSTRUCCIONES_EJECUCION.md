# 🚀 INSTRUCCIONES DE EJECUCIÓN

## Opción 1: DevContainer (RECOMENDADO)

1. **Abre en VS Code**
   - Presiona: `Ctrl+Shift+P`
   - Busca: "Remote Containers: Reopen in Container"
   - Espera construcción (2-3 min)

2. **Instala dependencias**
   ```bash
   composer install
   npm install
   php artisan key:generate
   ```

3. **Ejecuta servidores**
   ```bash
   # Terminal 1
   php artisan serve --host=0.0.0.0 --port=8000
   
   # Terminal 2
   npm run dev
   ```

4. **Accede a**
   - http://localhost:8000 (Frontend)
   - http://localhost:5173 (Vite)

## Opción 2: Sin DevContainer

1. **Requisitos:** PHP 8.3+, Node 20+, Composer

2. **Instala**
   ```bash
   composer install && npm install
   php artisan key:generate
   ```

3. **Ejecuta**
   ```bash
   php artisan serve
   npm run dev
   ```

## 🗄️ Configurar Supabase BD

1. Ve a: https://app.supabase.com
2. SQL Editor → New Query
3. Copia contenido de: `database/schema.sql`
4. Ejecuta

**¡Listo!**
