<?php
session_start();
require_once('../../../conecct/conex.php');
include '../../../includes/validarsession.php';

$db = new Database();
$con = $db->conectar();
$code = $_SESSION['documento'];

// Consulta corregida con WHERE documento = :code
$sql = $con->prepare("SELECT * FROM usuarios 
    INNER JOIN roles ON usuarios.id_rol = roles.id_rol 
    INNER JOIN estado_usuario ON usuarios.id_estado_usuario = estado_usuario.id_estado 
    WHERE documento = :code");
$sql->bindParam(':code', $code);
$sql->execute();
$fila = $sql->fetch();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    


    <div class="navbar">
        <div class="logo">
            <img src="../../../css/img/logo_sinfondo.png" alt="Logo">
            <span class="empresa">Flotax AGC</span>
            </div>
        
        <div class="boton-inicio">
        <a href="../index.php" class="boton">Atrás</a>
    </div>


    <div class="menu">
        <a href="index.php">Panel de control</a>
        <a href="registro_vehiculos.php">Registro de vehículos</a>
    </div>

        <div class="perfil">
            <img src="../perfil.jpg" alt="Usuario">
            <div>
                <span><?= $fila['nombre_completo'] ?></span><br>
                <small><?= $fila['tip_rol'] ?></small>
            </div>
        </div>
    </div>

    <!-- Contenedor principal con sidebar y contenido -->
    <div class="container">
        <!-- Sidebar lateral -->
        <div class="sidebar">
            <img src="../img/J_E_A_P_O_S_T-removebg-preview.png" alt="Logo">
            <a href="#solicitudes">Solicitudes</a>
            <a href="#usuarios">Usuarios</a>
            <a href="#notificaciones">Notificaciones</a>
            <a href="#perfil">Perfil</a>
            <a href="#configuracion">Configuración</a>
        </div>

        <!-- Contenido -->
        <div class="content">
            <h1>Bienvenido, <?= $fila['nombre_completo'] ?>. Su rol es <?= $fila['tip_rol'] ?>.</h1>
            <div class="chart">
                <div class="chart-placeholder">Gráfico aquí</div>
            </div>
        </div>
    </div>

</body>
</html>
