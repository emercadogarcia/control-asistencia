<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sucursales</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; }
        header { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); color: white; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .section { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        h2 { color: #8b5cf6; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 4px; }
        .btn { background: #0284c7; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f3f4f6; padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb; }
        td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
        .btn-delete { background: #dc2626; padding: 6px 12px; }
        .btn-edit { background: #f59e0b; padding: 6px 12px; margin-right: 5px; }
        .link-back { display: inline-block; margin-top: 10px; color: #8b5cf6; text-decoration: none; }
    </style>
</head>
<body>
    <header><h1>🏢 Gestión de Sucursales</h1></header>

    <div class="container">
        <div class="section">
            <h2>Crear Sucursal</h2>
            <form method="POST" action="<?php echo e(route('configuracion.sucursales.crear')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <textarea name="direccion"></textarea>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono">
                </div>
                <button type="submit" class="btn">Crear Sucursal</button>
            </form>
        </div>

        <div class="section" style="margin-top: 20px;">
            <h2>Sucursales Registradas</h2>
            <?php if($sucursales->count() > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Personal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($s->nombre); ?></strong></td>
                                <td><?php echo e($s->direccion ?? '—'); ?></td>
                                <td><?php echo e($s->telefono ?? '—'); ?></td>
                                <td><?php echo e($s->personals->count()); ?></td>
                                <td>
                                    <a href="<?php echo e(route('configuracion.sucursales.editar', $s->id)); ?>" class="btn btn-edit">Editar</a>
                                    <button class="btn btn-delete" onclick="deleteSucursal(<?php echo e($s->id); ?>)">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #999;">Sin sucursales registradas</p>
            <?php endif; ?>
        </div>

        <a href="<?php echo e(route('configuracion.index')); ?>" class="link-back">← Volver</a>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        if (!token) window.location.href = '/login';

        function deleteSucursal(id) {
            if (!confirm('¿Eliminar sucursal?')) return;
            window.location.href = `<?php echo e(url('configuracion/sucursales')); ?>/${id}/eliminar`;
        }
    </script>
</body>
</html>
<?php /**PATH /workspace/resources/views/configuracion/sucursales.blade.php ENDPATH**/ ?>