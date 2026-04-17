<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asistencia Hoy</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; }
        header { background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%); color: white; padding: 20px; }
        header h1 { display: flex; align-items: center; gap: 10px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .section { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        h2 { color: #0284c7; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f3f4f6; padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb; }
        td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
        tr:hover { background: #f9fafb; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .badge-presente { background: #dcfce7; color: #166534; }
        .badge-tardanza { background: #fed7aa; color: #92400e; }
        .link-back { display: inline-block; margin-top: 10px; color: #0284c7; text-decoration: none; }
        .btn { background: #0284c7; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-secondary { background: #6b7280; }
    </style>
</head>
<body>
    <header>
        <h1>📊 Asistencia de Hoy</h1>
    </header>

    <div class="container">
        <div class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin-bottom: 0;">Registro de Asistencia</h2>
                <a href="{{ route('asistencia.crear') }}" class="btn">+ Marcar Asistencia</a>
            </div>

            @if($asistencias->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Personal</th>
                            <th>CI</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asistencias as $asistencia)
                            <tr>
                                <td><strong>{{ $asistencia->personal->nombre }} {{ $asistencia->personal->apellido }}</strong></td>
                                <td>{{ $asistencia->personal->ci }}</td>
                                <td>{{ $asistencia->hora_entrada->format('H:i') }}</td>
                                <td>{{ $asistencia->hora_salida ? $asistencia->hora_salida->format('H:i') : '—' }}</td>
                                <td>
                                    @if($asistencia->estado === 'presente')
                                        <span class="badge badge-presente">Presente</span>
                                    @elseif($asistencia->estado === 'tardanza')
                                        <span class="badge badge-tardanza">Tardanza</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #999; padding: 40px;">Sin registros hoy</p>
            @endif
        </div>

        <a href="{{ route('dashboard') }}" class="link-back">← Volver al Dashboard</a>
    </div>

    <script>
        if (!localStorage.getItem('auth_token')) window.location.href = '/login';
    </script>
</body>
</html>
