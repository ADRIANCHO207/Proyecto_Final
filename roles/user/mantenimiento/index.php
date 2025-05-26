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

$maint_query = $con->prepare("SELECT m.fecha_programada, m.fecha_realizada, m.observaciones, t.descripcion 
                              FROM mantenimiento m 
                              JOIN tipo_mantenimiento t ON m.id_tipo_mantenimiento = t.id_tipo_mantenimiento 
                              WHERE m.placa = :placa");
$maint_query->bindParam(':placa', $placa, PDO::PARAM_STR);
$maint_query->execute();
$maintenances = $maint_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento y Aceite - Flotax AGC</title>
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
        <h1>Mantenimiento y Aceite</h1>
        <?php if ($placa): ?>
            <p><strong>Placa del vehículo:</strong> <?php echo htmlspecialchars($placa); ?></p>
            <?php if ($maintenances): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Fecha Programada</th>
                            <th>Fecha Realizada</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($maintenances as $maint): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($maint['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($maint['fecha_programada']); ?></td>
                                <td><?php echo htmlspecialchars($maint['fecha_realizada'] ?? 'Pendiente'); ?></td>
                                <td><?php echo htmlspecialchars($maint['observaciones'] ?? 'Ninguna'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="alert error">No se encontraron registros de mantenimiento.</p>
            <?php endif; ?>
        <?php else: ?>
            <p class="alert error">No tienes vehículos registrados.</p>
        <?php endif; ?>
    </div>
</body>
</html>