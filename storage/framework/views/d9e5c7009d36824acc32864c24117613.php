<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📋</text></svg>">
    <title>Personal - Control de Asistencia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: linear-gradient(180deg, #e0f2fe 0%, #f8fafc 220px); color: #0f172a; }
        header { background: linear-gradient(135deg, #0369a1 0%, #0284c7 55%, #38bdf8 100%); color: white; padding: 24px 0; box-shadow: 0 18px 45px rgba(3, 105, 161, 0.25); }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 20px; }
        .header-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        .header-copy h1 { font-size: 32px; margin-bottom: 6px; }
        .header-copy p { color: rgba(255, 255, 255, 0.9); }
        .logout-btn { color: white; padding: 10px 16px; border-radius: 999px; background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.22); cursor: pointer; transition: background 0.2s ease, transform 0.2s ease; }
        .logout-btn:hover { background: rgba(255, 255, 255, 0.28); transform: translateY(-1px); }
        main { padding: 28px 0 36px; }
        .card { background: rgba(255, 255, 255, 0.94); border: 1px solid rgba(148, 163, 184, 0.18); border-radius: 22px; padding: 24px; margin-bottom: 22px; box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08); backdrop-filter: blur(12px); }
        .card h2 { color: #075985; margin-bottom: 10px; }
        .card p { color: #475569; }
        .action-bar { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-top: 20px; }
        .nav-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .nav-link { display: inline-flex; align-items: center; justify-content: center; padding: 12px 18px; border-radius: 14px; text-decoration: none; font-weight: 600; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease; }
        .nav-link:hover { transform: translateY(-1px); box-shadow: 0 10px 18px rgba(2, 132, 199, 0.14); }
        .nav-link.primary { background: #0284c7; color: white; }
        .nav-link.secondary { background: #e0f2fe; color: #075985; }
        .summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; margin-top: 22px; }
        .metric { background: linear-gradient(180deg, #f8fafc 0%, #eff6ff 100%); border: 1px solid #dbeafe; border-radius: 18px; padding: 18px; }
        .metric span { display: block; font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 8px; }
        .metric strong { font-size: 28px; color: #0f172a; }
        .filters { display: grid; grid-template-columns: 1.4fr 1fr auto; gap: 14px; align-items: end; }
        .field label { display: block; margin-bottom: 8px; color: #334155; font-weight: 600; }
        .field input, .field select { width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 14px; font-size: 14px; background: white; color: #0f172a; }
        .field input:focus, .field select:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12); }
        .filter-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { border: none; border-radius: 14px; padding: 12px 16px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 10px 18px rgba(15, 23, 42, 0.12); }
        .btn-info { background: #0ea5e9; color: white; }
        .btn-edit { background: #e0f2fe; color: #075985; }
        .btn-danger { background: #fee2e2; color: #b91c1c; }
        .btn-neutral { background: #e2e8f0; color: #0f172a; }
        .table-wrap { overflow-x: auto; border-radius: 18px; border: 1px solid #e2e8f0; margin-top: 22px; }
        table { width: 100%; border-collapse: collapse; min-width: 1080px; background: white; }
        thead { background: #f8fafc; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        th { font-size: 13px; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; }
        tbody tr:hover { background: #f8fbff; }
        .person-name strong { display: block; font-size: 15px; }
        .person-name small, td small { color: #64748b; }
        .badge { display: inline-flex; align-items: center; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .badge.active { background: #dcfce7; color: #166534; }
        .badge.inactive { background: #fee2e2; color: #b91c1c; }
        .action-cell { display: flex; gap: 8px; flex-wrap: wrap; }
        .empty-state { text-align: center; padding: 32px; color: #64748b; }
        .pagination { margin-top: 16px; }
        .pagination nav { display: flex; justify-content: center; }
        .pagination svg { width: 16px; height: 16px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; border-radius: 16px; padding: 14px 16px; margin-bottom: 18px; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); display: none; align-items: center; justify-content: center; padding: 24px; z-index: 1000; }
        .modal-overlay.active { display: flex; }
        .modal-card { width: min(720px, 100%); max-height: 85vh; overflow-y: auto; background: white; border-radius: 24px; padding: 24px; box-shadow: 0 30px 80px rgba(15, 23, 42, 0.3); }
        .modal-head { display: flex; align-items: start; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
        .modal-head h3 { color: #0f172a; font-size: 24px; }
        .modal-close { background: #e2e8f0; color: #0f172a; border: none; width: 38px; height: 38px; border-radius: 999px; cursor: pointer; font-size: 18px; }
        .modal-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; }
        .modal-item { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 14px; }
        .modal-item span { display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 6px; }
        .modal-item strong { color: #0f172a; font-size: 15px; word-break: break-word; }
        @media (max-width: 860px) {
            .header-row { flex-direction: column; align-items: flex-start; }
            .filters { grid-template-columns: 1fr; }
            .filter-actions { width: 100%; }
            .filter-actions .btn { flex: 1; }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-row">
            <div class="header-copy">
                <h1>Gestion de Personal</h1>
                <p>Consulta, organiza y administra el personal registrado desde una sola vista.</p>
            </div>
            <button class="logout-btn" onclick="logout()">Cerrar Sesion</button>
        </div>
    </header>

    <main class="container">
        <?php if(session('success')): ?>
            <div class="alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <section class="card">
            <h2>Panel de navegacion</h2>
            <p>Los accesos principales se mantienen arriba para que la gestion sea rapida y clara.</p>

            <div class="action-bar">
                <div class="nav-actions">
                    <a href="/dashboard" class="nav-link secondary">Volver al Dashboard</a>
                    <a href="/personal/crear" class="nav-link primary">Agregar Personal</a>
                </div>
            </div>

            <div class="summary">
                <div class="metric">
                    <span>Total registrados</span>
                    <strong><?php echo e($personals->total()); ?></strong>
                </div>
                <div class="metric">
                    <span>Activos en esta pagina</span>
                    <strong><?php echo e($personals->where('estado', 1)->count()); ?></strong>
                </div>
                <div class="metric">
                    <span>Con sucursal asignada</span>
                    <strong><?php echo e($personals->filter(fn ($personal) => !empty($personal->sucursal))->count()); ?></strong>
                </div>
            </div>
        </section>

        <section class="card">
            <h2>Personal registrado</h2>
            <p>Debajo de los botones ahora tienes una vista completa del personal con datos relevantes y acciones directas.</p>

            <form method="GET" action="<?php echo e(route('personal.index')); ?>" class="filters" style="margin-top: 20px;">
                <div class="field">
                    <label for="search">Buscar por nombre, CI o email</label>
                    <input id="search" type="text" name="search" value="<?php echo e($search); ?>" placeholder="Ej. Maria, 1234567 o correo@empresa.com">
                </div>

                <div class="field">
                    <label for="sucursal_id">Filtrar por sucursal</label>
                    <select id="sucursal_id" name="sucursal_id">
                        <option value="">Todas las sucursales</option>
                        <?php $__currentLoopData = $sucursales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucursal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sucursal->id); ?>" <?php echo e((string) $sucursal_id === (string) $sucursal->id ? 'selected' : ''); ?>>
                                <?php echo e($sucursal->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-info">Filtrar</button>
                    <a href="<?php echo e(route('personal.index')); ?>" class="btn btn-neutral">Limpiar</a>
                </div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Personal</th>
                            <th>CI</th>
                            <th>Contacto</th>
                            <th>Sucursal</th>
                            <th>Tipo</th>
                            <th>Rol</th>
                            <th>Ingreso</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $personals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $personal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="person-name">
                                        <strong><?php echo e($personal->nombre); ?> <?php echo e($personal->apellido); ?></strong>
                                        <small>ID #<?php echo e($personal->id); ?></small>
                                    </div>
                                </td>
                                <td><?php echo e($personal->ci); ?></td>
                                <td>
                                    <div><?php echo e($personal->email ?: 'Sin email'); ?></div>
                                    <small><?php echo e($personal->telefono ?: 'Sin telefono'); ?></small>
                                </td>
                                <td><?php echo e($personal->sucursal->nombre ?? 'Sin sucursal'); ?></td>
                                <td><?php echo e($personal->tipoPersonal->nombre ?? 'Sin tipo'); ?></td>
                                <td><?php echo e($personal->rol ? ucfirst($personal->rol->nombre) : 'Sin rol'); ?></td>
                                <td><?php echo e($personal->fecha_contratacion ? \Illuminate\Support\Carbon::parse($personal->fecha_contratacion)->format('d/m/Y') : 'No registrado'); ?></td>
                                <td>
                                    <span class="badge <?php echo e((int) $personal->estado === 1 ? 'active' : 'inactive'); ?>">
                                        <?php echo e((int) $personal->estado === 1 ? 'Activo' : 'Inactivo'); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="action-cell">
                                        <button
                                            type="button"
                                            class="btn btn-info"
                                            data-modal-open
                                            data-personal="<?php echo e(e(json_encode([
                                                'nombreCompleto' => trim($personal->nombre . ' ' . $personal->apellido),
                                                'id' => $personal->id,
                                                'ci' => $personal->ci,
                                                'email' => $personal->email ?: 'Sin email',
                                                'telefono' => $personal->telefono ?: 'Sin telefono',
                                                'direccion' => $personal->direccion ?: 'Sin direccion',
                                                'fechaNacimiento' => $personal->fecha_nacimiento ? \Illuminate\Support\Carbon::parse($personal->fecha_nacimiento)->format('d/m/Y') : 'No registrado',
                                                'fechaContratacion' => $personal->fecha_contratacion ? \Illuminate\Support\Carbon::parse($personal->fecha_contratacion)->format('d/m/Y') : 'No registrado',
                                                'sucursal' => $personal->sucursal->nombre ?? 'Sin sucursal',
                                                'tipoPersonal' => $personal->tipoPersonal->nombre ?? 'Sin tipo',
                                                'rol' => $personal->rol ? ucfirst($personal->rol->nombre) : 'Sin rol',
                                                'estado' => (int) $personal->estado === 1 ? 'Activo' : 'Inactivo',
                                            ]))); ?>"
                                        >
                                            Ver
                                        </button>
                                        <a href="<?php echo e(route('personal.editar', $personal->id)); ?>" class="btn btn-edit">Editar</a>
                                        <?php if((int) $personal->estado === 1): ?>
                                            <a href="<?php echo e(route('personal.eliminar', $personal->id)); ?>" class="btn btn-danger" onclick="return confirm('Se dara de baja a este registro. Deseas continuar?')">Baja</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="empty-state">No hay personal registrado con los filtros actuales.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <?php echo e($personals->appends(request()->query())->links()); ?>

            </div>
        </section>
    </main>

    <div class="modal-overlay" id="personalModal" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <div class="modal-head">
                <div>
                    <h3 id="modalTitle">Detalle del personal</h3>
                    <p style="color: #64748b;">Vista rapida del registro seleccionado.</p>
                </div>
                <button type="button" class="modal-close" id="modalClose" aria-label="Cerrar">×</button>
            </div>

            <div class="modal-grid">
                <div class="modal-item"><span>Nombre completo</span><strong data-field="nombreCompleto">-</strong></div>
                <div class="modal-item"><span>ID</span><strong data-field="id">-</strong></div>
                <div class="modal-item"><span>CI</span><strong data-field="ci">-</strong></div>
                <div class="modal-item"><span>Email</span><strong data-field="email">-</strong></div>
                <div class="modal-item"><span>Telefono</span><strong data-field="telefono">-</strong></div>
                <div class="modal-item"><span>Direccion</span><strong data-field="direccion">-</strong></div>
                <div class="modal-item"><span>Fecha de nacimiento</span><strong data-field="fechaNacimiento">-</strong></div>
                <div class="modal-item"><span>Fecha de contratacion</span><strong data-field="fechaContratacion">-</strong></div>
                <div class="modal-item"><span>Sucursal</span><strong data-field="sucursal">-</strong></div>
                <div class="modal-item"><span>Tipo de personal</span><strong data-field="tipoPersonal">-</strong></div>
                <div class="modal-item"><span>Rol</span><strong data-field="rol">-</strong></div>
                <div class="modal-item"><span>Estado</span><strong data-field="estado">-</strong></div>
            </div>
        </div>
    </div>

    <script>
        function logout() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                fetch('/api/logout', { method: 'POST', headers: { 'Authorization': `Bearer ${token}` } })
                    .then(() => { localStorage.removeItem('auth_token'); window.location.href = '/login'; });
            } else {
                window.location.href = '/login';
            }
        }

        if (!localStorage.getItem('auth_token')) {
            window.location.href = '/login';
        }

        const modal = document.getElementById('personalModal');
        const modalClose = document.getElementById('modalClose');
        const modalFields = modal.querySelectorAll('[data-field]');

        function closeModal() {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }

        function openModal(data) {
            modalFields.forEach((field) => {
                const key = field.dataset.field;
                field.textContent = data[key] ?? '-';
            });

            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
        }

        document.querySelectorAll('[data-modal-open]').forEach((button) => {
            button.addEventListener('click', () => {
                const payload = JSON.parse(button.getAttribute('data-personal'));
                openModal(payload);
            });
        });

        modalClose.addEventListener('click', closeModal);

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
    </script>
</body>
</html>
<?php /**PATH /workspace/resources/views/personal/index.blade.php ENDPATH**/ ?>