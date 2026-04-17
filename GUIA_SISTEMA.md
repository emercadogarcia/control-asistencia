# 📋 Guía de Uso - Sistema de Control de Asistencia

## ✅ Estado de Implementación

### Módulos Completados
- ✅ **Autenticación**: Integración con Supabase Auth
- ✅ **Dashboard**: Estadísticas en tiempo real del día
- ✅ **Marcación Rápida**: Interfaz de entrada/salida con CI
- ✅ **Gestión de Personal**: CRUD completo con búsqueda
- ✅ **Configuración**: Sucursales, Turnos, Calendario Laboral
- ✅ **Reportes**: Reporte diario con filtros por fecha y sucursal

### Características Implementadas

#### 1. Marcación Rápida (Panel Principal)
- Ingresa tu CI (Cédula de Identidad)
- Sistema valida turno vigente automáticamente
- Botones grandes de ENTRADA ✍️ y SALIDA ⬅️
- Validación de tolerancia automática
- Feedback visual de estado (Presente/Tardanza)

#### 2. Dashboard
- Resumen diario: Total Personal, Presentes, Tardanzas, Ausentes
- Últimas 10 marcaciones del día
- Accesos rápidos a todos los módulos
- Información del usuario autenticado

#### 3. Gestión de Personal
- Listado completo de personal activo
- Búsqueda por nombre, CI, email o sucursal
- Crear nuevo personal
- Editar información (nombre, apellido, CI, email, teléfono, tipo, sucursal)
- Desactivar personal (soft delete)

#### 4. Configuración
- **Sucursales**: Crear y gestionar sucursales/locales
- **Turnos**: Definir turnos con horarios y tolerancia
- **Calendario Laboral**: Agregar feriados y eventos
- **Reset BD**: Limpiar base de datos (requiere contraseña)

#### 5. Reportes
- Filtrar asistencia por rango de fechas
- Filtrar por sucursal
- Mostrar entrada, salida y estado de cada marcación
- Export a Excel (preparado para implementación futura)

---

## 🔐 Acceso al Sistema

### Usuarios de Prueba
```
Email: admin@asistencia.local
Email: demo@asistencia.local
```

### Flujo de Login
1. Ve a `http://localhost:8000/login`
2. Ingresa tu email y contraseña (en Supabase Auth)
3. Haz clic en "Ingresar"
4. Serás redirigido al Dashboard

---

## 📍 Navegación Principal

### Dashboard (`/dashboard`)
- Vista principal con estadísticas
- Accesos rápidos a todos los módulos
- Últimas marcaciones del día

### Marcación Rápida (`/asistencia/crear`)
- Panel para marcar entrada/salida
- Búsqueda de personal por CI
- Validación automática de turnos

### Listado de Asistencia (`/asistencia`)
- Todas las marcaciones de hoy
- Estado de entrada/salida

### Gestión de Personal (`/personal`)
- CRUD completo
- Búsqueda y filtros
- Asignación de sucursal y tipo

### Configuración (`/configuracion`)
- Sucursales
- Turnos laborales
- Calendario laboral
- Reset de base de datos

### Reportes (`/asistencia/reporte`)
- Filtrar por rango de fechas
- Filtrar por sucursal
- Ver detalle de asistencias

---

## ⚙️ Lógica de Negocio

### Validación de Tolerancia
```
1. Se obtiene el turno vigente del personal
2. Se compara hora de entrada con hora_entrada del turno
3. Si diferencia <= tolerancia_min → PRESENTE
4. Si diferencia > tolerancia_min → TARDANZA
5. Se registra con estado correspondiente
```

### Turnos Vigentes
- Se obtiene de `asignacion_turnos` con fecha vigente
- Campo `fecha_inicio` <= hoy y (`fecha_fin` IS NULL o >= hoy)
- Cada personal puede tener un turno vigente

### Estados de Asistencia
- `presente`: Entrada dentro de tolerancia
- `tardanza`: Entrada fuera de tolerancia
- `ausente`: Sin marcación
- `permiso`: Permisos autorizados

---

## 🔧 Configuración Técnica

### Base de Datos
- **Host**: aws-1-us-east-1.pooler.supabase.com
- **Puerto**: 6543
- **Usuario**: postgres
- **Base**: postgres
- **Tablas Principales**: 
  - sucursals, turnos, personals, asignacion_turnos
  - asistencias, horas_extras, calendario_laborals
  - users, personal_access_tokens, migrations

### API Endpoints
- `POST /api/login` - Autenticación con Supabase
- `POST /api/register` - Registro de nuevo usuario
- `GET /api/user` - Información del usuario autenticado
- `POST /api/logout` - Cierre de sesión
- `GET /asistencia/buscar-personal?ci=XXXX` - Buscar personal por CI
- `POST /asistencia/marcar` - Registrar entrada/salida
- `GET /asistencia/reporte` - Obtener reporte de asistencias

### Autenticación
- Token JWT de Supabase Auth
- Token de sesión con Sanctum (Laravel)
- Almacenamiento local del token en el navegador
- Envío en header: `Authorization: Bearer {token}`

---

## 📊 Tablas de Base de Datos

### sucursals
- id, nombre, direccion, telefono, estado, timestamps

### turnos
- id, sucursal_id, nombre, hora_entrada, hora_salida, tolerancia_min, estado, timestamps

### personals
- id, sucursal_id, nombre, apellido, ci, email, telefono, tipo_personal, estado, timestamps

### asignacion_turnos
- id, personal_id, turno_id, fecha_inicio, fecha_fin, estado, timestamps

### asistencias
- id, personal_id, fecha, hora_entrada, hora_salida, estado, ip_dispositivo, registrado_por, timestamps

### calendario_laborals
- id, fecha, es_feriado, descripcion, estado, timestamps

### horas_extras
- id, asistencia_id, personal_id, minutos_extra, estado_aprobacion, aprobado_por, fecha_aprobacion, timestamps

---

## 🚀 Próximas Mejoras

- [ ] Exportación a Excel con librería maatwebsite/excel
- [ ] QR code scanning para marcación
- [ ] Cálculo automático de horas extras
- [ ] Notificaciones en tiempo real
- [ ] Reportes en PDF
- [ ] Dashboard con gráficos avanzados
- [ ] Integración con sistemas de payroll

---

## 📞 Soporte

Para reportar errores o sugerencias, contacta al equipo de desarrollo.

**Sistema de Control de Asistencia v1.0**
