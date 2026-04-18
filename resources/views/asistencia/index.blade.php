<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Marcar Asistencia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #0f172a; }
        header { background: #ffffff; border-bottom: 1px solid #e2e8f0; }
        .container { max-width: 1180px; margin: 0 auto; padding: 0 20px; }
        .header-inner { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 22px 0; }
        .header-inner h1 { font-size: 30px; font-weight: 700; }
        .header-inner p { color: #64748b; margin-top: 4px; }
        .page { padding: 28px 0 40px; }
        .layout { display: grid; grid-template-columns: 380px minmax(0, 1fr); gap: 22px; align-items: start; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 22px; box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06); }
        .card h2 { font-size: 20px; margin-bottom: 8px; }
        .card p { color: #64748b; }
        .clock-card { text-align: center; background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); }
        .clock-value { font-size: 36px; font-weight: 800; letter-spacing: 0.04em; margin-top: 18px; }
        .clock-date { color: #475569; font-weight: 600; margin-top: 10px; }
        .clock-zone { color: #64748b; font-size: 13px; margin-top: 10px; }
        .field { margin-top: 16px; }
        .field label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155; }
        .field input { width: 100%; padding: 14px 16px; border: 1px solid #cbd5e1; border-radius: 14px; background: #fff; font-size: 16px; }
        .field input:focus { outline: none; border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12); }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; border-radius: 14px; border: 1px solid transparent; padding: 12px 16px; font-weight: 600; text-decoration: none; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 10px 18px rgba(15, 23, 42, 0.1); }
        .btn-primary { width: 100%; background: #0f172a; color: white; margin-top: 16px; }
        .btn-secondary { background: #e2e8f0; color: #0f172a; }
        .alert { border-radius: 14px; padding: 14px 16px; margin-top: 16px; display: none; }
        .alert.show { display: block; }
        .alert.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert.error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .result-card { margin-top: 18px; border-radius: 16px; border: 1px solid #e2e8f0; background: #f8fafc; padding: 16px; display: none; }
        .result-card.show { display: block; }
        .result-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-top: 12px; }
        .result-item { background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px; }
        .result-item span { display: block; color: #64748b; font-size: 12px; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.08em; }
        .result-item strong { font-size: 15px; }
        .metrics { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
        .metric { background: white; border: 1px solid #e2e8f0; border-radius: 18px; padding: 18px; }
        .metric span { display: block; color: #64748b; font-size: 13px; margin-bottom: 8px; }
        .metric strong { font-size: 28px; }
        .table-wrap { overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 18px; }
        table { width: 100%; border-collapse: collapse; min-width: 760px; background: white; }
        th, td { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; text-align: left; vertical-align: top; }
        th { color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; background: #f8fafc; }
        tbody tr:hover { background: #f8fafc; }
        .badge { display: inline-flex; align-items: center; padding: 5px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .badge-presente { background: #dcfce7; color: #166534; }
        .badge-tardanza { background: #ffedd5; color: #c2410c; }
        .badge-pendiente { background: #e0f2fe; color: #075985; }
        .empty-state { text-align: center; padding: 28px; color: #64748b; }
        .back-link { display: inline-flex; align-items: center; margin-top: 18px; color: #475569; text-decoration: none; }
        @media (max-width: 980px) {
            .layout { grid-template-columns: 1fr; }
            .metrics { grid-template-columns: 1fr 1fr; }
            .result-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .metrics { grid-template-columns: 1fr; }
            .clock-value { font-size: 28px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-inner">
            <div>
                <h1>Marcar asistencia</h1>
                <p>Registro rapido por CI con reloj digital y marcacion automatica de entrada o salida.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
        </div>
    </header>

    <main class="container page">
        <div class="layout">
            <section>
                <div class="card clock-card">
                    <h2>Hora actual</h2>
                    <p>La marcacion usa la region detectada en este dispositivo.</p>
                    <div class="clock-value" id="clockTime">--:--:--</div>
                    <div class="clock-date" id="clockDate">---</div>
                    <div class="clock-zone" id="clockZone">Zona horaria: --</div>
                </div>

                <div class="card" style="margin-top: 18px;">
                    <h2>Registrar por CI</h2>
                    <p>Ingresa solo el CI. El sistema decidira si corresponde entrada o salida.</p>

                    <div class="alert success" id="successBox"></div>
                    <div class="alert error" id="errorBox"></div>

                    <form id="attendanceForm">
                        <div class="field">
                            <label for="ci">CI</label>
                            <input id="ci" name="ci" type="text" placeholder="Ej. 12345678" autocomplete="off" required>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitBtn">Registrar asistencia</button>
                    </form>

                    <div class="result-card" id="resultCard">
                        <h2 style="font-size: 18px; margin-bottom: 0;">Ultimo registro</h2>
                        <div class="result-grid">
                            <div class="result-item">
                                <span>Personal</span>
                                <strong id="resultName">-</strong>
                            </div>
                            <div class="result-item">
                                <span>CI</span>
                                <strong id="resultCi">-</strong>
                            </div>
                            <div class="result-item">
                                <span>Tipo</span>
                                <strong id="resultTipo">-</strong>
                            </div>
                            <div class="result-item">
                                <span>Estado</span>
                                <strong id="resultEstado">-</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="metrics">
                    <div class="metric">
                        <span>Marcaciones hoy</span>
                        <strong>{{ $totalHoy }}</strong>
                    </div>
                    <div class="metric">
                        <span>Presentes</span>
                        <strong>{{ $presentesHoy }}</strong>
                    </div>
                    <div class="metric">
                        <span>Tardanzas</span>
                        <strong>{{ $tardanzasHoy }}</strong>
                    </div>
                    <div class="metric">
                        <span>Sin salida</span>
                        <strong>{{ $pendientesSalida }}</strong>
                    </div>
                </div>

                <div class="card">
                    <h2>Asistencia de hoy</h2>
                    <p>Vista clara de las marcaciones del dia con estado actual de cada registro.</p>

                    <div class="table-wrap" style="margin-top: 18px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Personal</th>
                                    <th>CI</th>
                                    <th>Sucursal</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asistencias as $asistencia)
                                    <tr>
                                        <td><strong>{{ $asistencia->personal->nombre_completo ?? trim(($asistencia->personal->nombre ?? '') . ' ' . ($asistencia->personal->apellido ?? '')) }}</strong></td>
                                        <td>{{ $asistencia->personal->ci ?? 'Sin CI' }}</td>
                                        <td>{{ $asistencia->personal->sucursal->nombre ?? 'Sin sucursal' }}</td>
                                        <td>{{ $asistencia->hora_entrada ? $asistencia->hora_entrada->format('H:i:s') : 'Sin registro' }}</td>
                                        <td>{{ $asistencia->hora_salida ? $asistencia->hora_salida->format('H:i:s') : 'Pendiente' }}</td>
                                        <td>
                                            @if($asistencia->hora_salida)
                                                <span class="badge {{ $asistencia->estado === 'tardanza' ? 'badge-tardanza' : 'badge-presente' }}">
                                                    {{ $asistencia->estado === 'tardanza' ? 'Tardanza' : 'Completo' }}
                                                </span>
                                            @else
                                                <span class="badge badge-pendiente">
                                                    {{ $asistencia->estado === 'tardanza' ? 'Tardanza pendiente salida' : 'Pendiente salida' }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="empty-state">No hay marcaciones registradas hoy.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 16px;">
                        {{ $asistencias->appends(request()->query())->links() }}
                    </div>
                </div>
            </section>
        </div>

        <a href="{{ route('dashboard') }}" class="back-link">← Volver al dashboard</a>
    </main>

    <script>
        if (!localStorage.getItem('auth_token')) window.location.href = '/login';

        const form = document.getElementById('attendanceForm');
        const input = document.getElementById('ci');
        const submitBtn = document.getElementById('submitBtn');
        const successBox = document.getElementById('successBox');
        const errorBox = document.getElementById('errorBox');
        const resultCard = document.getElementById('resultCard');

        function pad(value) {
            return String(value).padStart(2, '0');
        }

        function renderClock() {
            const now = new Date();
            const zone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
            const weekdays = ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB'];
            const day = weekdays[now.getDay()];
            const date = `${day}, ${pad(now.getDate())}-${pad(now.getMonth() + 1)}-${now.getFullYear()}, ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

            document.getElementById('clockTime').textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            document.getElementById('clockDate').textContent = date;
            document.getElementById('clockZone').textContent = `Zona horaria: ${zone}`;
        }

        function showMessage(element, message) {
            element.textContent = message;
            element.classList.add('show');
        }

        function clearMessages() {
            successBox.classList.remove('show');
            errorBox.classList.remove('show');
            successBox.textContent = '';
            errorBox.textContent = '';
        }

        function updateResult(data) {
            document.getElementById('resultName').textContent = `${data.personal.nombre} ${data.personal.apellido}`.trim();
            document.getElementById('resultCi').textContent = data.personal.ci;
            document.getElementById('resultTipo').textContent = data.tipo === 'entrada' ? 'Entrada' : 'Salida';
            document.getElementById('resultEstado').textContent = data.estado === 'tardanza' ? 'Tardanza' : 'Presente';
            resultCard.classList.add('show');
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            clearMessages();

            const ci = input.value.trim();
            if (!ci) return;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Registrando...';

            try {
                const response = await fetch('{{ route('asistencia.marcar') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        ci,
                        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC',
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    showMessage(errorBox, data.error || 'No se pudo registrar la asistencia');
                    return;
                }

                updateResult(data);
                showMessage(successBox, `${data.message}. ${data.tipo === 'entrada' ? 'Se marco entrada' : 'Se marco salida'} para ${data.personal.nombre} ${data.personal.apellido}.`);
                input.value = '';

                setTimeout(() => window.location.reload(), 1200);
            } catch (error) {
                showMessage(errorBox, 'Ocurrio un error al registrar la asistencia');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Registrar asistencia';
                input.focus();
            }
        });

        renderClock();
        setInterval(renderClock, 1000);
        input.focus();
    </script>
</body>
</html>
