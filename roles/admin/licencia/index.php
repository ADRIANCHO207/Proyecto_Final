<?php
// index.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Licencias de Conducción</title>
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
    <h1>Gestión de Licencias de Conducción</h1>
    <p>Seleccione el usuario y la licencia de conducción para gestionar y actualizar la información de la licencia.</p>

    <form action="actualizar/index.php" method="post" class="form-descarga">
        <!-- Selección de usuario -->
        <label for="usuario">Seleccione el usuario:</label>
        <select name="usuario" id="usuario" class="select-vehiculo" required>
            <option value="">Seleccione un usuario</option>
            <!-- Opciones de usuarios dinámicas -->
            <option value="usuario1">Usuario 1</option>
            <option value="usuario2">Usuario 2</option>
            <!-- Agrega más opciones según sea necesario -->
        </select>

        <!-- Selección de licencia -->
        <label for="licencia">Seleccione la licencia de conducción:</label>
        <select name="licencia" id="licencia" class="select-vehiculo" required>
            <option value="">Seleccione una licencia</option>
            <!-- Opciones de licencias dinámicas -->
            <option value="licencia1">Licencia 1 (Vigente)</option>
            <option value="licencia2">Licencia 2 (Vencida)</option>
            <option value="licencia3">Licencia 3 (Vigente)</option>
        </select>

        <!-- Botón para ver y actualizar la licencia -->
        <button type="submit" class="boton">Ver y actualizar</button>
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
