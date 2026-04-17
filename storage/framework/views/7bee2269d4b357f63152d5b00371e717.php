<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📋</text></svg>">
    <title>Dashboard - Control de Asistencia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f3f4f6;
            color: #333;
        }
        header {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .header-right {
            float: right;
            display: flex;
            gap: 15px;
            margin-top: -30px;
        }
        .header-right a, .header-right button {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 13px;
        }
        .header-right a:hover, .header-right button:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .fecha-hoy {
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            margin-top: 10px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #0284c7;
            text-align: center;
        }
        .stat-card.presente {
            border-left-color: #16a34a;
        }
        .stat-card.tardanza {
            border-left-color: #ea580c;
        }
        .stat-card.ausente {
            border-left-color: #dc2626;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #0284c7;
            margin: 10px 0;
        }
        .stat-card.presente .stat-number {
            color: #16a34a;
        }
        .stat-card.tardanza .stat-number {
            color: #ea580c;
        }
        .stat-card.ausente .stat-number {
            color: #dc2626;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        .section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .section h2 {
            color: #0284c7;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .btn-primary, .btn-secondary {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #0284c7;
            color: white;
        }
        .btn-primary:hover {
            background: #0369a1;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #333;
        }
        .btn-secondary:hover {
            background: #d1d5db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            color: #333;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:hover {
            background: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge-presente {
            background: #dcfce7;
            color: #166534;
        }
        .badge-tardanza {
            background: #fed7aa;
            color: #92400e;
        }
        .badge-ausente {
            background: #fee2e2;
            color: #991b1b;
        }
        .menu-rapido {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .menu-item {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            transition: transform 0.3s;
            cursor: pointer;
        }
        .menu-item:hover {
            transform: translateY(-5px);
        }
        .menu-item.config {
            background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
        }
        .menu-item.config:hover {
            transform: translateY(-5px);
        }
        .success-message {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            border-left: 4px solid #16a34a;
        }
        .hour {
            font-weight: 600;
            color: #0284c7;
        }
    </style>
</head>
<body>
    <header>
        <h1>📋 Control de Asistencia</h1>
        <div class="fecha-hoy">Hoy: <?php echo e($hoy->format('d/m/Y - l')); ?></div>
        <div class="header-right">
            <span id="userName">Usuario</span>
            <button onclick="logout()">Cerrar Sesión</button>
        </div>
    </header>

    <div class="container">
        <div class="section">
            <h2>📊 Resumen del Día</h2>
            <div class="grid">
                <div class="stat-card">
                    <div class="stat-label">Total Personal</div>
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
            <h2>⚡ Acciones Rápidas</h2>
            <div class="menu-rapido">
                <a href="<?php echo e(route('asistencia.crear')); ?>" class="menu-item">
                    <div style="font-size: 24px; margin-bottom: 10px;">✍️</div>
                    <strong>Marcar Asistencia</strong>
                </a>
                <a href="<?php echo e(route('personal.index')); ?>" class="menu-item">
                    <div style="font-size: 24px; margin-bottom: 10px;">👥</div>
                    <strong>Gestionar Personal</strong>
                </a>
                <a href="<?php echo e(route('asistencia.reporte')); ?>" class="menu-item">
                    <div style="font-size: 24px; margin-bottom: 10px;">📈</div>
                    <strong>Reportes</strong>
                </a>
                <a href="<?php echo e(route('configuracion.index')); ?>" class="menu-item config">
                    <div style="font-size: 24px; margin-bottom: 10px;">⚙️</div>
                    <strong>Configuración</strong>
                </a>
            </div>
        </div>

        <div class="section">
            <h2>🕐 Últimas Marcaciones</h2>
            <?php if($asistenciasRecientes->count() > 0): ?>
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
            <?php else: ?>
                <p style="color: #999; text-align: center; padding: 40px;">No hay marcaciones aún hoy.</p>
            <?php endif; ?>
        </div>
    </div>

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