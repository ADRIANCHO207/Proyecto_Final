<?php
session_start();
require_once('../../../conecct/conex.php');
include '../../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();

$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    header('Location: ../../login.php');
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

$tecno_query = $con->prepare("SELECT fecha_inicio, fecha_vencimiento 
                              FROM documentacion 
                              WHERE placa = :placa AND id_tipo_documento = 'TECNOMECANICA'");
$tecno_query->bindParam(':placa', $placa, PDO::PARAM_STR);
$tecno_query->execute();
$tecno = $tecno_query->fetch(PDO::FETCH_ASSOC);

$today = date('Y-m-d');
$status = ($tecno && $tecno['fecha_vencimiento'] >= $today) ? 'Vigente' : 'Vencido o no registrado';
$days_left = $tecno ? (strtotime($tecno['fecha_vencimiento']) - strtotime($today)) / (60 * 60 * 24) : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnico-Mecánica - Flotax AGC</title>
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
        <h1>Estado de Técnico-Mecánica</h1>
        <?php if ($placa): ?>
            <p><strong>Placa del vehículo:</strong> <?php echo htmlspecialchars($placa); ?></p>
            <?php if ($tecno): ?>
                <p><strong>Fecha de inicio:</strong> <?php echo htmlspecialchars($tecno['fecha_inicio']); ?></p>
                <p><strong>Fecha de vencimiento:</strong> <?php echo htmlspecialchars($tecno['fecha_vencimiento']); ?></p>
                <p><strong>Estado:</strong> <?php echo $status; ?></p>
                <?php if ($days_left <= 30 && $days_left > 0): ?>
                    <p class="alert warning">¡Alerta! Tu Técnico-Mecánica vencerá en <?php echo floor($days_left); ?> días.</p>
                <?php elseif ($days_left <= 0): ?>
                    <p class="alert error">¡Urgente! Tu Técnico-Mecánica está vencida.</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="alert error">No se encontró información de Técnico-Mecánica para este vehículo.</p>
            <?php endif; ?>
        <?php else: ?>
            <p class="alert error">No tienes vehículos registrados.</p>
        <?php endif; ?>
    </div>
</body>
</html>