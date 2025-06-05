<?php
session_start();
require_once('../../../conecct/conex.php');
include '../../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();
$data = json_decode(file_get_contents("respuesta.json"), true);
    $multas = $data['data']['multas'] ?? [];
    $por_pagina = 5;
    $pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
    $inicio = ($pagina_actual - 1) * $por_pagina;
    $multas_paginadas = array_slice($multas, $inicio, $por_pagina);
    $total_paginas = ceil(count($multas) / $por_pagina);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multas y Comparendos</title>
    <link rel="shortcut icon" href="../../css/img/logo_sinfondo.png">
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../header.php'); ?>

<div class="contenedor">
    <h1>Resultado de la Consulta de Multas</h1>

   

<?php if (count($multas_paginadas) > 0): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Placa</th>
                <th>Secretaría</th>
                <th>Infracción</th>
                <th>Estado</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($multas_paginadas as $index => $multa): ?>
                <tr>
                    <td><?= $multa['numeroComparendo'] ?? 'N/A' ?></td>
                    <td><?= $multa['placa'] ?></td>
                    <td><?= $multa['organismoTransito'] ?></td>
                    <td><?= $multa['infracciones'][0]['codigoInfraccion'] ?> - <?= $multa['infracciones'][0]['descripcionInfraccion'] ?></td>
                    <td><?= $multa['estadoCartera'] ?></td>
                    <td>$<?= number_format($multa['valor'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td><strong>Número Resolución:</strong> <?= $multa['numeroResolucion'] ?></td>
                    <td><?= $multa['placa'] ?></td>
                    <td><?= $multa['organismoTransito'] ?></td>
                    <td><?= $multa['infracciones'][0]['codigoInfraccion'] ?></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td><strong>Fecha Resolución:</strong> <?= $multa['fechaResolucion'] ?></td>
                    <td><strong>Estado Cartera:</strong> <?= $multa['estadoCartera'] ?></td>
                    <td><strong>Valor Infracción:</strong> $<?= number_format($multa['infracciones'][0]['valorInfraccion'], 0, ',', '.') ?></td>
                    <td colspan="2"></td>
                    <td>
                        <a href="ver_detalle.php?id=<?= urlencode($multa['numeroComparendo'] ?? $multa['numeroResolucion']) ?>" class="btn btn-primary">Ver detalles</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PAGINACIÓN -->
    <div style="margin-top:20px;">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?pagina=<?= $i ?>" style="margin-right:10px;<?= $i == $pagina_actual ? 'font-weight: bold;' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php else: ?>
    <p class="sin-multas">No hay multas asociadas.</p>
<?php endif; ?>
