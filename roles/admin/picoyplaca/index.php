

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Pico y Placa</title>
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
    <h1>Administrar Pico y Placa</h1>
    <p>Configure los días y placas de vehículos que no pueden transitar.</p>

    
        <form action="guardar_pico_placa.php" method="post" class="formulario-pico-placa">
    <label for="placa">Número de placa:</label>
    <input type="text" id="placa" name="placa" placeholder="ABC-123" required>

    <label for="dias">Días de restricción:</label>
    <ul id="dias" class="lista-dias">
        <li><input type="checkbox" name="dias[]" value="lunes"> Lunes</li>
        <li><input type="checkbox" name="dias[]" value="martes"> Martes</li>
        <li><input type="checkbox" name="dias[]" value="miércoles"> Miércoles</li>
        <li><input type="checkbox" name="dias[]" value="jueves"> Jueves</li>
        <li><input type="checkbox" name="dias[]" value="viernes"> Viernes</li>
    </ul>

    <button type="submit" class="boton">Guardar y Enviar</button>
</form>



    <!-- Lista de placas y sus días de restricción -->
    <div class="lista-restricciones">
        <h2>Placas y días de restricción</h2>
        <table class="tabla-restricciones">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Días de Restricción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ABC-123</td>
                    <td>Lunes, Miércoles</td>
                </tr>
                <tr>
                    <td>DEF-456</td>
                    <td>Martes, Jueves</td>
                </tr>
                <!-- Agrega más filas dinámicamente con PHP -->
            </tbody>
        </table>
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
