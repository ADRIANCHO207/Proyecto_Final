<?php
session_start();
require_once('../../../conecct/conex.php');
require_once('../../../includes/validarsession.php');
$db = new Database();
$con = $db->conectar();

$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    header('Location: ../../../login/login.php');
    exit;
}

// Fetch nombre_completo and foto_perfil if not in session
$nombre_completo = $_SESSION['nombre_completo'] ?? null;
$foto_perfil = $_SESSION['foto_perfil'] ?? null;
if (!$nombre_completo || !$foto_perfil) {
    $user_query = $con->prepare("SELECT nombre_completo, foto_perfil FROM usuarios WHERE documento = :documento");
    $user_query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $user_query->execute();
    $user = $user_query->fetch(PDO::FETCH_ASSOC);
    $nombre_completo = $user['nombre_completo'] ?? 'Usuario';
    $foto_perfil = $user['foto_perfil'] ?: '/proyecto/roles/usuario/css/img/perfil.jpg';
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['foto_perfil'] = $foto_perfil;
}

// Fetch tipos de mantenimiento
$tipos_mantenimiento_query = $con->prepare("SELECT id_tipo_mantenimiento, descripcion FROM tipo_mantenimiento");
$tipos_mantenimiento_query->execute();
$tipos_mantenimiento = $tipos_mantenimiento_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch mantenimientos
$mantenimientos_query = $con->prepare("
    SELECT m.*, v.placa, tm.descripcion AS tipo_mantenimiento,
           GROUP_CONCAT(c.Trabajo, ': $', d.subtotal) AS detalles_trabajos
    FROM mantenimiento m
    JOIN vehiculos v ON m.placa = v.placa
    JOIN tipo_mantenimiento tm ON m.id_tipo_mantenimiento = tm.id_tipo_mantenimiento
    LEFT JOIN detalles_mantenimiento_clasificacion d ON m.id_mantenimiento = d.id_mantenimiento
    LEFT JOIN clasificacion_trabajo c ON d.id_trabajo = c.id
    WHERE v.Documento = :documento
    GROUP BY m.id_mantenimiento
");
$mantenimientos_query->bindParam(':documento', $documento, PDO::PARAM_STR);
$mantenimientos_query->execute();
$mantenimientos = $mantenimientos_query->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Flotax AGC - Mantenimiento General</title>
    <link rel="shortcut icon" href="../../../css/img/logo_sinfondo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f0f2f5;
            padding-bottom: 60px;
        }

        .container {
            margin-top: 60px;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }

        .table thead {
            background-color: #0d6efd;
            color: white;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .badge {
            font-size: 0.9rem;
            padding: 6px 10px;
            border-radius: 12px;
        }

        .estado-vigente {
            background-color:rgb(100, 253, 184);
            color: #0f5132;
        }

        .estado-vencido {
            background-color:rgb(248, 102, 114);
            color:rgb(123, 0, 0);
        }

        .estado-pendiente {
            background-color:rgb(255, 219, 100);
            color: #664d03;
        }

        @media screen and (max-width: 768px) {
            .container {
                padding: 15px;
            }

            table {
                font-size: 0.9rem;
            }

            h2 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>

    <?php
        include('../header.php'); 
    ?>

    <div class="container">
        <h2>Historial de Mantenimientos</h2>

        <div class="table-responsive">
        
            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Tipo de Mantenimiento</th>
                        <th>Fecha Programada</th>
                        <th>Fecha Realizada</th>
                        <th>Kilometraje Actual</th>
                        <th>Próximo Mantenimiento (km)</th>
                        <th>Próximo Mantenimiento (Fecha)</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tbody>
                    <?php if(count($mantenimientos) > 0): ?>
                        <?php foreach ($mantenimientos as $mantenimiento): ?>
                            <tr <?php
                                $hoy = new DateTime();
                                $proximo = new DateTime($mantenimiento['proximo_cambio_fecha']);
                                $diferencia_dias = $hoy->diff($proximo)->days;
                                if ($proximo >= $hoy && $diferencia_dias <= 30) {
                                    echo 'class="alerta"';
                                }
                            ?>>
                                <td><?php echo htmlspecialchars($mantenimiento['placa']); ?></td>
                                <td>
                                    <?php
                                        $tipo = strtolower($mantenimiento ['tipo_mantenimiento']);
                                        $clase = match ($tipo) {
                                            'correctivo' => 'estado-vencido',
                                            'preventivo' => 'estado-pendiente',
                                            default => 'bg-secondary text-white'
                                        };
                                    ?>
                                    <span class="badge <?= $clase ?>"><?= ucfirst($tipo); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($mantenimiento['fecha_programada']); ?></td>
                                <td><?php echo htmlspecialchars($mantenimiento['fecha_realizada'] ?: 'No realizada'); ?></td>
                                <td><?php echo htmlspecialchars($mantenimiento['kilometraje_actual'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($mantenimiento['proximo_cambio_km'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($mantenimiento['proximo_cambio_fecha'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($mantenimiento['observaciones'] ?: 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No hay registros de mantenimiento.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
      include('../../../includes/auto_logout_modal.php');
    ?>

</body>