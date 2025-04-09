<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Mantenimiento</title>
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
        <a href="../index.php" class="boton">Atrás</a>
    </div>
    <div class="menu">
        <a href="index.php">Panel de control</a>
        <a href="registro_mantenimiento.php">Registro de vehiculos</a>
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
    <h1>Registro de Mantenimiento</h1>
    <p>Ingrese los detalles sobre el último mantenimiento y la duración estimada hasta el próximo.</p>

    <!-- Notificaciones -->
    <div class="alertas">
        <h2>Notificaciones de Mantenimiento</h2>
    </div>

 
    <form action="guardar_mantenimiento.php" method="post" class="form-descarga">
    
        <label for="vehiculo">Seleccione el vehículo:</label>
        <select name="vehiculo" id="vehiculo" class="select-vehiculo" required>
            <option value="">Seleccione un vehículo</option>
       
            <option value="vehiculo1">Vehículo 1</option>
            <option value="vehiculo2">Vehículo 2</option>
            <option value="vehiculo3">Vehículo 3</option>
        </select>

        
        <label for="fecha_mantenimiento">Fecha del último mantenimiento:</label>
        <input type="date" name="fecha_mantenimiento" id="fecha_mantenimiento" class="input-fecha" required>

        <label for="duracion_mantenimiento">Duración estimada hasta el próximo mantenimiento (en días):</label>
        <input type="number" name="duracion_mantenimiento" id="duracion_mantenimiento" class="input-duracion" required>

     
        <button type="submit" class="boton">Guardar</button>
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
