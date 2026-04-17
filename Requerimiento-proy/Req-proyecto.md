# PLAN DE REQUERIMIENTOS - SISTEMA DE CONTROL DE ASISTENCIA LABORAL

**Proyecto:** Asistencia Empresarial  
**Tecnología:** Laravel 11 + Supabase (PostgreSQL)  
**Responsable:** Edgar Mercado García (Lucho) – Coordinador de Desarrollo  
**Ubicación:** Santa Cruz de la Sierra, Bolivia  
**Fecha:** 13 de abril de 2026  

## Objetivo de Negocio
- Reducir **-25%** errores operativos en control de asistencia  
- Ahorrar **+20 horas/semana** en procesos manuales de RRHH  
- Aumentar **+30%** la eficiencia en cálculo de nómina y generación de reportes  
- Preparar solución escalable tipo SaaS con integración futura a ERP/nómina  

## Módulos del Sistema (Requeridos)

1. **Login** – Autenticación segura + RBAC (roles: Admin, Jefe, Coordinador, Empleado)  
2. **Dashboard** – Resumen en tiempo real (presentes, tardanzas, ausentes, horas extra pendientes)  
3. **Panel de Marcación** – Marcación completa por CI con validación de turno y tolerancia  
4. **Gestión Empleados** – CRUD completo + asignación de sucursal, turno y tipo de personal, debe incluir carga masiva desde Excel, tambien busqueda por CI o nombre 
5. **Reportes** – Asistencia diaria, atrasos, horas trabajadas, horas extra, inasistencias + exportación Excel/PDF  
6. **Configuración** – Sucursales, turnos, calendarios laborales, tolerancias jerárquicas y feriados , tambien debe tener un opcion para limpiar toda la base de datos para que la utilice otra empresa.
7. **Marcado Rápido** – Opción ultra-rápida (1 clic o escaneo QR) para marcación masiva o desde celular  

## Tablas principales (resumen de cambios)

- `sucursal` (renombrado de `oficina`)
- `turno`
- `asignacion_turno`
- `calendario_laboral`
- `horas_extra`
- Mejoras en `asistencia` (foto_url, ip_dispositivo)

### DDL clave (migraciones recomendadas)

```sql
-- Sucursal
CREATE TABLE IF NOT EXISTS sucursal (
    id              BIGSERIAL PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL UNIQUE,
    descripcion     TEXT,
    direccion       TEXT,
    ciudad          VARCHAR(80) DEFAULT 'Santa Cruz',
    estado          SMALLINT DEFAULT 1,
    creado_el       TIMESTAMPTZ DEFAULT NOW(),
    actualizado_el  TIMESTAMPTZ DEFAULT NOW()
);

-- Turno
CREATE TABLE IF NOT EXISTS turno (
    id                BIGSERIAL PRIMARY KEY,
    sucursal_id       BIGINT REFERENCES sucursal(id),
    nombre            VARCHAR(80) NOT NULL,
    hora_entrada      TIME NOT NULL,
    hora_salida       TIME NOT NULL,
    dias_semana       TEXT[] NOT NULL,
    tolerancia_min    SMALLINT NOT NULL DEFAULT 10,
    estado            SMALLINT DEFAULT 1,
    creado_el         TIMESTAMPTZ DEFAULT NOW(),
    actualizado_el    TIMESTAMPTZ DEFAULT NOW()
);

-- Asignacion Turno
CREATE TABLE IF NOT EXISTS asignacion_turno (
    id                BIGSERIAL PRIMARY KEY,
    personal_id       BIGINT REFERENCES personal(id),
    turno_id          BIGINT REFERENCES turno(id),
    fecha_inicio      DATE NOT NULL,
    fecha_fin         DATE,
    estado            SMALLINT DEFAULT 1
);

-- Calendario Laboral
CREATE TABLE IF NOT EXISTS calendario_laboral (
    id                BIGSERIAL PRIMARY KEY,
    sucursal_id       BIGINT REFERENCES sucursal(id),
    fecha             DATE NOT NULL UNIQUE,
    es_feriado        BOOLEAN DEFAULT false,
    descripcion       TEXT,
    creado_el         TIMESTAMPTZ DEFAULT NOW()
);

-- Horas Extra
CREATE TABLE IF NOT EXISTS horas_extra (
    id                    BIGSERIAL PRIMARY KEY,
    asistencia_id         BIGINT REFERENCES asistencia(id),
    minutos_extra         INTEGER NOT NULL,
    estado_aprobacion     VARCHAR(20) DEFAULT 'pendiente',
    aprobado_por          BIGINT REFERENCES personal(id),
    fecha_aprobacion      TIMESTAMPTZ,
    creado_el             TIMESTAMPTZ DEFAULT NOW()
);

---
```
### Indices recomendados:
solo como recomendacion pero se debe validar si hay mejoras se debe aplicar para mejor consistencia.
```sql
CREATE INDEX idx_asistencia_fecha_estado ON asistencia(fecha, estado);
CREATE INDEX idx_asignacion_turno_personal ON asignacion_turno(personal_id, fecha_inicio, fecha_fin);
```
---


## Flujo de Marcación (3 pasos)

- Configura empresa (sucursales, turnos, tolerancias, carga de empleados desde Excel opcional seeder para carga de ejemplos).
- Activa Marcado Rápido: Ingresa CI → sistema valida turno vigente → botones grandes ENTRADA / SALIDA (o QR).
- Control y reportes: Dashboard en tiempo real + exportación automática.

## Lógica de negocio clave:

- Tolerancia jerárquica (global → sucursal → turno)
- Validación de duplicados (UNIQUE personal_id + fecha)
- Cálculo automático de horas y horas extra vía Job
- Auditoría completa (IP, usuario que registra)

## Estructura del Proyecto Laravel
debe ser MVC 

Estrucutura que podria ser pero tomar siempre modelo MVC guardando las buenas practicas


referencias los archivos siguientes para mayor contexto:
- Requerimiento tecnico: ´requerimiento_tecnico.md´
- credenciales supabase: ´data-supabase.md´
