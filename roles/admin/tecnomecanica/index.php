<?php
// index.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Tecnomecánica</title>
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
        <a href="registro_vehiculos.php">Registro de vehículos</a>
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
    <h1>Generar Tecnomecánica</h1>
    <p>Seleccione el usuario y el vehículo para generar y enviar la tecnomecánica correspondiente.</p>

    <form action="descargar/index.php" method="post" class="form-descarga">
        <!-- Selección de usuario -->
        <label for="usuario">Seleccione el usuario:</label>
        <select name="usuario" id="usuario" class="select-vehiculo" required>
            <option value="">Seleccione un usuario</option>
            <!-- Opciones de usuarios dinámicas -->
            <option value="usuario1">Usuario 1</option>
            <option value="usuario2">Usuario 2</option>
            <!-- Agrega más opciones según sea necesario -->
        </select>

        <!-- Selección de vehículo -->
        <label for="vehiculo">Seleccione el vehículo:</label>
        <select name="vehiculo" id="vehiculo" class="select-vehiculo" required>
            <option value="">Seleccione un vehículo</option>
            <!-- Opciones de vehículos que se actualizarán dinámicamente según el usuario seleccionado -->
            <option value="vehiculo1">Vehículo 1</option>
            <option value="vehiculo2">Vehículo 2</option>
            <option value="vehiculo3">Vehículo 3</option>
        </select>

        <!-- Botón para generar y enviar la tecnomecánica -->
        <button type="submit" class="boton">Generar</button>
    </form>
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
