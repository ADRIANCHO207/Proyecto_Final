<?php
$error_mensaje = $_GET['error'] ?? 'Error de licencia';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Licencia - Sistema de Gestión de Flota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            text-align: center;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-4"></i>
        <h2 class="mb-3">Error de Licencia</h2>
        <p class="text-muted mb-4"><?php echo htmlspecialchars($error_mensaje); ?></p>
        <p class="mb-4">Por favor, contacte al administrador del sistema para resolver este problema.</p>
        <a href="/Proyecto/" class="btn btn-primary me-2">
            <i class="fas fa-home me-2"></i>Volver al Inicio
        </a>
        <a href="mailto:admin@flotax.com" class="btn btn-outline-primary">
            <i class="fas fa-envelope me-2"></i>Contactar Soporte
        </a>
    </div>
</body>
</html>