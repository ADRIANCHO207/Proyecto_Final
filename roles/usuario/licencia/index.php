<?php
session_start();
require_once('../../../conecct/conex.php');
include '../../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();

// Check for documento in session
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

// Mock query for license (replace with actual license table if exists)
$license_query = $con->prepare("SELECT reset_expira AS fecha_vencimiento FROM usuarios WHERE documento = :documento");
$license_query->bindParam(':documento', $documento, PDO::PARAM_STR);
$license_query->execute();
$license = $license_query->fetch(PDO::FETCH_ASSOC);

$today = date('Y-m-d');
$status = ($license && $license['fecha_vencimiento'] >= $today) ? 'Vigente' : 'Vencida o no registrada';
$days_left = $license && $license['fecha_vencimiento'] ? (strtotime($license['fecha_vencimiento']) - strtotime($today)) / (60 * 60 * 24) : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Licencia de Conducción - Flotax AGC</title>
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
        <h1>Estado de Licencia de Conducción</h1>
        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($nombre_completo); ?></p>
        <?php if ($license && $license['fecha_vencimiento']): ?>
            <p><strong>Fecha de vencimiento:</strong> <?php echo htmlspecialchars($license['fecha_vencimiento']); ?></p>
            <p><strong>Estado:</strong> <?php echo $status; ?></p>
            <?php if ($days_left <= 30 && $days_left > 0): ?>
                <p class="alert warning">¡Alerta! Tu licencia vencerá en <?php echo floor($days_left); ?> días.</p>
            <?php elseif ($days_left <= 0): ?>
                <p class="alert error">¡Urgente! Tu licencia está vencida.</p>
            <?php endif; ?>
        <?php else: ?>
            <p class="alert error">No se encontró información de licencia de conducción.</p>
        <?php endif; ?>
    </div>
</body>
</html>