<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Turnos Laborales</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; }
        header { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); color: white; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .section { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        h2 { color: #8b5cf6; margin-bottom: 20px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; }
        input, select { width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 4px; }
        .btn { background: #0284c7; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-edit { background: #f59e0b; padding: 6px 12px; margin-right: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f3f4f6; padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb; }
        td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
        .btn-delete { background: #dc2626; padding: 6px 12px; }
        .link-back { display: inline-block; margin-top: 10px; color: #8b5cf6; text-decoration: none; }
    </style>
</head>
<body>
    <header><h1>⏰ Gestión de Turnos Laborales</h1></header>

    <div class="container">
        <div class="section">
            <h2>Crear Turno</h2>
            <form method="POST" action="{{ route('configuracion.turnos.crear') }}">
                @csrf
                
                <div class="form-group">
                    <label>Sucursal</label>
                    <select name="sucursal_id" required>
                        <option value="">Seleccionar sucursal...</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Nombre del Turno</label>
                    <input type="text" name="nombre" placeholder="Ej: Mañana, Tarde, Noche" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Hora Entrada (HH:MM)</label>
                        <input type="time" name="hora_entrada" required>
                    </div>
                    <div class="form-group">
                        <label>Hora Salida (HH:MM)</label>
                        <input type="time" name="hora_salida" required>
                    </div>
                    <div class="form-group">
                        <label>Tolerancia (minutos)</label>
                        <input type="number" name="tolerancia_min" value="15" min="0">
                    </div>
                </div>
                <button type="submit" class="btn">Crear Turno</button>
            </form>
        </div>

        <div class="section" style="margin-top: 20px;">
            <h2>Turnos Registrados</h2>
            @if($turnos->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Tolerancia</th>
                            <th>Sucursal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($turnos as $turno)
                            <tr>
                                <td><strong>{{ $turno->nombre }}</strong></td>
                                <td>{{ $turno->hora_entrada }}</td>
                                <td>{{ $turno->hora_salida }}</td>
                                <td>{{ $turno->tolerancia_min }} min</td>
                                <td>{{ $turno->sucursal->nombre ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('configuracion.turnos.editar', $turno->id) }}" class="btn btn-edit">Editar</a>
                                    <button class="btn btn-delete" onclick="deleteTurno({{ $turno->id }})">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: #999;">Sin turnos registrados</p>
            @endif
        </div>

        <a href="{{ route('configuracion.index') }}" class="link-back">← Volver</a>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        if (!token) window.location.href = '/login';

        function deleteTurno(id) {
            if (!confirm('¿Eliminar turno?')) return;
            window.location.href = `{{ url('configuracion/turnos') }}/${id}/eliminar`;
        }
    </script>
</body>
</html>
