<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Dashboard - Control de Asistencia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #0f172a; }
        header { background: #ffffff; border-bottom: 1px solid #e2e8f0; }
        .container { max-width: 1180px; margin: 0 auto; padding: 0 20px; }
        .header-inner { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 22px 0; }
        .header-inner h1 { font-size: 30px; font-weight: 700; }
        .header-copy p, .today-label { color: #64748b; margin-top: 4px; }
        .top-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .page { padding: 28px 0 40px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; border-radius: 14px; border: 1px solid transparent; padding: 11px 15px; font-weight: 600; text-decoration: none; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 10px 18px rgba(15, 23, 42, 0.1); }
        .btn-primary { background: #0f172a; color: white; }
        .btn-secondary { background: #e2e8f0; color: #0f172a; }
        .grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
        .stat-card, .section, .menu-item { background: white; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06); }
        .stat-card { padding: 18px; }
        .stat-label { display: block; color: #64748b; font-size: 13px; margin-bottom: 8px; }
        .stat-number { font-size: 30px; font-weight: 800; }
        .section { padding: 22px; margin-bottom: 18px; }
        .section h2 { font-size: 20px; margin-bottom: 8px; }
        .section p { color: #64748b; }
        .menu-rapido { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-top: 18px; }
        .menu-item { padding: 18px; text-decoration: none; color: #0f172a; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .menu-item:hover { transform: translateY(-2px); box-shadow: 0 16px 26px rgba(15, 23, 42, 0.1); }
        .menu-item span { display: inline-flex; width: 42px; height: 42px; align-items: center; justify-content: center; border-radius: 14px; background: #f8fafc; margin-bottom: 14px; }
        .menu-item strong { display: block; margin-bottom: 6px; }
        .menu-item small { color: #64748b; }
        .table-wrap { overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 18px; margin-top: 18px; }
        table { width: 100%; border-collapse: collapse; min-width: 760px; background: white; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; background: #f8fafc; }
        tbody tr:hover { background: #f8fafc; }
        .badge { display: inline-flex; align-items: center; padding: 5px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .badge-presente { background: #dcfce7; color: #166534; }
        .badge-tardanza { background: #ffedd5; color: #c2410c; }
        .badge-ausente { background: #fee2e2; color: #991b1b; }
        .empty-state { text-align: center; padding: 28px; color: #64748b; }
        .hour { font-weight: 700; }
        @media (max-width: 980px) {
            .header-inner { flex-direction: column; align-items: flex-start; }
            .grid, .menu-rapido { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .grid, .menu-rapido { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-inner">
            <div class="header-copy">
                <h1>Control de asistencia</h1>
                <p>Panel principal con acceso rapido a marcaciones, personal y configuracion.</p>
                <div class="today-label">Hoy: <?php echo e($hoy->format('d/m/Y')); ?></div>
            </div>
            <div class="top-actions">
                <span id="userName" class="today-label">Usuario</span>
                <button onclick="logout()" class="btn btn-secondary">Cerrar sesion</button>
            </div>
        </div>
    </header>

    <main class="container page">
        <div class="section">
            <h2>Resumen del dia</h2>
            <p>Seguimiento inmediato del estado operativo de asistencia.</p>
            <div class="grid">
                <div class="stat-card">
                    <div class="stat-label">Total personal</div>
                    <div class="stat-number"><?php echo e($totalPersonal); ?></div>
                </div>
                <div class="stat-card presente">
                    <div class="stat-label">Presentes</div>
                    <div class="stat-number"><?php echo e($presentesHoy); ?></div>
                </div>
                <div class="stat-card tardanza">
                    <div class="stat-label">Tardanzas</div>
                    <div class="stat-number"><?php echo e($tardanzasHoy); ?></div>
                </div>
                <div class="stat-card ausente">
                    <div class="stat-label">Ausentes</div>
                    <div class="stat-number"><?php echo e($auentesHoy); ?></div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Acciones rapidas</h2>
            <p>Los modulos clave mantienen la misma linea visual minimalista.</p>
            <div class="menu-rapido">
                <a href="<?php echo e(route('asistencia.crear')); ?>" class="menu-item">
                    <span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.8"/>
                        </svg>
                    </span>
                    <strong>Marcar Asistencia</strong>
                    <small>Registrar por CI con reloj digital y flujo automatico.</small>
                </a>
                <a href="<?php echo e(route('personal.index')); ?>" class="menu-item">
                    <span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M16 19a4 4 0 0 0-8 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M5 19a4 4 0 0 1 2-3.5M19 19a4 4 0 0 0-2-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <strong>Gestionar Personal</strong>
                    <small>Consulta registros y usa acciones con iconos unificados.</small>
                </a>
                <a href="<?php echo e(route('asistencia.reporte')); ?>" class="menu-item">
                    <span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 19V5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M5 19h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="m8 15 3-3 2 2 4-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <strong>Reportes</strong>
                    <small>Consulta consolidado diario de asistencia.</small>
                </a>
                <a href="<?php echo e(route('configuracion.index')); ?>" class="menu-item">
                    <span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 9.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M19.4 15a1 1 0 0 0 .2 1.1l.1.1a1 1 0 0 1 0 1.4l-1.2 1.2a1 1 0 0 1-1.4 0l-.1-.1a1 1 0 0 0-1.1-.2 1 1 0 0 0-.6.9V20a1 1 0 0 1-1 1h-1.7a1 1 0 0 1-1-1v-.2a1 1 0 0 0-.7-.9 1 1 0 0 0-1.1.2l-.1.1a1 1 0 0 1-1.4 0L4.3 17.9a1 1 0 0 1 0-1.4l.1-.1a1 1 0 0 0 .2-1.1 1 1 0 0 0-.9-.6H3.5a1 1 0 0 1-1-1v-1.7a1 1 0 0 1 1-1h.2a1 1 0 0 0 .9-.7 1 1 0 0 0-.2-1.1l-.1-.1a1 1 0 0 1 0-1.4l1.2-1.2a1 1 0 0 1 1.4 0l.1.1a1 1 0 0 0 1.1.2 1 1 0 0 0 .6-.9V4a1 1 0 0 1 1-1h1.7a1 1 0 0 1 1 1v.2a1 1 0 0 0 .7.9 1 1 0 0 0 1.1-.2l.1-.1a1 1 0 0 1 1.4 0l1.2 1.2a1 1 0 0 1 0 1.4l-.1.1a1 1 0 0 0-.2 1.1 1 1 0 0 0 .9.6h.2a1 1 0 0 1 1 1v1.7a1 1 0 0 1-1 1h-.2a1 1 0 0 0-.9.7Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <strong>Configuración</strong>
                    <small>Sucursales, turnos y calendarios en estilo minimalista.</small>
                </a>
            </div>
        </div>

        <div class="section">
            <h2>Ultimas marcaciones</h2>
            <p>Actividad reciente del dia para una revision rapida.</p>
            <?php if($asistenciasRecientes->count() > 0): ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Personal</th>
                                <th>CI</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $asistenciasRecientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asistencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($asistencia->personal->nombre_completo); ?></strong></td>
                                    <td><?php echo e($asistencia->personal->ci); ?></td>
                                    <td><span class="hour"><?php echo e($asistencia->hora_entrada->format('H:i')); ?></span></td>
                                    <td><?php echo e($asistencia->hora_salida ? $asistencia->hora_salida->format('H:i') : '—'); ?></td>
                                    <td>
                                        <?php if($asistencia->estado === 'presente'): ?>
                                            <span class="badge badge-presente">Presente</span>
                                        <?php elseif($asistencia->estado === 'tardanza'): ?>
                                            <span class="badge badge-tardanza">Tardanza</span>
                                        <?php else: ?>
                                            <span class="badge badge-ausente">Ausente</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">No hay marcaciones aun hoy.</div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        async function loadUserInfo() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            try {
                const response = await fetch('/api/user', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (response.ok) {
                    const user = await response.json();
                    document.getElementById('userName').textContent = user.name || user.email;
                } else {
                    localStorage.removeItem('auth_token');
                    window.location.href = '/login';
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function logout() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}` }
                }).then(() => {
                    localStorage.removeItem('auth_token');
                    window.location.href = '/login';
                });
            } else {
                window.location.href = '/login';
            }
        }

        loadUserInfo();
    </script>
</body>
</html>
<?php /**PATH /workspace/resources/views/dashboard.blade.php ENDPATH**/ ?>