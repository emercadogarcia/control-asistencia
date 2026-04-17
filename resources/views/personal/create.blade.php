<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📋</text></svg>">
    <title>Agregar Personal - Control de Asistencia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; }
        header { background: #0284c7; color: white; padding: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        header h1 { display: flex; align-items: center; gap: 10px; }
        .header-right { float: right; }
        .header-right button { color: white; padding: 8px 15px; border-radius: 4px; background: rgba(255, 255, 255, 0.2); border: none; cursor: pointer; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .card { background: white; border-radius: 10px; padding: 30px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        .card h2 { color: #0284c7; margin-bottom: 10px; }
        nav { margin-top: 20px; }
        nav a { display: inline-block; margin-right: 10px; padding: 10px 15px; background: #0284c7; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <header>
        <h1>👥 Agregar Personal</h1>
        <div class="header-right">
            <button onclick="logout()">Cerrar Sesión</button>
        </div>
    </header>
    <div class="container">
        <div class="card">
            <h2>Registrar Nuevo Personal</h2>
            <p>Formulario para agregar un nuevo miembro del personal.</p>
            <nav>
                <a href="/personal">← Volver a Personal</a>
            </nav>
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
        if (!localStorage.getItem('auth_token')) window.location.href = '/login';
    </script>
</body>
</html>
