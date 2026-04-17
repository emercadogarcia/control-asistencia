<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Configuración - Control de Asistencia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #0f172a; }
        header { background: #ffffff; border-bottom: 1px solid #e2e8f0; }
        .container { max-width: 1180px; margin: 0 auto; padding: 0 20px; }
        .hero { padding: 28px 0 20px; }
        .hero h1 { font-size: 34px; margin-bottom: 8px; }
        .hero p { color: #64748b; max-width: 720px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 18px; margin-top: 20px; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 22px; padding: 22px; box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06); }
        .card span { display: inline-flex; width: 44px; height: 44px; align-items: center; justify-content: center; border-radius: 14px; background: #f8fafc; margin-bottom: 14px; }
        .card h2 { font-size: 20px; margin-bottom: 8px; }
        .card p { color: #64748b; margin-bottom: 18px; min-height: 42px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 14px; border-radius: 14px; text-decoration: none; font-weight: 600; border: 1px solid transparent; cursor: pointer; }
        .btn-primary { background: #0f172a; color: white; }
        .btn-danger { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .back-link { display: inline-flex; margin: 22px 0 36px; color: #475569; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <div class="container hero">
            <h1>Configuracion</h1>
            <p>Centraliza la administracion de sucursales, turnos laborales y calendario con una interfaz mas limpia y enfocada en operacion.</p>
        </div>
    </header>

    <main class="container">
        <div class="grid">
            <section class="card">
                <span>🏢</span>
                <h2>Sucursales</h2>
                <p>Gestiona los puntos de operacion y su informacion principal.</p>
                <a href="{{ route('configuracion.sucursales') }}" class="btn btn-primary">Administrar</a>
            </section>

            <section class="card">
                <span>⏰</span>
                <h2>Turnos laborales</h2>
                <p>Configura horarios, tolerancia y dias de trabajo por sucursal.</p>
                <a href="{{ route('configuracion.turnos') }}" class="btn btn-primary">Administrar</a>
            </section>

            <section class="card">
                <span>📅</span>
                <h2>Calendario laboral</h2>
                <p>Administra feriados y eventos operativos de forma centralizada.</p>
                <a href="{{ route('configuracion.calendario') }}" class="btn btn-primary">Administrar</a>
            </section>

            <section class="card">
                <span>🧹</span>
                <h2>Base de datos</h2>
                <p>Ejecuta tareas de mantenimiento con confirmacion explicita.</p>
                <button class="btn btn-danger" onclick="resetDB()">Reiniciar BD</button>
            </section>
        </div>

        <a href="{{ route('dashboard') }}" class="back-link">← Volver al Dashboard</a>
    </main>

    <script>
        const token = localStorage.getItem('auth_token');
        if (!token) window.location.href = '/login';

        function resetDB() {
            if (!confirm('ADVERTENCIA: Esto eliminara todos los datos. Deseas continuar?')) return;

            const password = prompt('Ingresa tu contrasena para confirmar:');
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
