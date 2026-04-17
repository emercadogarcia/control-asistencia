<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📋</text></svg>">
    <title>Marcación Rápida - Control de Asistencia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; }
        header { background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%); color: white; padding: 20px; }
        header h1 { display: flex; align-items: center; gap: 10px; }
        .header-right { float: right; }
        .header-right button { color: white; padding: 8px 15px; border-radius: 4px; background: rgba(255, 255, 255, 0.2); border: none; cursor: pointer; }
        .container { max-width: 600px; margin: 40px auto; padding: 20px; }
        .panel {
            background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        .panel h2 { color: #0284c7; margin-bottom: 30px; }
        .search-group {
            margin-bottom: 30px;
        }
        input[type="text"] {
            width: 100%; padding: 15px; font-size: 16px; border: 2px solid #e5e7eb;
            border-radius: 6px; text-align: center;
        }
        input[type="text"]:focus {
            outline: none; border-color: #0284c7; box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
        }
        .info-card {
            background: #f3f4f6; padding: 20px; border-radius: 6px; margin-bottom: 20px;
            display: none;
        }
        .info-card.show { display: block; }
        .info-card h3 { color: #0284c7; margin-bottom: 10px; }
        .info-row {
            display: flex; justify-content: space-between; padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; }
        .info-value { color: #666; }
        .buttons {
            display: grid; grid-template-columns: 1fr 1fr; gap: 15px;
            margin-top: 30px;
        }
        button {
            padding: 15px; font-size: 18px; font-weight: bold;
            border: none; border-radius: 6px; cursor: pointer;
            transition: all 0.3s;
        }
        .btn-entrada {
            background: #16a34a; color: white;
        }
        .btn-entrada:hover { background: #15803d; }
        .btn-entrada:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-salida {
            background: #ea580c; color: white;
        }
        .btn-salida:hover { background: #c2410c; }
        .btn-salida:disabled { opacity: 0.5; cursor: not-allowed; }
        .error-message {
            background: #fee2e2; color: #991b1b; padding: 15px;
            border-radius: 6px; margin-bottom: 20px; display: none;
        }
        .error-message.show { display: block; }
        .success-message {
            background: #dcfce7; color: #166534; padding: 15px;
            border-radius: 6px; margin-bottom: 20px; display: none;
        }
        .success-message.show { display: block; }
        .badge {
            display: inline-block; padding: 6px 15px; border-radius: 20px;
            font-size: 13px; font-weight: 600;
        }
        .badge-presente { background: #dcfce7; color: #166534; }
        .badge-tardanza { background: #fed7aa; color: #92400e; }
        .link-back {
            display: inline-block; margin-top: 20px;
            color: #0284c7; text-decoration: none;
        }
        .link-back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <header>
        <h1>✍️ Marcación Rápida</h1>
        <div class="header-right">
            <button onclick="logout()">Cerrar Sesión</button>
        </div>
    </header>

    <div class="container">
        <div class="panel">
            <h2>Ingresa tu CI para marcar</h2>

            <div class="error-message" id="errorMsg"></div>
            <div class="success-message" id="successMsg"></div>

            <div class="search-group">
                <input type="text" id="ciInput" placeholder="Ej: 1234567890" autofocus>
            </div>

            <div class="info-card" id="infoCard">
                <h3 id="personalNombre"></h3>
                <div class="info-row">
                    <span class="info-label">CI:</span>
                    <span class="info-value" id="personalCI"></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Sucursal:</span>
                    <span class="info-value" id="personalSucursal"></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Turno:</span>
                    <span class="info-value" id="personalTurno"></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Horario:</span>
                    <span class="info-value" id="personalHorario"></span>
                </div>
            </div>

            <div class="buttons">
                <button class="btn-entrada" id="btnEntrada" onclick="marcar('entrada')" disabled>
                    ➡️ ENTRADA
                </button>
                <button class="btn-salida" id="btnSalida" onclick="marcar('salida')" disabled>
                    ⬅️ SALIDA
                </button>
            </div>

            <a href="<?php echo e(route('dashboard')); ?>" class="link-back">← Volver al Dashboard</a>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        let personalData = null;

        if (!token) {
            window.location.href = '/login';
        }

        document.getElementById('ciInput').addEventListener('keypress', async (e) => {
            if (e.key === 'Enter') {
                const ci = e.target.value.trim();
                if (!ci) return;

                clearMessages();
                await buscarPersonal(ci);
            }
        });

        async function buscarPersonal(ci) {
            try {
                const response = await fetch(`<?php echo e(route('asistencia.buscar')); ?>?ci=${ci}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                const data = await response.json();

                if (!response.ok) {
                    mostrarError(data.error || 'Personal no encontrado');
                    return;
                }

                personalData = data;
                mostrarInfo(data);
                habilitarBotones(data.personal.id);
            } catch (error) {
                mostrarError('Error al buscar personal');
            }
        }

        function mostrarInfo(data) {
            document.getElementById('personalNombre').textContent = data.personal.nombre + ' ' + data.personal.apellido;
            document.getElementById('personalCI').textContent = data.personal.ci;
            document.getElementById('personalSucursal').textContent = data.sucursal.nombre;
            document.getElementById('personalTurno').textContent = data.turno.nombre;
            document.getElementById('personalHorario').textContent = 
                data.turno.hora_entrada + ' - ' + data.turno.hora_salida;

            document.getElementById('infoCard').classList.add('show');
        }

        function habilitarBotones(personalId) {
            document.getElementById('btnEntrada').disabled = false;
            document.getElementById('btnSalida').disabled = false;
            document.getElementById('btnEntrada').dataset.personalId = personalId;
            document.getElementById('btnSalida').dataset.personalId = personalId;
        }

        async function marcar(tipo) {
            if (!personalData) return;

            const btn = document.getElementById(tipo === 'entrada' ? 'btnEntrada' : 'btnSalida');
            btn.disabled = true;

            try {
                const response = await fetch('<?php echo e(route('asistencia.marcar')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        personal_id: personalData.personal.id,
                        tipo: tipo
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    mostrarError(data.message);
                } else {
                    const estado = data.estado || data.message;
                    mostrarExito(`${tipo.toUpperCase()} registrada - ${estado}`);
                    
                    if (tipo === 'entrada') {
                        document.getElementById('btnEntrada').disabled = true;
                    }

                    setTimeout(() => {
                        document.getElementById('ciInput').value = '';
                        document.getElementById('infoCard').classList.remove('show');
                        document.getElementById('btnEntrada').disabled = false;
                        document.getElementById('btnSalida').disabled = false;
                        document.getElementById('ciInput').focus();
                    }, 2000);
                }
            } catch (error) {
                mostrarError('Error al registrar marcación');
            } finally {
                btn.disabled = false;
            }
        }

        function mostrarError(msg) {
            const el = document.getElementById('errorMsg');
            el.textContent = msg;
            el.classList.add('show');
        }

        function mostrarExito(msg) {
            const el = document.getElementById('successMsg');
            el.textContent = '✓ ' + msg;
            el.classList.add('show');
        }

        function clearMessages() {
            document.getElementById('errorMsg').classList.remove('show');
            document.getElementById('successMsg').classList.remove('show');
        }

        function logout() {
            fetch('<?php echo e(route('asistencia.index')); ?>', {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}` }
            }).then(() => {
                localStorage.removeItem('auth_token');
                window.location.href = '/login';
            });
        }
    </script>
</body>
</html>
<?php /**PATH /workspace/resources/views/asistencia/crear.blade.php ENDPATH**/ ?>