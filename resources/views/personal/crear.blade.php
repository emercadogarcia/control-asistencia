<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Personal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #0f172a; }
        header { background: #ffffff; border-bottom: 1px solid #e2e8f0; }
        .container { max-width: 820px; margin: 0 auto; padding: 0 20px; }
        .header-inner { padding: 22px 0; }
        .header-inner h1 { font-size: 30px; }
        .header-inner p { color: #64748b; margin-top: 6px; }
        .page { padding: 28px 0 40px; }
        .form-card { background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 24px; box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06); }
        h2 { margin-bottom: 8px; }
        .intro { color: #64748b; margin-bottom: 18px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .full { grid-column: 1 / -1; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #334155; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 11px 13px; border: 1px solid #cbd5e1; border-radius: 14px; font-size: 14px; background: white; }
        textarea { min-height: 98px; resize: vertical; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12); }
        .section-divider { margin: 22px 0 14px; padding-top: 18px; border-top: 1px solid #e2e8f0; }
        .section-divider h3 { font-size: 16px; margin-bottom: 6px; }
        .section-divider p, .field-hint { color: #64748b; font-size: 14px; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; border-radius: 14px; padding: 11px 15px; text-decoration: none; font-weight: 600; border: 1px solid transparent; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 10px 18px rgba(15, 23, 42, 0.1); }
        .btn-submit { background: #0f172a; color: white; }
        .link-back { background: #e2e8f0; color: #0f172a; }
        .alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 16px; padding: 14px 16px; margin-bottom: 18px; }
        .alert-error ul { margin-left: 18px; margin-top: 10px; }
        @media (max-width: 700px) { .form-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header>
        <div class="container header-inner">
            <h1>Crear personal</h1>
            <p>Registro con el mismo formato limpio del proyecto, ahora incluyendo asignacion de turno.</p>
        </div>
    </header>

    <main class="container page">
        <div class="form-card">
            <h2>Registrar nuevo personal</h2>
            <p class="intro">Completa la informacion principal y, si corresponde, deja su turno listo para asistencia.</p>

            @if(isset($errors) && $errors->any())
                <div class="alert-error">
                    <strong>Hay datos por revisar.</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('personal.guardar') }}">
                @csrf
                <div class="form-grid">
                    <div>
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required>
                    </div>
                    <div>
                        <label>Apellido</label>
                        <input type="text" name="apellido" value="{{ old('apellido') }}" required>
                    </div>
                    <div>
                        <label>CI</label>
                        <input type="text" name="ci" value="{{ old('ci') }}" required>
                    </div>
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div>
                        <label>Telefono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}">
                    </div>
                    <div>
                        <label>Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                    </div>
                    <div class="full">
                        <label>Direccion</label>
                        <textarea name="direccion">{{ old('direccion') }}</textarea>
                    </div>
                    <div>
                        <label>Fecha de contratacion</label>
                        <input type="date" name="fecha_contratacion" value="{{ old('fecha_contratacion', date('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label>Tipo de personal</label>
                        <select name="tipo_personal_id" required>
                            <option value="">Seleccionar...</option>
                            @foreach($tiposPersonal as $tipoPersonal)
                                <option value="{{ $tipoPersonal->id }}" {{ old('tipo_personal_id') == $tipoPersonal->id ? 'selected' : '' }}>{{ $tipoPersonal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Rol</label>
                        <select name="rol_id" required>
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>{{ ucfirst($rol->nombre) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Sucursal</label>
                        <select name="sucursal_id" id="sucursal_id" required>
                            <option value="">Seleccionar sucursal...</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" {{ old('sucursal_id') == $sucursal->id ? 'selected' : '' }}>{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="section-divider">
                    <h3>Asignacion de turno</h3>
                    <p>Si el personal va a marcar asistencia, conviene salir de aqui con turno vigente configurado.</p>
                </div>

                <div class="form-grid">
                    <div>
                        <label>Turno</label>
                        <select name="turno_id" id="turno_id">
                            <option value="">Sin turno por ahora</option>
                            @foreach($turnos as $turno)
                                <option value="{{ $turno->id }}" data-sucursal="{{ $turno->sucursal_id }}" {{ old('turno_id') == $turno->id ? 'selected' : '' }}>{{ $turno->nombre }} - {{ $turno->sucursal->nombre ?? 'Sin sucursal' }}</option>
                            @endforeach
                        </select>
                        <div class="field-hint">Solo se consideran turnos de la sucursal elegida.</div>
                    </div>
                    <div>
                        <label>Fecha de inicio del turno</label>
                        <input type="date" name="fecha_inicio_turno" value="{{ old('fecha_inicio_turno', old('fecha_contratacion', date('Y-m-d'))) }}">
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-submit">Crear personal</button>
                    <a href="{{ route('personal.index') }}" class="btn link-back">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        if (!localStorage.getItem('auth_token')) window.location.href = '/login';

        const sucursalSelect = document.getElementById('sucursal_id');
        const turnoSelect = document.getElementById('turno_id');

        function syncTurnos() {
            const sucursalId = String(sucursalSelect.value || '');
            Array.from(turnoSelect.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                option.hidden = sucursalId && option.dataset.sucursal !== sucursalId;
            });

            if (turnoSelect.selectedOptions[0] && turnoSelect.selectedOptions[0].hidden) {
                turnoSelect.value = '';
            }
        }

        sucursalSelect.addEventListener('change', syncTurnos);
        syncTurnos();
    </script>
</body>
</html>
