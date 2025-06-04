<?php
// form_tecnomecanica.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Tecnomecánica</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="header">
    <div class="logo">
        <img src="../../logo.jpeg" alt="Logo" class="logo-redondo">
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

<div class="contenido">
    <h1>Formulario de Tecnomecánica</h1>
    <p>Complete los siguientes campos para generar la tecnomecánica del vehículo.</p>

    <form action="generar_tecnomecanica.php" method="post" class="form-tecnomecanica">
        <!-- Datos del usuario -->
        <fieldset>
            <legend>Datos del Usuario</legend>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" required>

            <label for="identificacion">Número de Identificación:</label>
            <input type="text" name="identificacion" id="identificacion" placeholder="Número de ID" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" name="telefono" id="telefono" placeholder="Número de teléfono" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" id="email" placeholder="Correo electrónico" required>
        </fieldset>

        <!-- Datos del vehículo -->
        <fieldset>
            <legend>Datos del Vehículo</legend>
            <label for="placa">Placa:</label>
            <input type="text" name="placa" id="placa" placeholder="Número de placa" required>

            <label for="marca">Marca:</label>
            <input type="text" name="marca" id="marca" placeholder="Marca del vehículo" required>

            <label for="modelo">Modelo:</label>
            <input type="text" name="modelo" id="modelo" placeholder="Modelo del vehículo" required>

            <label for="anio">Año de Fabricación:</label>
            <input type="number" name="anio" id="anio" placeholder="Año de fabricación" min="1900" max="2099" step="1" required>

            <label for="color">Color:</label>
            <input type="text" name="color" id="color" placeholder="Color del vehículo" required>
        </fieldset>

        <!-- Información adicional -->
        <fieldset>
            <legend>Información Adicional</legend>
            <label for="tipo_tecnomecanica">Tipo de Tecnomecánica:</label>
            <select name="tipo_tecnomecanica" id="tipo_tecnomecanica" required>
                <option value="regular">Regular</option>
                <option value="prioritaria">Prioritaria</option>
            </select>

            <label for="fecha">Fecha de Solicitud:</label>
            <input type="date" name="fecha" id="fecha" required>
        </fieldset>

        <!-- Botón para enviar el formulario -->
        <button type="submit" class="boton">Generar Tecnomecánica y enviar</button>
    </form>
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
