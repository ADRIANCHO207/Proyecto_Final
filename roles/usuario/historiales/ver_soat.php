<?php
session_start();
require_once('../../../conecct/conex.php');
$db = new Database();
$con = $db->conectar();
include '../../../includes/validarsession.php';

$sql = $con->prepare("
    SELECT s.id_soat, v.placa, s.fecha_expedicion, s.fecha_vencimiento,
           a.nombre, e.soat_est
    FROM soat s
    INNER JOIN vehiculos v ON s.id_placa = v.placa
    INNER JOIN aseguradoras_soat a ON s.id_aseguradora = a.id_asegura
    INNER JOIN estado_soat e ON s.id_estado = e.id_stado
    ORDER BY s.fecha_expedicion DESC
");
$sql->execute();
$soats = $sql->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's full name and foto_perfil for the profile section
$nombre_completo = $_SESSION['nombre_completo'] ?? null;
$foto_perfil = $_SESSION['foto_perfil'] ?? null;
if (!$nombre_completo || !$foto_perfil) {
    $user_query = $con->prepare("SELECT nombre_completo, foto_perfil FROM usuarios WHERE documento = :documento");
    $user_query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $user_query->execute();
    $user = $user_query->fetch(PDO::FETCH_ASSOC);
    $nombre_completo = $user['nombre_completo'] ?? 'Usuario';
    $foto_perfil = $user['foto_perfil'] ?? '/proyecto/roles/usuario/css/img/perfil.jpg';
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['foto_perfil'] = $foto_perfil;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de SOAT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fuentes e iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CDN (si no lo tienes local) -->
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
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .estado-vencido {
            background-color: #f8d7da;
            color: #842029;
        }

        .estado-pendiente {
            background-color: #fff3cd;
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

<?php include('../header.php'); ?>

<div class="container">
    <h2><i class="fas fa-file-shield me-2"></i>Listado de SOAT Registrados</h2>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Fecha Expedición</th>
                    <th>Fecha Vencimiento</th>
                    <th>Aseguradora</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($soats) > 0): ?>
                    <?php foreach ($soats as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['placa']) ?></td>
                            <td><?= htmlspecialchars($row['fecha_expedicion']) ?></td>
                            <td><?= htmlspecialchars($row['fecha_vencimiento']) ?></td>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td>
                                <?php
                                    $estado = strtolower($row['soat_est']);
                                    $clase = match ($estado) {
                                        'vigente' => 'estado-vigente',
                                        'vencido' => 'estado-vencido',
                                        'pendiente' => 'estado-pendiente',
                                        default => 'bg-secondary text-white'
                                    };
                                ?>
                                <span class="badge <?= $clase ?>"><?= ucfirst($estado) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay registros de SOAT.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
