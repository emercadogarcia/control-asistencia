<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sucursales</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #0f172a; }
        header { background: #ffffff; border-bottom: 1px solid #e2e8f0; }
        .container { max-width: 1180px; margin: 0 auto; padding: 0 20px; }
        .header-inner { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 22px 0; }
        .header-inner h1 { font-size: 30px; font-weight: 700; }
        .header-inner p { color: #64748b; margin-top: 4px; }
        .page { padding: 28px 0 40px; }
        .layout { display: grid; grid-template-columns: 340px minmax(0, 1fr); gap: 22px; align-items: start; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 22px; box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06); }
        .card h2 { font-size: 20px; margin-bottom: 8px; }
        .card p { color: #64748b; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; border-radius: 14px; padding: 14px 16px; margin-bottom: 18px; }
        .error-box { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 14px; padding: 14px 16px; margin-top: 16px; }
        .field { margin-top: 16px; }
        .field label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155; }
        .field input, .field textarea { width: 100%; padding: 11px 13px; border: 1px solid #cbd5e1; border-radius: 14px; background: #fff; font-size: 14px; }
        .field textarea { min-height: 110px; resize: vertical; }
        .field input:focus, .field textarea:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12); }
        .form-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; border-radius: 14px; border: 1px solid transparent; padding: 11px 15px; font-weight: 600; text-decoration: none; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 10px 18px rgba(15, 23, 42, 0.1); }
        .btn-primary { background: #0f172a; color: white; }
        .btn-secondary { background: #e2e8f0; color: #0f172a; }
        .metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
        .metric { background: white; border: 1px solid #e2e8f0; border-radius: 18px; padding: 18px; }
        .metric span { display: block; color: #64748b; font-size: 13px; margin-bottom: 8px; }
        .metric strong { font-size: 28px; }
        .table-wrap { overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 18px; }
        table { width: 100%; border-collapse: collapse; min-width: 820px; background: white; }
        th, td { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; text-align: left; vertical-align: top; }
        th { color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; background: #f8fafc; }
        tbody tr:hover { background: #f8fafc; }
        .badge { display: inline-flex; align-items: center; padding: 5px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; background: #dcfce7; color: #166534; }
        .actions { display: flex; align-items: center; gap: 8px; }
        .icon-btn { width: 36px; height: 36px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #e2e8f0; background: white; color: #334155; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease; }
        .icon-btn:hover { transform: translateY(-1px); box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08); }
        .icon-btn.delete:hover { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .empty-state { text-align: center; padding: 28px; color: #64748b; }
        .back-link { display: inline-flex; align-items: center; margin-top: 18px; color: #475569; text-decoration: none; }
        @media (max-width: 980px) {
            .layout { grid-template-columns: 1fr; }
            .metrics { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-inner">
            <div>
                <h1>Sucursales</h1>
                <p>Administra los puntos operativos con el mismo formato minimalista de configuracion.</p>
            </div>
            <a href="{{ route('configuracion.index') }}" class="btn btn-secondary">Configuracion</a>
        </div>
    </header>

    <main class="container page">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="layout">
            <section class="card">
                <h2>{{ $sucursalEdit ? 'Editar sucursal' : 'Nueva sucursal' }}</h2>
                <p>{{ $sucursalEdit ? 'Actualiza la informacion sin salir del modulo.' : 'Registra una sucursal con sus datos base de operacion.' }}</p>

                @if(isset($errors) && $errors->any())
                    <div class="error-box">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ $sucursalEdit ? route('configuracion.sucursales.actualizar', $sucursalEdit->id) : route('configuracion.sucursales.crear') }}">
                    @csrf

                    <div class="field">
                        <label for="nombre">Nombre</label>
                        <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $sucursalEdit->nombre ?? '') }}" placeholder="Ej. Central, Norte o Planta 2" required>
                    </div>

                    <div class="field">
                        <label for="direccion">Direccion</label>
                        <textarea id="direccion" name="direccion" placeholder="Describe la direccion o referencia principal">{{ old('direccion', $sucursalEdit->direccion ?? '') }}</textarea>
                    </div>

                    <div class="field">
                        <label for="telefono">Telefono</label>
                        <input id="telefono" type="text" name="telefono" value="{{ old('telefono', $sucursalEdit->telefono ?? '') }}" placeholder="Ej. 70000000">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">{{ $sucursalEdit ? 'Guardar cambios' : 'Crear sucursal' }}</button>
                        @if($sucursalEdit)
                            <a href="{{ route('configuracion.sucursales') }}" class="btn btn-secondary">Cancelar</a>
                        @endif
                    </div>
                </form>
            </section>

            <section>
                <div class="metrics">
                    <div class="metric">
                        <span>Sucursales activas</span>
                        <strong>{{ $sucursales->total() }}</strong>
                    </div>
                    <div class="metric">
                        <span>Personal asignado</span>
                        <strong>{{ $sucursales->sum('personals_count') }}</strong>
                    </div>
                    <div class="metric">
                        <span>Turnos configurados</span>
                        <strong>{{ $sucursales->sum('turnos_count') }}</strong>
                    </div>
                </div>

                <div class="card">
                    <h2>Listado de sucursales</h2>
                    <p>Consulta rapidamente los datos principales y usa acciones iconograficas unificadas.</p>

                    <div class="table-wrap" style="margin-top: 18px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sucursal</th>
                                    <th>Direccion</th>
                                    <th>Telefono</th>
                                    <th>Personal</th>
                                    <th>Turnos</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sucursales as $sucursal)
                                    <tr>
                                        <td><strong>{{ $sucursal->nombre }}</strong></td>
                                        <td>{{ $sucursal->direccion ?: 'Sin direccion' }}</td>
                                        <td>{{ $sucursal->telefono ?: 'Sin telefono' }}</td>
                                        <td>{{ $sucursal->personals_count }}</td>
                                        <td>{{ $sucursal->turnos_count }}</td>
                                        <td><span class="badge">Activa</span></td>
                                        <td>
                                            <div class="actions">
                                                <a href="{{ route('configuracion.sucursales.editar', $sucursal->id) }}" class="icon-btn" title="Editar">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                        <path d="M4 20h4l10.5-10.5-4-4L4 16v4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                                        <path d="m12.5 7.5 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('configuracion.sucursales.eliminar', $sucursal->id) }}" class="icon-btn delete" title="Dar de baja" onclick="return confirm('Se dara de baja esta sucursal. Deseas continuar?')">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                        <path d="M4 7h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                        <path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                                        <path d="M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                                        <path d="M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="empty-state">No hay sucursales registradas por ahora.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 16px;">
                        {{ $sucursales->appends(request()->query())->links() }}
                    </div>
                </div>
            </section>
        </div>

        <a href="{{ route('configuracion.index') }}" class="back-link">← Volver al modulo de configuracion</a>
    </main>

    <script>
        if (!localStorage.getItem('auth_token')) window.location.href = '/login';
    </script>
</body>
</html>
