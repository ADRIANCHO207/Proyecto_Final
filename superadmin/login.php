<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración básica del documento -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FleetManager Pro - Superadmin Login</title>

    <!-- Ícono de pestaña -->
    <link rel="shortcut icon" href="../css/img/logo_sinfondo.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome para íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <style>
        /* Variables de colores globales */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
        }
        
        /* Estilo del cuerpo con fondo degradado y centrado */
        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Logo centrado */
        img {
            max-width: 40%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Contenedor del formulario de login */
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }

        /* Encabezado del formulario */
        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            text-align: center;
            padding: 2rem;
        }

        .login-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Cuerpo del formulario */
        .login-body {
            padding: 2rem;
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        /* Botón de login */
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Alertas de éxito/error */
        .alert {
            border-radius: 10px;
            border: none;
        }

        /* Estilos para los campos de entrada */
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
    </style>
</head>
<body>
    <!-- Contenedor principal del login -->
    <div class="login-container">
        <!-- Encabezado del login con logo -->
        <div class="login-header">
            <img src="../css/img/blanco.png">
            <h3>Flotax AGC</h3>
            <p class="mb-0">Superadmin Access</p>
        </div>
        
        <!-- Cuerpo del formulario -->
        <div class="login-body">
            <!-- Contenedor de alertas -->
            <div id="alert-container"></div>
            
            <!-- Formulario de login -->
            <form id="loginForm">
                <!-- Campo: Documento -->
                <div class="form-floating">
                    <input type="text" class="form-control" id="documento" name="documento" placeholder="Documento" required>
                    <label for="documento"><i class="fas fa-user me-2"></i>Documento</label>
                </div>
                
                <!-- Campo: Contraseña -->
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                    <label for="password"><i class="fas fa-lock me-2"></i>Contraseña</label>
                </div>
                
                <!-- Botón para enviar el formulario -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </button>
                </div>
            </form>
            
            <!-- Mensaje informativo -->
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Acceso restringido solo para superadministradores
                </small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para manejar el envío del formulario -->
    <script>
        // Espera a que el formulario sea enviado
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Previene el envío tradicional

            const formData = new FormData(this); // Obtiene los datos del formulario

            // Envía los datos a través de fetch al archivo de autenticación
            fetch('auth_superadmin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Convierte la respuesta en JSON
            .then(data => {
                const alertContainer = document.getElementById('alert-container');

                if (data.status === 'success') {
                    // Muestra alerta de éxito
                    alertContainer.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>${data.message}
                        </div>
                    `;
                    // Redirige al dashboard tras 1 segundo
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
                    }, 1000);
                } else {
                    // Muestra alerta de error
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                // Maneja errores de conexión
                console.error('Error:', error);
                document.getElementById('alert-container').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Error de conexión
                    </div>
                `;
            });
        });
    </script>
</body>
</html>
