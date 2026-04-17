<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📋</text></svg>">
    <title>Control de Asistencia</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header { background: #0284c7; color: white; padding: 20px; }
        nav { background: white; border-bottom: 1px solid #e5e7eb; padding: 15px 0; }
        nav a { margin: 0 15px; text-decoration: none; color: #333; }
        nav a:hover { color: #0284c7; font-weight: bold; }
    </style>
</head>
<body>
    <header>
        <h1>📋 Control de Asistencia Laboral</h1>
    </header>
    <nav class="container">
        <a href="/dashboard">Dashboard</a>
        <a href="/asistencia">Asistencia</a>
        <a href="/personal">Personal</a>
        <a href="/configuracion">Configuración</a>
    </nav>
    <div class="container">
        <h2>¡Bienvenido!</h2>
        <p>El sistema está configurado correctamente.</p>
        <h3>Próximos pasos:</h3>
        <ol>
            <li>Ejecuta: <code>php artisan migrate</code></li>
            <li>Ejecuta: <code>npm run dev</code></li>
            <li>Abre: http://localhost:8000\</li\>
        </ol>
    </div>
</body>
</html>
