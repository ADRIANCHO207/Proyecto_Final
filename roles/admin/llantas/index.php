<?php
// index.php

// Función para calcular si es necesario mostrar una notificación
function verificarNotificacion($fechaCambio, $duracionLlantas) {
    $fechaActual = new DateTime();
    $fechaCambioDate = new DateTime($fechaCambio);
    
    // Sumamos la duración estimada (en días) a la fecha del último cambio
    $fechaCambioDate->modify("+{$duracionLlantas} days");
    
    // Calculamos la diferencia entre la fecha actual y la fecha estimada para el próximo cambio
    $diferencia = $fechaActual->diff($fechaCambioDate);

    // Si quedan menos de 7 días para la fecha estimada, mostramos una notificación
    return $diferencia->days <= 7 && $fechaActual < $fechaCambioDate;
}

// Datos de ejemplo (normalmente, estos vendrían de una base de datos)
$registros = [
    ["usuario" => "Juan Pérez", "vehiculo" => "Vehículo 1", "fecha_cambio" => "2024-12-01", "duracion_llantas" => 30],
    ["usuario" => "Ana Gómez", "vehiculo" => "Vehículo 2", "fecha_cambio" => "2024-11-20", "duracion_llantas" => 20]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cambio de Llantas</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="header">
    <div class="logo">
        <img src="../logo.jpeg" alt="Logo" class="logo-redondo">
        <span class="empresa">Flotax AGC</span>
    </div>
    <div class="boton-inicio">
        <a href="../index.php" class="boton">Atras</a>
    </div>
    <div class="menu">
        <a href="index.php">Panel de control</a>
        <a href="registro_llantas.php">Registro de llantas</a>
    </div>
    <div class="perfil">
        <img src="../perfil.jpg" alt="Usuario" class="imagen-usuario">
        <div class="info-usuario">
            <span>Nombres, Apellidos</span>
            <br>
            <span>Perfil Administrador</span>
        </div>
    </div>
</div>

<div class="contenido">
    <h1>Registro de Cambio de Llantas</h1>
    <p>Ingrese los detalles sobre el último cambio de llantas y la duración estimada de las llantas.</p>

    <form action="guardar_cambio_llantas.php" method="post" class="form-descarga">
        <!-- Selección de usuario -->
        <label for="usuario">Seleccione el usuario:</label>
        <select name="usuario" id="usuario" class="select-usuario" required>
            <option value="">Seleccione un usuario</option>
            <!-- Opciones de usuarios dinámicas -->
            <option value="Juan Pérez">Juan Pérez</option>
            <option value="Ana Gómez">Ana Gómez</option>
        </select>

        <!-- Selección de vehículo -->
        <label for="vehiculo">Seleccione el vehículo:</label>
        <select name="vehiculo" id="vehiculo" class="select-vehiculo" required>
            <option value="">Seleccione un vehículo</option>
            <!-- Opciones de vehículos dinámicas -->
            <option value="vehiculo1">Vehículo 1</option>
            <option value="vehiculo2">Vehículo 2</option>
        </select>

        <!-- Fecha del último cambio de llantas -->
        <label for="fecha_cambio">Fecha del último cambio de llantas:</label>
        <input type="date" name="fecha_cambio" id="fecha_cambio" class="input-fecha" required>

        <!-- Duración estimada de las llantas en días -->
        <label for="duracion_llantas">Duración estimada de las llantas (en días):</label>
        <input type="number" name="duracion_llantas" id="duracion_llantas" class="input-duracion" required>

        <!-- Botón para guardar la información -->
        <button type="submit" class="boton">Guardar</button>
    </form>

    <div class="alertas">
        <h2>Notificaciones</h2>
        <?php foreach ($registros as $registro): ?>
            <?php if (verificarNotificacion($registro['fecha_cambio'], $registro['duracion_llantas'])): ?>
                <div class="notificacion">
                    <p><strong>Usuario:</strong> <?php echo $registro['usuario']; ?></p>
                    <p><strong>Vehículo:</strong> <?php echo $registro['vehiculo']; ?></p>
                    <p><strong>Próximo cambio recomendado:</strong> ¡Cambiar las llantas pronto!</p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<div class="sidebar">
    <a href="cerrar_sesion.php" class="logout">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
</div>

<footer class="footer">
    <div class="footer-content">
        <p>© 2024 Flotax AGC. Todos los derechos reservados.</p>
        <div class="social-media">
            <a href="https://facebook.com" target="_blank" class="social-icon"><i class="bi bi-facebook"></i></a>
            <a href="https://twitter.com" target="_blank" class="social-icon"><i class="bi bi-twitter"></i></a>
            <a href="https://instagram.com" target="_blank" class="social-icon"><i class="bi bi-instagram"></i></a>
            <a href="https://wa.me/1234567890" target="_blank" class="social-icon"><i class="bi bi-whatsapp"></i></a>
        </div>
    </div>
</footer>

</body>
</html>