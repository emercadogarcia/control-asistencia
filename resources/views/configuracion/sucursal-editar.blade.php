<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Sucursal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; }
        header { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); color: white; padding: 20px; }
        .container { max-width: 600px; margin: 40px auto; padding: 20px; }
        .form-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); }
        h2 { color: #8b5cf6; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; color: #333; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 14px; }
        input:focus, textarea:focus { outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
        .btn-submit { background: #16a34a; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .link-back { display: inline-block; margin-top: 10px; color: #8b5cf6; text-decoration: none; }
    </style>
</head>
<body>
    <header>
        <h1>✏️ Editar Sucursal</h1>
    </header>

    <div class="container">
        <div class="form-card">
            <h2>Actualizar Sucursal</h2>
            
            <form method="POST" action="{{ route('configuracion.sucursales.actualizar', $sucursal->id) }}">
                @csrf

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="{{ $sucursal->nombre }}" required>
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <textarea name="direccion">{{ $sucursal->direccion }}</textarea>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="{{ $sucursal->telefono }}">
                </div>

                <button type="submit" class="btn-submit">Actualizar Sucursal</button>
            </form>

            <a href="{{ route('configuracion.sucursales') }}" class="link-back">← Volver</a>
        </div>
    </div>

    <script>
        if (!localStorage.getItem('auth_token')) window.location.href = '/login';
    </script>
</body>
</html>
