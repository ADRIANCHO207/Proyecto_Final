<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flotax AGC - Inicio</title>
    <link rel="shortcut icon" href="../../css/img/logo_sinfondo.png">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="header">
    <div class="logo">
        <a href="index.php">
            <img src="css/img/logo.jpeg" alt="Logo">
            <span class="empresa">Flotax AGC</span>
        </a>
    </div>
    <div class="menu">
        <a href="index.php" class="boton">Inicio</a>
        <a href="vehiculos/formulario.php" class="boton">Registrar Vehículo</a>
    </div>
    <div class="perfil" onclick="openModal()">
        <img src="<?php echo ($foto_perfil); ?>" alt="Usuario" class="imagen-usuario">
        <div class="info-usuario">
            <span><?php echo htmlspecialchars($nombre_completo); ?></span>
            <span>Perfil Usuario</span>
        </div>
    </div>
</div>

<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #ffffff, #f1f1f1);
        padding: 20px 40px;
        border-bottom: 3px solid #d32f2f;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .logo {
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .logo:hover {
        transform: scale(1.05);
    }

    .logo img {
        width: 75px;
        height: 70px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .empresa {
        font-size: 32px;
        font-weight: 700;
        color: #d32f2f;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .logo a{
        text-decoration: none;
        text-align:center;
        display: flex;
        align-items: center;
    }

    .menu {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .menu .boton {
        background: linear-gradient(135deg, #d32f2f, #b71c1c);
        color: #fff;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: solid 3px #d32f2f;
        transition: transform 0.3s ease;
    }
    

    .menu .boton:hover {
        background: transparent;
        border: solid 3px #d32f2f;
        transform: scale(1.05);
        color: #333;
    }

    .perfil {
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .perfil:hover {
        transform: scale(1.05);
    }

    .imagen-usuario {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        margin-right: 5px;
        border: 2px solid #d32f2f;
        object-fit: cover;
    }

    .info-usuario {
        text-align: right;
    }

    .info-usuario span {
        display: block;
        color: #333;
        font-size: 16px;
        font-weight: 600;
    }

    .info-usuario span:last-child {
        font-size: 14px;
        font-weight: 400;
        color: #666;
    }
</style>