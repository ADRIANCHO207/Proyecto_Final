<?php
require_once('../../conecct/conex.php');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reporte_dashboard.csv');

$output = fopen('php://output', 'w');

// Encabezado general
fputcsv($output, ['==============================================']);
fputcsv($output, ['              REPORTE GENERAL DASHBOARD']);
fputcsv($output, ['==============================================']);
fputcsv($output, ['Fecha de Generación:', date('d/m/Y')]);
fputcsv($output, []); // Línea en blanco

// Conexión
$db = new Database();
$con = $db->conectar();

// Consultas
$consultas = [
    ['label' => 'Total de Vehículos', 'query' => "SELECT COUNT(*) FROM vehiculos"],
    ['label' => 'Vehículos al Día', 'query' => "SELECT COUNT(*) FROM vehiculos WHERE id_estado = 10"],
    ['label' => 'Total de Usuarios', 'query' => "SELECT COUNT(*) FROM usuarios"],
    // Puedes agregar más indicadores aquí...
];

// Encabezado de tabla
fputcsv($output, ['Indicador', 'Cantidad']);
fputcsv($output, ['--------------------------', '---------']);

// Datos
foreach ($consultas as $consulta) {
    $stmt = $con->prepare($consulta['query']);
    $stmt->execute();
    $valor = $stmt->fetchColumn();
    fputcsv($output, [$consulta['label'], $valor]);
}

fputcsv($output, []); // Línea final en blanco
fputcsv($output, ['Fin del Reporte']);
fclose($output);
exit;
?>
