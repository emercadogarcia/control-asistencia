<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calendario Laboral</title>
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
        .badge-feriado { background: #fed7aa; padding: 2px 8px; border-radius: 4px; font-size: 12px; }
        .btn-delete { background: #dc2626; padding: 6px 12px; }
        .link-back { display: inline-block; margin-top: 10px; color: #8b5cf6; text-decoration: none; }
    </style>
</head>
<body>
    <header><h1>📅 Calendario Laboral</h1></header>

    <div class="container">
        <div class="section">
            <h2>Agregar Evento/Feriado</h2>
            <form method="POST" action="{{ route('configuracion.calendario.crear') }}">
                @csrf
                <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" name="fecha" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" placeholder="Ej: Feriado - Día de la Independencia"></textarea>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="es_feriado"> Es Feriado
                    </label>
                </div>
                <button type="submit" class="btn">Agregar Evento</button>
            </form>
        </div>

        <div class="section" style="margin-top: 20px;">
            <h2>Eventos Registrados</h2>
            @if($eventos->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventos as $evento)
                            <tr>
                                <td><strong>{{ $evento->fecha }}</strong></td>
                                <td>{{ $evento->descripcion ?? '—' }}</td>
                                <td>
                                    @if($evento->es_feriado)
                                        <span class="badge-feriado">Feriado</span>
                                    @else
                                        <span>Evento</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-delete" onclick="deleteEvento({{ $evento->id }})">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: #999;">Sin eventos registrados</p>
            @endif
        </div>

        <a href="{{ route('configuracion.index') }}" class="link-back">← Volver</a>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        if (!token) window.location.href = '/login';

        function deleteEvento(id) {
            if (!confirm('¿Eliminar evento?')) return;
            window.location.href = `{{ url('configuracion/calendario') }}/${id}/eliminar`;
        }
    </script>
</body>
</html>
