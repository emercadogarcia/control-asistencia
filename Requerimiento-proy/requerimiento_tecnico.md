# 📄 REQUERIMIENTO OPTIMIZADO – SISTEMA DE CONTROL DE ASISTENCIA (Laravel + Supabase)

## 1. CONTEXTO  
Se requiere implementar un sistema de **control de asistencia laboral** basado en el requerimiento existente, manteniendo toda su lógica funcional, estructura y objetivos de negocio, con un ajuste en la arquitectura:

- **Frontend:** Laravel 11 (Inertia.js + Vue 3)  
- **Backend (BaaS):** Supabase (PostgreSQL + Auth + Storage + Edge Functions si aplica)  

Adicionalmente, se debe garantizar que la base de datos en Supabase sea **exclusiva para este proyecto**, eliminando cualquier estructura previa no relacionada.

El archivo `data-supabase.md` contiene la definición de datos requerida. Si la información es incompleta, se deben solicitar únicamente los datos faltantes críticos antes de continuar.

---

## 2. ROL  
Actúa como un **Arquitecto de Software Senior + Backend Engineer especializado en Supabase + Laravel Fullstack Developer**, con experiencia en diseño SaaS, modelado de datos y buenas prácticas MVC.

---

## 3. OBJETIVO  
Adaptar el requerimiento original del sistema de asistencia para:

- Mantener **100% la lógica funcional definida**
- Migrar el backend a **Supabase como servicio central**
- Usar Laravel únicamente como **capa de frontend + orquestación**
- Dejar la base de datos **limpia, optimizada y exclusiva del proyecto**
- Garantizar una arquitectura escalable tipo SaaS

---

## 4. INSTRUCCIONES  

### 4.1 Adaptación de Arquitectura
- Mantener Laravel como frontend usando:
  - Inertia.js + Vue 3
  - Consumo de Supabase vía API/SDK
- Delegar en Supabase:
  - Base de datos PostgreSQL
  - Autenticación
  - Storage
  - Lógica backend (RPC / Edge Functions)

---

### 4.2 Base de Datos (Supabase)
- Limpieza completa de esquema existente
- Creación de tablas según requerimiento
- Validar integridad e índices

---

### 4.3 Validación contra `data-supabase.md`
- Detectar faltantes e inconsistencias
- Solicitar datos críticos si aplica

---

### 4.4 Backend Lógico en Supabase
- Validaciones de marcación
- Control de duplicados
- Cálculo de horas y horas extra
- Auditoría

---

### 4.5 Integración Laravel ↔ Supabase
- Laravel consume APIs
- Manejo de sesión con tokens
- Renderizado de vistas

---

### 4.6 Seguridad
- Implementar RLS
- Políticas por rol

---

## 5. REQUISITOS  

### Funcionales
- Mantener flujos originales  
- Carga masiva Excel  
- Exportación  

### Técnicos
- Laravel 11  
- Supabase  
- API / RPC  

---

## 6. RESTRICCIONES  

- No alterar lógica  
- No duplicar lógica en Laravel  
- No asumir datos faltantes  

---

## 7. FORMATO DE SALIDA  

- Arquitectura  
- SQL  
- APIs  
- Integración  
- Validaciones  

---

## 8. CRITERIOS DE CALIDAD  

- Producción  
- Escalable  
- Seguro  
- Sin ambigüedad  
