<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📋</text></svg>">
    <title>Personal - Control de Asistencia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #0f172a; }
        header { background: #ffffff; color: #0f172a; padding: 24px 0; border-bottom: 1px solid #e2e8f0; }
        .container { max-width: 1320px; margin: 0 auto; padding: 0 20px; }
        .header-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        .header-copy h1 { font-size: 32px; margin-bottom: 6px; }
        .header-copy p { color: #64748b; }
        .logout-btn { color: #0f172a; padding: 10px 16px; border-radius: 14px; background: #e2e8f0; border: 1px solid transparent; cursor: pointer; transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease; }
        .logout-btn:hover { background: #cbd5e1; transform: translateY(-1px); box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08); }
        main { padding: 28px 0 36px; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 22px; padding: 24px; margin-bottom: 22px; box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06); }
        .card h2 { color: #0f172a; margin-bottom: 10px; }
        .card p { color: #475569; }
        .action-bar { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-top: 20px; }
        .nav-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .nav-link, .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 18px; border-radius: 14px; text-decoration: none; font-weight: 600; border: 1px solid transparent; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease; }
        .nav-link:hover, .btn:hover { transform: translateY(-1px); box-shadow: 0 10px 18px rgba(15, 23, 42, 0.12); }
        .nav-link.primary, .btn-primary { background: #0f172a; color: white; }
        .nav-link.secondary, .btn-neutral { background: #e2e8f0; color: #0f172a; }
        .summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; margin-top: 22px; }
        .metric { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 18px; }
        .metric span { display: block; font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 8px; }
        .metric strong { font-size: 28px; color: #0f172a; }
        .filters { display: grid; grid-template-columns: 1.4fr 1fr auto; gap: 14px; align-items: end; }
        .field label { display: block; margin-bottom: 8px; color: #334155; font-weight: 600; }
        .field input, .field select, .field textarea { width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 14px; font-size: 14px; background: white; color: #0f172a; }
        .field textarea { min-height: 98px; resize: vertical; }
        .field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12); }
        .field-hint { margin-top: 8px; color: #64748b; font-size: 13px; }
        .filter-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .table-wrap { overflow-x: auto; border-radius: 18px; border: 1px solid #e2e8f0; margin-top: 22px; }
        table { width: 100%; border-collapse: collapse; min-width: 1220px; background: white; }
        thead { background: #f8fafc; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        th { font-size: 13px; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; }
        tbody tr:hover { background: #f8fbff; }
        .person-name strong { display: block; font-size: 15px; }
        .person-name small, td small { color: #64748b; }
        .badge { display: inline-flex; align-items: center; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .badge.active { background: #dcfce7; color: #166534; }
        .badge.inactive { background: #fee2e2; color: #b91c1c; }
        .badge.turno { background: #dbeafe; color: #1d4ed8; }
        .badge.turno-empty { background: #e2e8f0; color: #475569; }
        .action-col { width: 1%; white-space: nowrap; }
        .action-cell { display: flex; align-items: center; gap: 8px; flex-wrap: nowrap; }
        .icon-btn { width: 36px; height: 36px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #e2e8f0; background: white; color: #334155; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease; cursor: pointer; }
        .icon-btn:hover { transform: translateY(-1px); box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08); }
        .icon-btn.assign:hover { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .icon-btn.delete:hover { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .empty-state { text-align: center; padding: 32px; color: #64748b; }
        .pagination { margin-top: 16px; }
        .pagination nav { display: flex; justify-content: center; }
        .pagination svg { width: 16px; height: 16px; }
        .alert-success, .alert-error { border-radius: 16px; padding: 14px 16px; margin-bottom: 18px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-error ul { margin-left: 18px; margin-top: 10px; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); display: none; align-items: center; justify-content: center; padding: 24px; z-index: 1000; }
        .modal-overlay.active { display: flex; }
        .modal-card { width: min(920px, 100%); max-height: 88vh; overflow-y: auto; background: white; border-radius: 24px; padding: 24px; box-shadow: 0 30px 80px rgba(15, 23, 42, 0.3); }
        .modal-card.compact { width: min(560px, 100%); }
        .modal-head { display: flex; align-items: start; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
        .modal-head h3 { color: #0f172a; font-size: 24px; }
        .modal-head p { color: #64748b; margin-top: 6px; }
        .modal-close { background: #e2e8f0; color: #0f172a; border: none; width: 38px; height: 38px; border-radius: 999px; cursor: pointer; font-size: 18px; }
        .modal-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; }
        .modal-item { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 14px; }
        .modal-item span { display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 6px; }
        .modal-item strong { color: #0f172a; font-size: 15px; word-break: break-word; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .full { grid-column: 1 / -1; }
        .section-divider { margin: 22px 0 14px; padding-top: 18px; border-top: 1px solid #e2e8f0; }
        .section-divider h4 { margin-bottom: 6px; }
        .section-divider p { font-size: 14px; color: #64748b; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap; margin-top: 22px; }
        @media (max-width: 900px) {
            .header-row { flex-direction: column; align-items: flex-start; }
            .filters, .form-grid { grid-template-columns: 1fr; }
            .filter-actions { width: 100%; }
            .filter-actions .btn { flex: 1; }
        }
    </style>
</head>
<body>
    @php
        $personalsPage = $personals->getCollection();
        $turnosAsignadosPagina = $personalsPage->filter(fn ($personal) => optional($personal->turnoVigente)->turno)->count();
        $formMode = old('form_mode');
        $assigningPersonalId = old('assigning_personal_id');
        $personDefaultsJs = [
            'nombre' => old('nombre'),
            'apellido' => old('apellido'),
            'ci' => old('ci'),
            'email' => old('email'),
            'telefono' => old('telefono'),
            'direccion' => old('direccion', ''),
            'fecha_nacimiento' => old('fecha_nacimiento'),
            'fecha_contratacion' => old('fecha_contratacion', now()->format('Y-m-d')),
            'tipo_personal_id' => old('tipo_personal_id'),
            'rol_id' => old('rol_id'),
            'sucursal_id' => old('sucursal_id'),
            'turno_id' => old('turno_id'),
            'fecha_inicio_turno' => old('fecha_inicio_turno', now()->format('Y-m-d')),
        ];
    @endphp

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
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert-error">
                <strong>No pudimos guardar los cambios todavia.</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="card">
            <h2>Panel de navegacion</h2>
            <p>Los accesos principales se mantienen arriba para que la gestion sea rapida y clara.</p>

            <div class="action-bar">
                <div class="nav-actions">
                    <a href="/dashboard" class="nav-link secondary">Volver al Dashboard</a>
                    <button type="button" class="nav-link primary" id="openCreateModal">Agregar Personal</button>
                </div>
            </div>

            <div class="summary">
                <div class="metric">
                    <span>Total registrados</span>
                    <strong>{{ $personals->total() }}</strong>
                </div>
                <div class="metric">
                    <span>Activos en esta pagina</span>
                    <strong>{{ $personalsPage->where('estado', 1)->count() }}</strong>
                </div>
                <div class="metric">
                    <span>Con sucursal asignada</span>
                    <strong>{{ $personalsPage->filter(fn ($personal) => !empty($personal->sucursal))->count() }}</strong>
                </div>
                <div class="metric">
                    <span>Personal con turno asignado</span>
                    <strong>{{ $turnosAsignadosPagina }}</strong>
                </div>
            </div>
        </section>

        <section class="card">
            <h2>Personal registrado</h2>
            <p>La tabla ahora muestra el turno vigente y acciones directas sin desalinear la fila.</p>

            <form method="GET" action="{{ route('personal.index') }}" class="filters" style="margin-top: 20px;">
                <div class="field">
                    <label for="search">Buscar por nombre, CI o email</label>
                    <input id="search" type="text" name="search" value="{{ $search }}" placeholder="Ej. Maria, 1234567 o correo@empresa.com">
                </div>

                <div class="field">
                    <label for="sucursal_id">Filtrar por sucursal</label>
                    <select id="sucursal_id" name="sucursal_id">
                        <option value="">Todas las sucursales</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}" {{ (string) $sucursal_id === (string) $sucursal->id ? 'selected' : '' }}>
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('personal.index') }}" class="btn btn-neutral">Limpiar</a>
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
                            <th>Turno</th>
                            <th>Ingreso</th>
                            <th>Estado</th>
                            <th class="action-col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personals as $personal)
                            @php
                                $turnoVigente = optional($personal->turnoVigente)->turno;
                                $turnoNombre = $turnoVigente->nombre ?? 'Sin turno';
                                $fechaInicioTurno = optional(optional($personal->turnoVigente)->fecha_inicio)->format('Y-m-d')
                                    ?? ($personal->fecha_contratacion ? 
                                        \Illuminate\Support\Carbon::parse($personal->fecha_contratacion)->format('Y-m-d') : now()->format('Y-m-d'));
                                $detailPayload = [
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
                                    'turno' => $turnoNombre,
                                    'estado' => (int) $personal->estado === 1 ? 'Activo' : 'Inactivo',
                                ];
                                $editPayload = [
                                    'id' => $personal->id,
                                    'nombre' => $personal->nombre,
                                    'apellido' => $personal->apellido,
                                    'ci' => $personal->ci,
                                    'email' => $personal->email,
                                    'telefono' => $personal->telefono,
                                    'direccion' => $personal->direccion,
                                    'fecha_nacimiento' => $personal->fecha_nacimiento ? \Illuminate\Support\Carbon::parse($personal->fecha_nacimiento)->format('Y-m-d') : '',
                                    'fecha_contratacion' => $personal->fecha_contratacion ? \Illuminate\Support\Carbon::parse($personal->fecha_contratacion)->format('Y-m-d') : '',
                                    'tipo_personal_id' => $personal->tipo_personal_id,
                                    'rol_id' => $personal->rol_id,
                                    'sucursal_id' => $personal->sucursal_id,
                                    'turno_id' => $turnoVigente->id ?? '',
                                    'fecha_inicio_turno' => $fechaInicioTurno,
                                ];
                                $assignPayload = [
                                    'id' => $personal->id,
                                    'nombreCompleto' => trim($personal->nombre . ' ' . $personal->apellido),
                                    'sucursal_id' => $personal->sucursal_id,
                                    'turno_id' => $turnoVigente->id ?? '',
                                    'fecha_inicio_turno' => $fechaInicioTurno,
                                ];
                            @endphp
                            <tr>
                                <td>
                                    <div class="person-name">
                                        <strong>{{ $personal->nombre }} {{ $personal->apellido }}</strong>
                                        <small>ID #{{ $personal->id }}</small>
                                    </div>
                                </td>
                                <td>{{ $personal->ci }}</td>
                                <td>
                                    <div>{{ $personal->email ?: 'Sin email' }}</div>
                                    <small>{{ $personal->telefono ?: 'Sin telefono' }}</small>
                                </td>
                                <td>{{ $personal->sucursal->nombre ?? 'Sin sucursal' }}</td>
                                <td>{{ $personal->tipoPersonal->nombre ?? 'Sin tipo' }}</td>
                                <td>{{ $personal->rol ? ucfirst($personal->rol->nombre) : 'Sin rol' }}</td>
                                <td>
                                    <span class="badge {{ $turnoVigente ? 'turno' : 'turno-empty' }}">{{ $turnoNombre }}</span>
                                </td>
                                <td>{{ $personal->fecha_contratacion ? \Illuminate\Support\Carbon::parse($personal->fecha_contratacion)->format('d/m/Y') : 'No registrado' }}</td>
                                <td>
                                    <span class="badge {{ (int) $personal->estado === 1 ? 'active' : 'inactive' }}">
                                        {{ (int) $personal->estado === 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="action-col">
                                    <div class="action-cell">
                                        <button
                                            type="button"
                                            class="icon-btn"
                                            title="Ver detalle"
                                            data-modal-open
                                            data-personal='@json($detailPayload)'
                                        >
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                                <circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.8"/>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            class="icon-btn"
                                            title="Editar"
                                            data-edit-open
                                            data-personal-form='@json($editPayload)'
                                        >
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M4 20h4l10.5-10.5-4-4L4 16v4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                                <path d="m12.5 7.5 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            class="icon-btn assign"
                                            title="Asignar turno"
                                            data-assign-open
                                            data-assign='@json($assignPayload)'
                                        >
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M7 4v4M17 4v4M4 10h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                <rect x="4" y="6" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/>
                                                <path d="m9 15 2 2 4-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        @if((int) $personal->estado === 1)
                                            <a href="{{ route('personal.eliminar', $personal->id) }}" class="icon-btn delete" title="Dar de baja" onclick="return confirm('Se dara de baja a este registro. Deseas continuar?')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M4 7h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                    <path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                    <path d="M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                                    <path d="M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="empty-state">No hay personal registrado con los filtros actuales.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $personals->appends(request()->query())->links() }}
            </div>
        </section>
    </main>

    <div class="modal-overlay" id="personalModal" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <div class="modal-head">
                <div>
                    <h3 id="modalTitle">Detalle del personal</h3>
                    <p>Vista rapida del registro seleccionado.</p>
                </div>
                <button type="button" class="modal-close" data-modal-close="personalModal" aria-label="Cerrar">×</button>
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
                <div class="modal-item"><span>Turno vigente</span><strong data-field="turno">-</strong></div>
                <div class="modal-item"><span>Estado</span><strong data-field="estado">-</strong></div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="personFormModal" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="personFormTitle">
            <div class="modal-head">
                <div>
                    <h3 id="personFormTitle">Registrar personal</h3>
                    <p>Mantenemos un flujo minimalista, pero ahora incluyendo el turno desde el alta o la actualizacion.</p>
                </div>
                <button type="button" class="modal-close" data-modal-close="personFormModal" aria-label="Cerrar">×</button>
            </div>

            <form id="personForm" method="POST" action="{{ route('personal.guardar') }}">
                @csrf
                <input type="hidden" name="form_mode" id="form_mode" value="create">
                <input type="hidden" name="editing_personal_id" id="editing_personal_id" value="{{ old('editing_personal_id') }}">

                <div class="form-grid">
                    <div class="field">
                        <label for="nombre">Nombre</label>
                        <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="field">
                        <label for="apellido">Apellido</label>
                        <input id="apellido" type="text" name="apellido" value="{{ old('apellido') }}" required>
                    </div>
                    <div class="field">
                        <label for="ci">CI</label>
                        <input id="ci" type="text" name="ci" value="{{ old('ci') }}" required>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="field">
                        <label for="telefono">Telefono</label>
                        <input id="telefono" type="text" name="telefono" value="{{ old('telefono') }}">
                    </div>
                    <div class="field">
                        <label for="fecha_nacimiento">Fecha de nacimiento</label>
                        <input id="fecha_nacimiento" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                    </div>
                    <div class="field full">
                        <label for="direccion">Direccion</label>
                        <textarea id="direccion" name="direccion">{{ old('direccion') }}</textarea>
                    </div>
                    <div class="field">
                        <label for="fecha_contratacion">Fecha de contratacion</label>
                        <input id="fecha_contratacion" type="date" name="fecha_contratacion" value="{{ old('fecha_contratacion', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="field">
                        <label for="tipo_personal_id">Tipo de personal</label>
                        <select id="tipo_personal_id" name="tipo_personal_id" required>
                            <option value="">Seleccionar...</option>
                            @foreach($tiposPersonal as $tipoPersonal)
                                <option value="{{ $tipoPersonal->id }}">{{ $tipoPersonal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="rol_id">Rol</label>
                        <select id="rol_id" name="rol_id" required>
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}">{{ ucfirst($rol->nombre) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="sucursal_personal">Sucursal</label>
                        <select id="sucursal_personal" name="sucursal_id" required>
                            <option value="">Seleccionar sucursal...</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="section-divider">
                    <h4>Asignacion de turno</h4>
                    <p>Esto evita el caso operativo donde el personal queda registrado pero no puede marcar asistencia por no tener turno vigente.</p>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="turno_personal">Turno</label>
                        <select id="turno_personal" name="turno_id">
                            <option value="">Sin turno por ahora</option>
                            @foreach($turnos as $turno)
                                <option value="{{ $turno->id }}" data-sucursal="{{ $turno->sucursal_id }}">{{ $turno->nombre }} - {{ $turno->sucursal->nombre ?? 'Sin sucursal' }}</option>
                            @endforeach
                        </select>
                        <div class="field-hint">Solo se muestran turnos de la sucursal seleccionada.</div>
                    </div>
                    <div class="field">
                        <label for="fecha_inicio_turno">Fecha de inicio del turno</label>
                        <input id="fecha_inicio_turno" type="date" name="fecha_inicio_turno" value="{{ old('fecha_inicio_turno', now()->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-neutral" data-modal-close="personFormModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="personSubmit">Guardar personal</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="assignTurnoModal" aria-hidden="true">
        <div class="modal-card compact" role="dialog" aria-modal="true" aria-labelledby="assignTurnoTitle">
            <div class="modal-head">
                <div>
                    <h3 id="assignTurnoTitle">Asignar turno</h3>
                    <p>Actualiza el turno vigente directamente desde el listado.</p>
                </div>
                <button type="button" class="modal-close" data-modal-close="assignTurnoModal" aria-label="Cerrar">×</button>
            </div>

            <form id="assignTurnoForm" method="POST" action="">
                @csrf
                <input type="hidden" name="assigning_personal_id" id="assigning_personal_id" value="{{ old('assigning_personal_id') }}">

                <div class="field" style="margin-bottom: 16px;">
                    <label for="assign_personal_nombre">Personal</label>
                    <input id="assign_personal_nombre" type="text" readonly>
                </div>

                <div class="field" style="margin-bottom: 16px;">
                    <label for="assign_turno_id">Turno</label>
                    <select id="assign_turno_id" name="turno_id" required>
                        <option value="">Seleccionar turno...</option>
                        @foreach($turnos as $turno)
                            <option value="{{ $turno->id }}" data-sucursal="{{ $turno->sucursal_id }}">{{ $turno->nombre }} - {{ $turno->sucursal->nombre ?? 'Sin sucursal' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="assign_fecha_inicio_turno">Fecha de inicio</label>
                    <input id="assign_fecha_inicio_turno" type="date" name="fecha_inicio_turno" value="{{ old('fecha_inicio_turno', now()->format('Y-m-d')) }}">
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-neutral" data-modal-close="assignTurnoModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar turno</button>
                </div>
            </form>
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

        const detailModal = document.getElementById('personalModal');
        const detailFields = detailModal.querySelectorAll('[data-field]');
        const personFormModal = document.getElementById('personFormModal');
        const personForm = document.getElementById('personForm');
        const personSubmit = document.getElementById('personSubmit');
        const personFormTitle = document.getElementById('personFormTitle');
        const formModeInput = document.getElementById('form_mode');
        const editingPersonalId = document.getElementById('editing_personal_id');
        const formSucursal = document.getElementById('sucursal_personal');
        const formTurno = document.getElementById('turno_personal');
        const assignTurnoModal = document.getElementById('assignTurnoModal');
        const assignTurnoForm = document.getElementById('assignTurnoForm');
        const assignPersonalNombre = document.getElementById('assign_personal_nombre');
        const assignPersonalId = document.getElementById('assigning_personal_id');
        const assignTurno = document.getElementById('assign_turno_id');
        const assignFechaInicio = document.getElementById('assign_fecha_inicio_turno');
        const personDefaults = {{ \Illuminate\Support\Js::from($personDefaultsJs) }};

        function openModal(node) {
            node.classList.add('active');
            node.setAttribute('aria-hidden', 'false');
        }

        function closeModal(node) {
            node.classList.remove('active');
            node.setAttribute('aria-hidden', 'true');
        }

        function syncTurnoOptions(select, sucursalId, selectedValue) {
            const currentSucursal = String(sucursalId || '');
            Array.from(select.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                const optionSucursal = option.dataset.sucursal || '';
                const visible = !currentSucursal || optionSucursal === currentSucursal;
                option.hidden = !visible;
            });

            if (selectedValue) {
                const valid = Array.from(select.options).some((option) => option.value === String(selectedValue) && !option.hidden);
                select.value = valid ? String(selectedValue) : '';
            } else {
                select.value = '';
            }
        }

        function setFormValue(name, value) {
            const element = personForm.querySelector(`[name="${name}"]`);
            if (!element) return;
            element.value = value ?? '';
        }

        function openPersonForm(mode, data = {}) {
            const payload = { ...personDefaults, ...data };
            formModeInput.value = mode;
            editingPersonalId.value = payload.id || '';
            setFormValue('nombre', payload.nombre);
            setFormValue('apellido', payload.apellido);
            setFormValue('ci', payload.ci);
            setFormValue('email', payload.email);
            setFormValue('telefono', payload.telefono);
            setFormValue('direccion', payload.direccion);
            setFormValue('fecha_nacimiento', payload.fecha_nacimiento);
            setFormValue('fecha_contratacion', payload.fecha_contratacion || '{{ now()->format('Y-m-d') }}');
            setFormValue('tipo_personal_id', payload.tipo_personal_id);
            setFormValue('rol_id', payload.rol_id);
            setFormValue('sucursal_id', payload.sucursal_id);
            setFormValue('fecha_inicio_turno', payload.fecha_inicio_turno || payload.fecha_contratacion || '{{ now()->format('Y-m-d') }}');

            if (mode === 'edit') {
                personForm.action = `/personal/${payload.id}`;
                personFormTitle.textContent = 'Actualizar personal';
                personSubmit.textContent = 'Guardar cambios';
            } else {
                personForm.action = '{{ route('personal.guardar') }}';
                personFormTitle.textContent = 'Registrar personal';
                personSubmit.textContent = 'Guardar personal';
            }

            syncTurnoOptions(formTurno, payload.sucursal_id, payload.turno_id);
            openModal(personFormModal);
        }

        function openAssignForm(data) {
            assignTurnoForm.action = `/personal/${data.id}/asignar-turno`;
            assignPersonalNombre.value = data.nombreCompleto || '';
            assignPersonalId.value = data.id || '';
            assignFechaInicio.value = data.fecha_inicio_turno || '{{ now()->format('Y-m-d') }}';
            syncTurnoOptions(assignTurno, data.sucursal_id, data.turno_id);
            openModal(assignTurnoModal);
        }

        document.getElementById('openCreateModal').addEventListener('click', () => openPersonForm('create'));

        document.querySelectorAll('[data-modal-open]').forEach((button) => {
            button.addEventListener('click', () => {
                const payload = JSON.parse(button.dataset.personal);
                detailFields.forEach((field) => {
                    field.textContent = payload[field.dataset.field] ?? '-';
                });
                openModal(detailModal);
            });
        });

        document.querySelectorAll('[data-edit-open]').forEach((button) => {
            button.addEventListener('click', () => openPersonForm('edit', JSON.parse(button.dataset.personalForm)));
        });

        document.querySelectorAll('[data-assign-open]').forEach((button) => {
            button.addEventListener('click', () => openAssignForm(JSON.parse(button.dataset.assign)));
        });

        formSucursal.addEventListener('change', () => {
            syncTurnoOptions(formTurno, formSucursal.value, formTurno.value);
        });

        document.querySelectorAll('[data-modal-close]').forEach((button) => {
            button.addEventListener('click', () => closeModal(document.getElementById(button.dataset.modalClose)));
        });

        [detailModal, personFormModal, assignTurnoModal].forEach((overlay) => {
            overlay.addEventListener('click', (event) => {
                if (event.target === overlay) {
                    closeModal(overlay);
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;
            [assignTurnoModal, personFormModal, detailModal].forEach((overlay) => {
                if (overlay.classList.contains('active')) {
                    closeModal(overlay);
                }
            });
        });

        @if($formMode === 'create')
            openPersonForm('create');
        @elseif($formMode === 'edit')
            openPersonForm('edit', {
                id: '{{ old('editing_personal_id') }}',
                ...personDefaults
            });
        @elseif($assigningPersonalId)
            @php
                $assignedPerson = $personalsPage->firstWhere('id', (int) $assigningPersonalId);
            @endphp
            openAssignForm({
                id: '{{ $assigningPersonalId }}',
                nombreCompleto: @json($assignedPerson ? trim($assignedPerson->nombre . ' ' . $assignedPerson->apellido) : ''),
                sucursal_id: '{{ old('sucursal_id', $assignedPerson->sucursal_id ?? '') }}',
                turno_id: '{{ old('turno_id') }}',
                fecha_inicio_turno: '{{ old('fecha_inicio_turno', now()->format('Y-m-d')) }}'
            });
        @endif
    </script>
</body>
</html>
