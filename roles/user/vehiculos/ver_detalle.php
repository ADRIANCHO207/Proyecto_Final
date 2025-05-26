<?php
session_start();
require_once('../../../conecct/conex.php');
include '../../../includes/validarsession.php';

$id = $_GET['id'] ?? null;
$data = json_decode(file_get_contents("respuesta.json"), true);
$multas = $data['data']['multas'] ?? [];

$multa = null;
foreach ($multas as $item) {
    if ($item['numeroComparendo'] == $id || $item['numeroResolucion'] == $id) {
        $multa = $item;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Multa</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="contenedor">
        <h1>Detalles de la Multa</h1>

        <?php if ($multa): ?>
            <p><strong>Placa:</strong> <?= $multa['placa'] ?></p>
            <p><strong>Nombre Infractor:</strong> <?= $multa['infractor']['nombre'] . " " . $multa['infractor']['apellido'] ?></p>
            <p><strong>Departamento:</strong> <?= $multa['departamento'] ?></p>
            <p><strong>Estado Cartera:</strong> <?= $multa['estadoCartera'] ?></p>
            <p><strong>Fecha Comparendo:</strong> <?= $multa['fechaComparendo'] ?></p>
            <p><strong>Valor Total a Pagar:</strong> $<?= number_format($multa['valorPagar'], 0, ',', '.') ?></p>
            <p><strong>Infracción:</strong> <?= $multa['infracciones'][0]['descripcionInfraccion'] ?></p>
            <p><strong>Valor Infracción:</strong> $<?= number_format($multa['infracciones'][0]['valorInfraccion'], 0, ',', '.') ?></p>
            <p><strong>Resolución:</strong> <?= $multa['numeroResolucion'] ?> del <?= $multa['fechaResolucion'] ?></p>
            <p><a href="javascript:history.back()">← Volver</a></p>
        <?php else: ?>
            <p>No se encontró la multa solicitada.</p>
        <?php endif; ?>
    </div>
</body>
</html>
