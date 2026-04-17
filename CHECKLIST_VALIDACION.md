# ✅ CHECKLIST DE VALIDACIÓN

## Pre-Instalación

- [ ] DevContainer está instalado en VS Code
- [ ] Docker está corriendo
- [ ] WSL2 está habilitado (Windows)
- [ ] Conexión a internet disponible

## Instalación

- [ ] `composer install` completó sin errores
- [ ] `npm install` completó sin errores
- [ ] `php artisan key:generate` ejecutado
- [ ] `.env` contiene credenciales Supabase
- [ ] Base de datos Supabase creada

## Configuración BD

- [ ] Schema SQL ejecutado en Supabase
- [ ] 11 tablas creadas
- [ ] RLS habilitado
- [ ] Conexión a BD verifica correctamente

## Ejecución

- [ ] `php artisan serve` corre sin errores
- [ ] `npm run dev` corre sin errores
- [ ] Frontend accesible en http://localhost:8000
- [ ] No hay errores en console del navegador
- [ ] Estilos Tailwind cargan correctamente

## Módulos

- [ ] Dashboard visible
- [ ] Ruta /asistencia responde
- [ ] Ruta /personal responde
- [ ] Ruta /configuracion responde

---

**Completar este checklist antes de usar en producción.**
