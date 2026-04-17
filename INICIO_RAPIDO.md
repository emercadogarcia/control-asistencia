# ⚡ INICIO RÁPIDO (5 minutos)

## 1️⃣ Abre en DevContainer

Presiona en VS Code:
```
Ctrl+Shift+P → "Reopen in Container"
```

Espera 2-3 minutos (construcción de imagen Docker)

## 2️⃣ Instala Dependencias

En la terminal del contenedor:
```bash
composer install
npm install
php artisan key:generate
```

## 3️⃣ Configura la BD (Supabase)

1. Ve a https://app.supabase.com
2. Abre tu proyecto
3. SQL Editor → "New Query"
4. Copia todo el contenido de: `database/schema.sql`
5. Pega en Supabase y ejecuta

## 4️⃣ Configura Variables de Entorno

Abre `.env` y actualiza:
```env
DB_CONNECTION=supabase
DB_HOST=aws-0-xx.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.xxxxx
DB_PASSWORD=tu_contraseña
```

## 5️⃣ Inicia Desarrollo

Abre dos terminales:

**Terminal 1:**
```bash
php artisan serve --host=0.0.0.0
```

**Terminal 2:**
```bash
npm run dev
```

## ✅ Accede a la App

- **Frontend:** http://localhost:8000
- **Vite HMR:** http://localhost:5173

---

**¡Listo! El proyecto está corriendo** 🚀
