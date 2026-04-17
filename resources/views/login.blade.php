<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📋</text></svg>">
    <title>Login - Control de Asistencia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            font-size: 28px;
            color: #0284c7;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .tab-button {
            padding: 10px 20px;
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            font-weight: 500;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.3s;
        }
        .tab-button.active {
            color: #0284c7;
            border-bottom-color: #0284c7;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
        }
        .error-message {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }
        .error-message.show {
            display: block;
        }
        .success-message {
            color: #16a34a;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }
        .success-message.show {
            display: block;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #0284c7;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        button:hover {
            background: #0369a1;
        }
        button:active {
            transform: scale(0.98);
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 13px;
        }
        .demo-credentials {
            background: #f3f4f6;
            border-left: 4px solid #0284c7;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 13px;
            color: #333;
        }
        .demo-credentials strong {
            display: block;
            margin-bottom: 8px;
        }
        .demo-credentials code {
            background: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .loading {
            display: none;
        }
        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>📋 Control de Asistencia</h1>
            <p>Inicia sesión o regístrate para continuar</p>
        </div>

        <div class="tabs">
            <button class="tab-button active" onclick="switchTab('login')">Iniciar Sesión</button>
            <button class="tab-button" onclick="switchTab('register')">Registrarse</button>
        </div>

        <!-- Login Tab -->
        <div id="login" class="tab-content active">
            <form id="loginForm">
                @csrf
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required placeholder="usuario@ejemplo.com">
                    <div class="error-message" id="emailError"></div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
                    <div class="error-message" id="passwordError"></div>
                </div>

                <button type="submit" id="loginBtn">Iniciar Sesión</button>
            </form>

            <div id="generalError" class="error-message" style="text-align: center; margin-top: 15px;"></div>

            <div class="demo-credentials">
                <strong>🧪 Credenciales de Demostración:</strong>
                <div>Email: <code>admin@asistencia.local</code></div>
                <div>Contraseña: <code>password</code></div>
            </div>
        </div>

        <!-- Register Tab -->
        <div id="register" class="tab-content">
            <form id="registerForm">
                @csrf
                <div class="form-group">
                    <label for="register-name">Nombre Completo</label>
                    <input type="text" id="register-name" name="name" required placeholder="Tu nombre completo">
                    <div class="error-message" id="nameError"></div>
                </div>

                <div class="form-group">
                    <label for="register-email">Correo Electrónico</label>
                    <input type="email" id="register-email" name="email" required placeholder="usuario@ejemplo.com">
                    <div class="error-message" id="registerEmailError"></div>
                </div>

                <div class="form-group">
                    <label for="register-password">Contraseña</label>
                    <input type="password" id="register-password" name="password" required placeholder="Mínimo 6 caracteres">
                    <div class="error-message" id="registerPasswordError"></div>
                </div>

                <button type="submit" id="registerBtn">Registrarse</button>
            </form>

            <div id="registerError" class="error-message" style="text-align: center; margin-top: 15px;"></div>
            <div id="registerSuccess" class="success-message" style="text-align: center; margin-top: 15px;"></div>
        </div>

        <div class="login-footer">
            <p>¿Necesitas ayuda? Contacta al administrador del sistema.</p>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Hide all tabs
            document.getElementById('login').classList.remove('active');
            document.getElementById('register').classList.remove('active');
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));

            // Show selected tab
            document.getElementById(tab).classList.add('active');
            event.target.classList.add('active');
        }

        // Login form handler
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const loginBtn = document.getElementById('loginBtn');

            // Clear previous errors
            document.getElementById('emailError').classList.remove('show');
            document.getElementById('passwordError').classList.remove('show');
            document.getElementById('generalError').classList.remove('show');

            loginBtn.disabled = true;
            loginBtn.textContent = 'Iniciando sesión...';

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken,
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (!response.ok) {
                    if (data.errors) {
                        if (data.errors.email) {
                            document.getElementById('emailError').textContent = data.errors.email[0];
                            document.getElementById('emailError').classList.add('show');
                        }
                        if (data.errors.password) {
                            document.getElementById('passwordError').textContent = data.errors.password[0];
                            document.getElementById('passwordError').classList.add('show');
                        }
                    } else if (data.message) {
                        document.getElementById('generalError').textContent = data.message;
                        document.getElementById('generalError').classList.add('show');
                    }
                } else if (data.token) {
                    localStorage.setItem('auth_token', data.token);
                    window.location.href = '/dashboard';
                }
            } catch (error) {
                document.getElementById('generalError').textContent = 'Error de conexión. Por favor, intenta de nuevo.';
                document.getElementById('generalError').classList.add('show');
                console.error('Error:', error);
            } finally {
                loginBtn.disabled = false;
                loginBtn.textContent = 'Iniciar Sesión';
            }
        });

        // Register form handler
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const name = document.getElementById('register-name').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const registerBtn = document.getElementById('registerBtn');

            // Clear previous messages
            document.getElementById('nameError').classList.remove('show');
            document.getElementById('registerEmailError').classList.remove('show');
            document.getElementById('registerPasswordError').classList.remove('show');
            document.getElementById('registerError').classList.remove('show');
            document.getElementById('registerSuccess').classList.remove('show');

            registerBtn.disabled = true;
            registerBtn.textContent = 'Registrando...';

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken,
                    },
                    body: JSON.stringify({ name, email, password })
                });

                const data = await response.json();

                if (!response.ok) {
                    if (data.errors) {
                        if (data.errors.name) {
                            document.getElementById('nameError').textContent = data.errors.name[0];
                            document.getElementById('nameError').classList.add('show');
                        }
                        if (data.errors.email) {
                            document.getElementById('registerEmailError').textContent = data.errors.email[0];
                            document.getElementById('registerEmailError').classList.add('show');
                        }
                        if (data.errors.password) {
                            document.getElementById('registerPasswordError').textContent = data.errors.password[0];
                            document.getElementById('registerPasswordError').classList.add('show');
                        }
                    } else if (data.message) {
                        document.getElementById('registerError').textContent = data.message;
                        document.getElementById('registerError').classList.add('show');
                    }
                } else {
                    document.getElementById('registerSuccess').textContent = '✓ Registro exitoso. Ahora puedes iniciar sesión.';
                    document.getElementById('registerSuccess').classList.add('show');
                    document.getElementById('registerForm').reset();
                    
                    // Switch to login tab after 2 seconds
                    setTimeout(() => {
                        switchTab('login');
                    }, 2000);
                }
            } catch (error) {
                document.getElementById('registerError').textContent = 'Error de conexión. Por favor, intenta de nuevo.';
                document.getElementById('registerError').classList.add('show');
                console.error('Error:', error);
            } finally {
                registerBtn.disabled = false;
                registerBtn.textContent = 'Registrarse';
            }
        });
    </script>
</body>
</html>
