<?php
// index.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar SOAT</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="logo">
        <img src="../../logo.jpeg" alt="Logo">
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
        <img src="../../perfil.jpg" alt="Usuario" class="imagen-usuario">
        <div class="info-usuario">
            <span>Nombres, Apellidos</span>
            <br>
            <span>Perfil Administrador</span>
        </div>
    </div>
</div>

<!-- Contenido principal -->
<div class="contenido">
    <h1>Generar SOAT</h1>
    <p>Para generar y descargar el SOAT de un vehículo, ingresa los siguientes datos:</p>
    <form action="descargar_soat.php" method="post">
        <div class="form-group">
            <label for="placa">Placa del vehículo:</label>
            <input type="text" id="placa" name="placa" required>
        </div>
        <div class="form-group">
            <label for="tipo-documento">Tipo de documento:</label>
            <select id="tipo-documento" name="tipo-documento">
                <option value="cedula">Cédula de ciudadanía</option>
                <option value="cedula-extranjeria">Cédula de extranjería</option>
                <option value="otro">Otro</option>
            </select>
        </div>
        <div class="form-group">
            <label for="numero-documento">Número de documento:</label>
            <input type="text" id="numero-documento" name="numero-documento" required>
        </div>
        <button type="submit">Generar y Enviar SOAT</button>
    </form>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <a href="cerrar_sesion.php">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
</div>

<!-- Footer -->
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
