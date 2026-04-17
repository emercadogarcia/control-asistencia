<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📋</text></svg>">
    <title>Configuración - Control de Asistencia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; }
        header { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); color: white; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .section { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        h2 { color: #8b5cf6; margin-bottom: 20px; }
        .config-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .config-card { background: #f9fafb; padding: 20px; border-radius: 8px; border-left: 4px solid #8b5cf6; }
        .config-card h3 { color: #333; margin-bottom: 10px; }
        .config-card p { color: #666; font-size: 13px; margin-bottom: 15px; }
        .btn { background: #0284c7; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-danger { background: #dc2626; }
        .link-back { display: inline-block; margin-top: 10px; color: #8b5cf6; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <h1>⚙️ Configuración del Sistema</h1>
    </header>

    <div class="container">
        <div class="section">
            <h2>Módulos de Configuración</h2>
            <div class="config-grid">
                <div class="config-card">
                    <h3>🏢 Sucursales</h3>
                    <p>Gestiona las sucursales o locales de la empresa</p>
                    <a href="{{ route('configuracion.sucursales') }}" class="btn">Administrar</a>
                </div>
                <div class="config-card">
                    <h3>⏰ Turnos Laborales</h3>
                    <p>Define los turnos de trabajo y sus horarios</p>
                    <a href="{{ route('configuracion.turnos') }}" class="btn">Administrar</a>
                </div>
                <div class="config-card">
                    <h3>📅 Calendario Laboral</h3>
                    <p>Configura días feriados y eventos</p>
                    <a href="{{ route('configuracion.calendario') }}" class="btn">Administrar</a>
                </div>
                <div class="config-card">
                    <h3>🔄 Base de Datos</h3>
                    <p>Operaciones de mantenimiento de la BD</p>
                    <button class="btn btn-danger" onclick="resetDB()">Reiniciar BD</button>
                </div>
            </div>
        </div>

        <a href="{{ route('dashboard') }}" class="link-back">← Volver al Dashboard</a>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        if (!token) window.location.href = '/login';

        function resetDB() {
            if (!confirm('⚠️ ADVERTENCIA: Esto eliminará TODOS los datos. ¿Continuar?')) return;
            
            const password = prompt('Ingresa tu contraseña para confirmar:');
            if (!password) return;

            fetch('{{ route('configuracion.reset') }}', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ password })
            }).then(r => r.json()).then(data => {
                alert(data.message || 'Base de datos reiniciada');
                location.reload();
            }).catch(e => alert('Error: ' + e.message));
        }
    </script>
</body>
</html>
</body>
</html>
