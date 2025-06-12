<?php
require_once('../../conecct/conex.php');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reporte_dashboard.csv');

$output = fopen('php://output', 'w');

// Encabezados del archivo
fputcsv($output, ['Resumen del Dashboard']);
fputcsv($output, ['Fecha', date('d/m/Y')]);
fputcsv($output, []); // Línea en blanco
fputcsv($output, ['Categoría', 'Cantidad']);

$db = new Database();
$con = $db->conectar();

// Total de vehículos
$stmt = $con->prepare("SELECT COUNT(*) AS total FROM vehiculos");
$stmt->execute();
$total_vehiculos = $stmt->fetchColumn();

// Total de usuarios
$stmt1 = $con->prepare("SELECT COUNT(*) AS total FROM usuarios");
$stmt1->execute();
$total_usuarios = $stmt1->fetchColumn();

// Vehículos al día
$stmt2 = $con->prepare("SELECT COUNT(*) AS total FROM vehiculos WHERE id_estado = 10");
$stmt2->execute();
$veh_dia = $stmt2->fetchColumn();

// Escribir datos
fputcsv($output, ['Total de Vehículos', $total_vehiculos]);
fputcsv($output, ['Total de Usuarios', $total_usuarios]);
fputcsv($output, ['Vehículos al Día', $veh_dia]);

fclose($output);
exit;
?>
