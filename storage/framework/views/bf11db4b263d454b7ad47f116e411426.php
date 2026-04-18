<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reportes de Asistencia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; }
        header { background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%); color: white; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .section { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        h2 { color: #0284c7; margin-bottom: 20px; }
        .filters { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        input, select { padding: 10px; border: 1px solid #e5e7eb; border-radius: 4px; }
        .btn { background: #0284c7; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-export { background: #16a34a; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f3f4f6; padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb; }
        td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
        tr:hover { background: #f9fafb; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-presente { background: #dcfce7; color: #166534; }
        .badge-tardanza { background: #fed7aa; color: #92400e; }
        .badge-ausente { background: #fee2e2; color: #991b1b; }
        .link-back { display: inline-block; margin-top: 10px; color: #0284c7; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <h1>📈 Reportes de Asistencia</h1>
    </header>

    <div class="container">
        <div class="section">
            <h2>Generar Reporte</h2>
            
            <div class="filters">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Desde</label>
                    <input type="date" id="fechaInicio" value="<?php echo e(date('Y-m-d')); ?>">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Hasta</label>
                    <input type="date" id="fechaFin" value="<?php echo e(date('Y-m-d')); ?>">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Sucursal</label>
                    <select id="sucursalFilter">
                        <option value="">Todas</option>
                        <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sucursal->id); ?>"><?php echo e($sucursal->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div style="display: flex; gap: 10px; align-items: flex-end;">
                    <button class="btn" onclick="cargarReporte()">Filtrar</button>
                    <button class="btn btn-export" onclick="exportarExcel()">📥 Excel</button>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Resultados</h2>
            <div id="reporte-container">
                <p style="text-align: center; color: #999;">Cargando...</p>
            </div>
        </div>

        <a href="<?php echo e(route('dashboard')); ?>" class="link-back">← Volver al Dashboard</a>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        if (!token) window.location.href = '/login';

        async function cargarReporte() {
            const inicio = document.getElementById('fechaInicio').value;
            const fin = document.getElementById('fechaFin').value;
            const sucursal = document.getElementById('sucursalFilter').value;

            const url = new URL('<?php echo e(route('asistencia.reporte.datos')); ?>');
            url.searchParams.append('inicio', inicio);
            url.searchParams.append('fin', fin);
            if (sucursal) url.searchParams.append('sucursal_id', sucursal);

            try {
                const response = await fetch(url, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!response.ok) throw new Error('Error al cargar reporte');
                
                const data = await response.json();
                mostrarReporte(data);
            } catch (error) {
                document.getElementById('reporte-container').innerHTML = 
                    '<p style="color: red;">Error al cargar el reporte</p>';
            }
        }

        function mostrarReporte(data) {
            let html = '<table><thead><tr>';
            html += '<th>Personal</th><th>Fecha</th><th>Entrada</th><th>Salida</th><th>Estado</th><th>Turno</th>';
            html += '</tr></thead><tbody>';

            data.forEach(a => {
                const entrada = a.hora_entrada ? a.hora_entrada.slice(11, 16) : '—';
                const salida = a.hora_salida ? a.hora_salida.slice(11, 16) : '—';
                const badge = a.estado === 'presente' ? 'badge-presente' : 
                              a.estado === 'tardanza' ? 'badge-tardanza' : 'badge-ausente';
                
                html += `<tr>
                    <td>${a.personal.nombre}</td>
                    <td>${a.fecha}</td>
                    <td>${entrada}</td>
                    <td>${salida}</td>
                    <td><span class="badge ${badge}">${a.estado}</span></td>
                    <td>${a.personal.turno_vigente ? a.personal.turno_vigente.nombre : '—'}</td>
                </tr>`;
            });

            html += '</tbody></table>';
            document.getElementById('reporte-container').innerHTML = html;
        }

        function exportarExcel() {
            const inicio = document.getElementById('fechaInicio').value;
            const fin = document.getElementById('fechaFin').value;
            
            window.location.href = `<?php echo e(route('asistencia.exportar')); ?>?inicio=${inicio}&fin=${fin}`;
        }

        cargarReporte();
    </script>
</body>
</html>
<?php /**PATH /workspace/resources/views/asistencia/reporte.blade.php ENDPATH**/ ?>