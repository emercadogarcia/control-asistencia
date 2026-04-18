Contexto:
Estoy trabajando en un proyecto existente de Laravel (API) con Supabase. Usa la estructura actual del proyecto como referencia (models, controllers, naming conventions, relaciones, etc). NO inventes nuevas estructuras si ya existen.

Objetivo:
Implementar un módulo de reportes basado en la tabla/modelo de asistencias ya existente.

Instrucciones:

1. Analiza el proyecto actual y reutiliza:
- Modelos existentes (ej: Asistencia o similar)
- Convenciones de nombres
- Estructura de carpetas
- Configuración de base de datos

2. Si algo no existe, créalo siguiendo el estilo actual del proyecto.

3. Implementar endpoints:
- GET /reportes/asistencia-diaria
- GET /reportes/atrasos
- GET /reportes/horas-trabajadas
- GET /reportes/horas-extra
- GET /reportes/inasistencias

4. Parámetros:
- fecha_inicio
- fecha_fin
- page
- per_page
- export (excel | pdf)

5. Reglas:
- Jornada: 8h
- Entrada esperada: 08:00
- Atraso: hora_entrada > 08:00
- Horas trabajadas: hora_salida - hora_entrada
- Horas extra: > 8h
- Inasistencia: sin registro en fecha

6. Requisitos:
- Usar Eloquent existente
- Paginación con paginate()
- Evitar N+1
- Reutilizar lógica en services

7. Exportación:
- Excel: maatwebsite/excel
- PDF: barryvdh/laravel-dompdf
- Exportar sin paginación

8. Entrega:
- Service de reportes
- Controller
- Clases de exportación
- Código integrado con el proyecto actual

Importante:
- No duplicar lógica existente
- No crear modelos innecesarios
- Mantener consistencia con el código actual
- No agregar explicaciones, solo código