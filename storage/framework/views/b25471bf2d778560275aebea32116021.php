<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Personal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; }
        header { background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%); color: white; padding: 20px; }
        .container { max-width: 600px; margin: 40px auto; padding: 20px; }
        .form-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        h2 { color: #0284c7; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; color: #333; }
        input, select { width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 14px; }
        input:focus, select:focus { outline: none; border-color: #0284c7; box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1); }
        .btn-submit { background: #16a34a; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px; }
        .link-back { display: inline-block; margin-top: 10px; color: #0284c7; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <h1>➕ Crear Personal</h1>
    </header>

    <div class="container">
        <div class="form-card">
            <h2>Registrar Nuevo Personal</h2>
            
            <form method="POST" action="<?php echo e(route('personal.guardar')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required>
                </div>

                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" name="apellido" required>
                </div>

                <div class="form-group">
                    <label>CI (Cédula de Identidad)</label>
                    <input type="text" name="ci" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono">
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion">
                </div>

                <div class="form-group">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento">
                </div>

                <div class="form-group">
                    <label>Fecha de Contratación</label>
                    <input type="date" name="fecha_contratacion" value="<?php echo e(date('Y-m-d')); ?>" required>
                </div>

                <div class="form-group">
                    <label>Tipo de Personal</label>
                    <select name="tipo_personal_id" required>
                        <option value="">Seleccionar...</option>
                        <?php $__currentLoopData = $tiposPersonal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipoPersonal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tipoPersonal->id); ?>" <?php echo e(old('tipo_personal_id') == $tipoPersonal->id ? 'selected' : ''); ?>>
                                <?php echo e($tipoPersonal->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol_id" required>
                        <option value="">Seleccionar rol...</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($rol->id); ?>" <?php echo e(old('rol_id') == $rol->id ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($rol->nombre)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Sucursal</label>
                    <select name="sucursal_id" required>
                        <option value="">Seleccionar sucursal...</option>
                        <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sucursal->id); ?>"><?php echo e($sucursal->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Crear Personal</button>
            </form>

            <a href="<?php echo e(route('personal.index')); ?>" class="link-back">← Volver</a>
        </div>
    </div>

    <script>
        if (!localStorage.getItem('auth_token')) window.location.href = '/login';
    </script>
</body>
</html>
<?php /**PATH /workspace/resources/views/personal/crear.blade.php ENDPATH**/ ?>