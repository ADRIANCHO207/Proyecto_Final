<?php
// ver_licencia.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Licencia de Conducción</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="header">
    <div class="logo">
        <img src="../logo.jpeg" alt="Logo" class="logo-redondo">
        <span class="empresa">Flotax AGC</span>
    </div>
    <div class="boton-inicio">
        <a href="index.php" class="boton">Atras</a>
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
    <h1>Detalles de la Licencia de Conducción</h1>
    <p>A continuación, se muestra la información de la licencia de conducción. Puede actualizarla si ya está vencida.</p>

    <div class="detalle-licencia">
        <h2>Información de la Licencia</h2>
        <p><strong>Nombre del Titular:</strong> Juan Pérez</p>
        <p><strong>Número de Licencia:</strong> 12345678</p>
        <p><strong>Tipo de Licencia:</strong> Tipo B</p>
        <p><strong>Fecha de Emisión:</strong> 2020-01-15</p>
        <p><strong>Fecha de Expiración:</strong> 2024-01-15</p>
        <p><strong>Estado:</strong> Vencida</p>
    </div>

    <form action="actualizar_licencia.php" method="post" class="form-descarga">
        <label for="nueva_fecha">Fecha de Expiración (Nueva):</label>
        <input type="date" name="nueva_fecha" id="nueva_fecha" class="input-fecha" required>

        <button type="submit" class="boton">Actualizar Licencia</button>
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
