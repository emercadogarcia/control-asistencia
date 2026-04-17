-- =====================================================
-- LIMPIEZA COMPLETA Y CREACIÓN DE SCHEMA
-- Sistema de Control de Asistencia
-- =====================================================

DROP SCHEMA IF EXISTS public CASCADE;
CREATE SCHEMA public;

CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- =====================================================
-- 1. TABLA: ROLES
-- =====================================================
CREATE TABLE roles (
    id BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    permisos JSONB DEFAULT '{}',
    estado SMALLINT DEFAULT 1,
    creado_el TIMESTAMPTZ DEFAULT NOW(),
    actualizado_el TIMESTAMPTZ DEFAULT NOW()
);

INSERT INTO roles (nombre, descripcion, estado) VALUES
    ('admin', 'Administrador del sistema', 1),
    ('jefe', 'Jefe de sucursal', 1),
    ('coordinador', 'Coordinador de RRHH', 1),
    ('empleado', 'Empleado', 1);

-- =====================================================
-- 2. TABLA: SUCURSAL
-- =====================================================
CREATE TABLE sucursal (
    id BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    direccion TEXT,
    ciudad VARCHAR(80) DEFAULT 'Santa Cruz',
    estado SMALLINT DEFAULT 1,
    creado_el TIMESTAMPTZ DEFAULT NOW(),
    actualizado_el TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_sucursal_estado ON sucursal(estado);

-- =====================================================
-- 3. TABLA: TURNO
-- =====================================================
CREATE TABLE turno (
    id BIGSERIAL PRIMARY KEY,
    sucursal_id BIGINT REFERENCES sucursal(id),
    nombre VARCHAR(80) NOT NULL,
    hora_entrada TIME NOT NULL,
    hora_salida TIME NOT NULL,
    dias_semana TEXT[] DEFAULT '{"lunes","martes","miercoles","jueves","viernes"}',
    tolerancia_min SMALLINT DEFAULT 10,
    estado SMALLINT DEFAULT 1,
    creado_el TIMESTAMPTZ DEFAULT NOW(),
    actualizado_el TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_turno_sucursal ON turno(sucursal_id);

-- =====================================================
-- 4. TABLA: TIPO_PERSONAL
-- =====================================================
CREATE TABLE tipo_personal (
    id BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    estado SMALLINT DEFAULT 1,
    creado_el TIMESTAMPTZ DEFAULT NOW()
);

INSERT INTO tipo_personal (nombre, descripcion, estado) VALUES
    ('Administrativo', 'Personal de administración', 1),
    ('Operativo', 'Personal operativo/producción', 1),
    ('Gerencia', 'Personal de dirección', 1),
    ('Consultor', 'Personal consultor', 1);

-- =====================================================
-- 5. TABLA: PERSONAL
-- =====================================================
CREATE TABLE personal (
    id BIGSERIAL PRIMARY KEY,
    ci VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_nacimiento DATE,
    fecha_contratacion DATE DEFAULT CURRENT_DATE,
    sucursal_id BIGINT REFERENCES sucursal(id),
    tipo_personal VARCHAR(50) DEFAULT 'empleado',
    estado SMALLINT DEFAULT 1,
    creado_el TIMESTAMPTZ DEFAULT NOW(),
    actualizado_el TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_personal_ci ON personal(ci);
CREATE INDEX idx_personal_email ON personal(email);

-- =====================================================
-- 6. TABLA: ASIGNACION_TURNO
-- =====================================================
CREATE TABLE asignacion_turno (
    id BIGSERIAL PRIMARY KEY,
    personal_id BIGINT REFERENCES personal(id),
    turno_id BIGINT REFERENCES turno(id),
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    estado SMALLINT DEFAULT 1,
    creado_el TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_asignacion_personal ON asignacion_turno(personal_id);

-- =====================================================
-- 7. TABLA: ASISTENCIA
-- =====================================================
CREATE TABLE asistencia (
    id BIGSERIAL PRIMARY KEY,
    personal_id BIGINT REFERENCES personal(id),
    fecha DATE NOT NULL,
    hora_entrada TIMESTAMPTZ,
    hora_salida TIMESTAMPTZ,
    tipo_entrada VARCHAR(20),
    foto_url TEXT,
    ip_dispositivo VARCHAR(45),
    atraso_min SMALLINT DEFAULT 0,
    estado VARCHAR(20) DEFAULT 'marcado',
    observaciones TEXT,
    creado_el TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_asistencia_personal ON asistencia(personal_id);
CREATE INDEX idx_asistencia_fecha ON asistencia(fecha);

-- =====================================================
-- 8. TABLA: HORAS_EXTRA
-- =====================================================
CREATE TABLE horas_extra (
    id BIGSERIAL PRIMARY KEY,
    asistencia_id BIGINT REFERENCES asistencia(id),
    personal_id BIGINT REFERENCES personal(id),
    fecha DATE,
    minutos_extra INTEGER,
    estado_aprobacion VARCHAR(20) DEFAULT 'pendiente',
    aprobado_por BIGINT REFERENCES personal(id),
    fecha_aprobacion TIMESTAMPTZ,
    creado_el TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_horas_extra_personal ON horas_extra(personal_id);

-- =====================================================
-- 9. TABLA: CALENDARIO_LABORAL
-- =====================================================
CREATE TABLE calendario_laboral (
    id BIGSERIAL PRIMARY KEY,
    sucursal_id BIGINT REFERENCES sucursal(id),
    fecha DATE NOT NULL,
    es_feriado BOOLEAN DEFAULT false,
    descripcion TEXT,
    creado_el TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_calendario_fecha ON calendario_laboral(fecha);

-- =====================================================
-- 10. TABLA: CONFIGURACION
-- =====================================================
CREATE TABLE configuracion (
    id BIGSERIAL PRIMARY KEY,
    sucursal_id BIGINT REFERENCES sucursal(id),
    clave VARCHAR(100) NOT NULL,
    valor TEXT,
    tipo VARCHAR(20),
    descripcion TEXT,
    creado_el TIMESTAMPTZ DEFAULT NOW()
);

-- =====================================================
-- 11. TABLA: AUDITORIA
-- =====================================================
CREATE TABLE auditoria (
    id BIGSERIAL PRIMARY KEY,
    tabla VARCHAR(100),
    registro_id BIGINT,
    accion VARCHAR(20),
    usuario_id BIGINT,
    ip_direccion VARCHAR(45),
    datos_anteriores JSONB,
    datos_nuevos JSONB,
    creado_el TIMESTAMPTZ DEFAULT NOW()
);

-- =====================================================
-- Row Level Security
-- =====================================================
ALTER TABLE personal ENABLE ROW LEVEL SECURITY;
ALTER TABLE asistencia ENABLE ROW LEVEL SECURITY;

CREATE POLICY personal_select ON personal FOR SELECT USING (true);
CREATE POLICY asistencia_select ON asistencia FOR SELECT USING (true);

COMMENT ON SCHEMA public IS 'Schema Control de Asistencia - Limpio y listo';
