<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Turno</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; }
        header { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); color: white; padding: 20px; }
        .container { max-width: 600px; margin: 40px auto; padding: 20px; }
        .form-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        h2 { color: #8b5cf6; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; color: #333; }
        input, select { width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 14px; }
        input:focus, select:focus { outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
        .btn-submit { background: #16a34a; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .link-back { display: inline-block; margin-top: 10px; color: #8b5cf6; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <h1>✏️ Editar Turno</h1>
    </header>

    <div class="container">
        <div class="form-card">
            <h2>Actualizar Turno</h2>
            
            <form method="POST" action="{{ route('configuracion.turnos.actualizar', $turno->id) }}">
                @csrf

                <div class="form-group">
                    <label>Sucursal</label>
                    <select name="sucursal_id" required>
                        <option value="">Seleccionar sucursal...</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}" {{ $turno->sucursal_id === $sucursal->id ? 'selected' : '' }}>
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Nombre del Turno</label>
                    <input type="text" name="nombre" value="{{ $turno->nombre }}" required>
                </div>

                <div class="form-group">
                    <label>Hora Entrada (HH:MM)</label>
                    <input type="time" name="hora_entrada" value="{{ $turno->hora_entrada }}" required>
                </div>

                <div class="form-group">
                    <label>Hora Salida (HH:MM)</label>
                    <input type="time" name="hora_salida" value="{{ $turno->hora_salida }}" required>
                </div>

                <div class="form-group">
                    <label>Tolerancia (minutos)</label>
                    <input type="number" name="tolerancia_min" value="{{ $turno->tolerancia_min }}" min="0">
                </div>

                <button type="submit" class="btn-submit">Actualizar Turno</button>
            </form>

            <a href="{{ route('configuracion.turnos') }}" class="link-back">← Volver</a>
        </div>
    </div>

    <script>
        if (!localStorage.getItem('auth_token')) window.location.href = '/login';
    </script>
</body>
</html>
