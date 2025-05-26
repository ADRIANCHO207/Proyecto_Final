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

$tire_query = $con->prepare("SELECT fecha_programada, fecha_realizada, observaciones 
                             FROM mantenimiento 
                             WHERE placa = :placa AND id_tipo_mantenimiento = 'LLANTAS'");
$tire_query->bindParam(':placa', $placa, PDO::PARAM_STR);
$tire_query->execute();
$tire = $tire_query->fetch(PDO::FETCH_ASSOC);

$today = date('Y-m-d');
$days_since = $tire && $tire['fecha_realizada'] ? (strtotime($today) - strtotime($tire['fecha_realizada'])) / (60 * 60 * 24) : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Llantas - Flotax AGC</title>
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
        <h1>Mantenimiento de Llantas</h1>
        <?php if ($placa): ?>
            <p><strong>Placa del vehículo:</strong> <?php echo htmlspecialchars($placa); ?></p>
            <?php if ($tire): ?>
                <p><strong>Última revisión:</strong> <?php echo htmlspecialchars($tire['fecha_realizada'] ?? 'No realizada'); ?></p>
                <p><strong>Observaciones:</strong> <?php echo htmlspecialchars($tire['observaciones'] ?? 'Ninguna'); ?></p>
                <?php if ($days_since > 180): ?>
                    <p class="alert warning">¡Alerta! Han pasado más de 6 meses desde la última revisión de llantas.</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="alert error">No se encontraron registros de mantenimiento de llantas.</p>
            <?php endif; ?>
        <?php else: ?>
            <p class="alert error">No tienes vehículos registrados.</p>
        <?php endif; ?>
    </div>
</body>
</html>