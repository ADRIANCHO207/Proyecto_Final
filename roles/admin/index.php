<?php
session_start();
require_once('../../conecct/conex.php');
include '../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flotax AGC</title>
    <link rel="shortcut icon" href="../../css/img/logo_sinfondo.png">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>

<div class="header">
    <div class="logo">

        <img src="../../css/img/logo_sinfondo.png" alt="Loo">
        <span class="empresa">Flotax AGC</span>
    </div>
    <div class="menu">
        <a href="usuarios/index.php">Panel de control</a>
        <a href="#">Registro de vehículos</a>
    </div>
    <div class="perfil">
        <div class="info-usuario">
            <span> <?php echo""?></span>
            <br>
        </div>
    </div>
</div>

<div class="alertas">
    <h1>Alertas</h1>
    <a href="soat/index.php" class="boton">SOAT</a>
    <a href="tecnomecanica/index.php" class="boton">Tecnomecánica</a>
    <a href="licencia/index.php" class="boton">Licencia de conducción</a>
    <a href="picoyplaca/index.php" class="boton">Pico y placa</a>
    <a href="llantas/index.php" class="boton">Llantas</a>
    <a href="mantenimiento/index.php" class="boton">Mantenimiento y aceite</a>
</div>

<div class="garage">
    <h2>Garage</h2>
    <form>
        <select>
            <option value="vehiculos">Vehículos</option>
        </select>
        <button type="button">Mostrar</button>
    </form>
</div>

<div class="sidebar">
    <a href="../../includes/salir.php" class="logout">
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
