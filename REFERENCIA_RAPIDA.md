# ⚡ REFERENCIA RÁPIDA - Sistema de Control de Asistencia

## 🚀 Iniciar el Sistema

```bash
# Instalar dependencias (solo primera vez)
composer install
composer dump-autoload -o

# Iniciar servidor
php artisan serve

# El sistema estará en: http://localhost:8000
```

---

## 📍 URLs Principales

| Módulo | URL | Descripción |
|--------|-----|-------------|
| **Login** | `/login` | Acceso al sistema |
| **Dashboard** | `/dashboard` | Panel principal |
| **Marcación** | `/asistencia/crear` | Marcar entrada/salida |
| **Asistencia** | `/asistencia` | Listado del día |
| **Reporte** | `/asistencia/reporte` | Reportes con filtros |
| **Personal** | `/personal` | CRUD de personal |
| **Configuración** | `/configuracion` | Sucursales, turnos, calendario |

---

## 👤 Credenciales de Prueba

```
Email: admin@asistencia.local
Contraseña: [configurar en Supabase]
```

---

## 🔑 Rutas API

```bash
# Autenticación
POST /api/login                 # Login (email, password)
POST /api/register              # Registro (email, password)
GET  /api/user                  # Datos usuario autenticado
POST /api/logout                # Logout

# Asistencia
GET  /asistencia/buscar-personal?ci=XXXX  # Buscar personal
POST /asistencia/marcar                   # Marcar entrada/salida
GET  /asistencia/reporte                  # Reportes (JSON)
```

---

## 📋 Flujos Principales

### 1️⃣ Marcar Asistencia
```
/asistencia/crear
    ↓ Ingresa CI
    ↓ GET /asistencia/buscar-personal?ci=1234567890
    ↓ Presiona ENTRADA
    ↓ POST /asistencia/marcar { personal_id: X, tipo: "entrada" }
    ↓ Registrada ✓
```

### 2️⃣ Crear Personal
```
/personal/crear
    ↓ Completa formulario
    ↓ POST /personal/guardar
    ↓ Creado ✓
```

### 3️⃣ Ver Reportes
```
/asistencia/reporte
    ↓ Selecciona fechas y sucursal
    ↓ GET /asistencia/reporte?inicio=2024-01-01&fin=2024-01-31
    ↓ Visualiza tabla
```

---

## 🗄️ Estructura de Datos

### Tabla asistencias (más importante)
```sql
SELECT * FROM asistencias
WHERE personal_id = 1
ORDER BY fecha DESC;

-- Campos importantes:
-- - personal_id: Quién
-- - fecha: Cuándo
-- - hora_entrada: A qué hora llegó
-- - hora_salida: A qué hora se fue
-- - estado: presente | tardanza | ausente
```

### Validación de Tolerancia
```
hora_entrada_real = 08:15
hora_entrada_turno = 08:00
tolerancia = 15 minutos

diferencia = 15 minutos

✓ diferencia <= tolerancia → PRESENTE
✗ diferencia > tolerancia → TARDANZA
```

---

## 📊 Estadísticas Dashboard

```
Total Personal: Todos los empleados activos (estado = 1)
Presentes: Con asistencia y estado = 'presente'
Tardanzas: Con asistencia y estado = 'tardanza'
Ausentes: Total - Presentes - Tardanzas
```

---

## ⚙️ Comandos Útiles

```bash
# Artisan
php artisan serve                    # Iniciar servidor
php artisan migrate                  # Ejecutar migraciones
php artisan tinker                   # CLI interactiva

# Composer
composer install                     # Instalar dependencias
composer dump-autoload -o            # Optimizar autoload

# Limpiar cache
php artisan cache:clear             # Limpiar caché
php artisan view:clear              # Limpiar vistas compiladas
php artisan config:clear            # Limpiar configuración

# Debug
tail -f storage/logs/laravel.log    # Ver logs
php artisan config:show             # Ver configuración
```

---

## 🔒 Autenticación & Seguridad

### Headers Requeridos
```javascript
// Para requests a la API:
headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
}
```

### Token Storage
```javascript
// Guardar token (después de login)
localStorage.setItem('auth_token', response.data.token);

// Obtener token
const token = localStorage.getItem('auth_token');

// Eliminar token (logout)
localStorage.removeItem('auth_token');
```

---

## 🐛 Troubleshooting

### El login no funciona
```
1. Verifica credenciales en Supabase
2. Revisa que Supabase URL y KEY estén en .env
3. Mira logs: tail -f storage/logs/laravel.log
```

### No aparecen datos en dashboard
```
1. Verifica conexión a BD (psql o Supabase)
2. Revisa que personal tenga asignación de turno
3. Comprueba que hoy sea un día laborable
```

### Personal no encontrado en búsqueda
```
1. Verifica que el CI sea exacto
2. Comprueba que personal tenga estado = 1
3. Asegúrate que tenga turno vigente asignado
```

---

## 📂 Archivos Importantes

```
app/Http/Controllers/
├── AuthController.php           # Autenticación
├── DashboardController.php      # Dashboard
├── AsistenciaController.php     # Marcación
├── PersonalController.php       # Personal CRUD
└── ConfiguracionController.php  # Configuración

app/Models/
├── Personal.php                 # Con getTurnoVigente()
├── Asistencia.php               # Con constantes de estado
├── Turno.php
├── Sucursal.php
└── ...

resources/views/
├── login.blade.php
├── dashboard.blade.php
├── asistencia/
├── personal/
└── configuracion/

routes/
├── web.php                      # Rutas principales
└── api.php                      # Rutas API

config/
└── app.php
```

---

## 💡 Tips Útiles

### Obtener turno vigente de un personal
```php
$personal = Personal::find(1);
$turno = $personal->turnoVigente;  // Custom accessor
```

### Crear asistencia
```php
Asistencia::create([
    'personal_id' => 1,
    'fecha' => now()->toDateString(),
    'hora_entrada' => now(),
    'estado' => 'presente',
    'registrado_por' => auth()->id(),
]);
```

### Buscar personal
```php
$personal = Personal::where('ci', '1234567890')
    ->where('estado', 1)
    ->first();
```

---

## 📞 Soporte

Para problemas técnicos, revisar:
- `storage/logs/laravel.log`
- Console del navegador (F12 → Console)
- Network tab (F12 → Network)

---

**Sistema de Control de Asistencia v1.0 - OPERATIVO ✅**

Última actualización: 2024
