<?php
session_start();
require_once('../../../conecct/conex.php');
include '../../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();

$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    header('Location: ../../../login.php');
    exit;
}

// Fetch nombre_completo if not in session
$nombre_completo = $_SESSION['nombre_completo'] ?? null;
if (!$nombre_completo) {
    $user_query = $con->prepare("SELECT nombre_completo FROM usuarios WHERE documento = :documento");
    $user_query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $user_query->execute();
    $user = $user_query->fetch(PDO::FETCH_ASSOC);
    $nombre_completo = $user['nombre_completo'] ?? 'Usuario';
    $_SESSION['nombre_completo'] = $nombre_completo;
}

// Get vehicle plate
$query = $con->prepare("SELECT placa FROM vehiculos WHERE Documento = :documento");
$query->bindParam(':documento', $documento, PDO::PARAM_STR);
$query->execute();
$vehiculo = $query->fetch(PDO::FETCH_ASSOC);
$placa = $vehiculo['placa'] ?? null;

// Mock Pico y Placa logic (Ibagué example)
$last_digit = $placa ? substr($placa, -1) : null;
$days = [
    '0' => 'Lunes', '1' => 'Lunes',
    '2' => 'Martes', '3' => 'Martes',
    '4' => 'Miércoles', '5' => 'Miércoles',
    '6' => 'Jueves', '7' => 'Jueves',
    '8' => 'Viernes', '9' => 'Viernes'
];
$restriction_day = $last_digit !== null && isset($days[$last_digit]) ? $days[$last_digit] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pico y Placa - Flotax AGC</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../../../css/img/logo.png" alt="Logo">
            <span class="empresa">Flotax AGC</span>
        </div>
        <div class="menu">
            <a href="../index.php">Volver al Panel</a>
        </div>
        <div class="perfil">
            <img src="../css/img/perfil.jpg" alt="Usuario" class="imagen-usuario">
            <div class="info-usuario">
                <span><?php echo htmlspecialchars($nombre_completo); ?></span>
                <br>
                <span>Usuario</span>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Pico y Placa</h1>
        <?php if ($placa): ?>
            <p><strong>Placa del vehículo:</strong> <?php echo htmlspecialchars($placa); ?></p>
            <?php if ($restriction_day): ?>
                <p><strong>Restricción:</strong> Tu vehículo tiene Pico y Placa los días <strong><?php echo $restriction_day; ?></strong>.</p>
                <p><strong>Horario:</strong> 7:00 AM - 8:00 PM (sujeto a normativa local).</p>
            <?php else: ?>
                <p class="alert error">No se pudo determinar el Pico y Placa.</p>
            <?php endif; ?>
        <?php else: ?>
            <p class="alert error">No tienes vehículos registrados.</p>
        <?php endif; ?>
    </div>
</body>
</html>