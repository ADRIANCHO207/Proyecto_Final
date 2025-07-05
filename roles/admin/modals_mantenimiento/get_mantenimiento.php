<?php
session_start();
require_once('../../../conecct/conex.php');
include '../../../includes/validarsession.php';

header('Content-Type: application/json');

$db = new Database();
$con = $db->conectar();

// Validar sesión
if (!isset($_SESSION['documento'])) {
    echo json_encode(['success' => false, 'message' => 'Sesión no válida']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID de mantenimiento requerido']);
    exit;
}

try {
    $query = $con->prepare("
        SELECT 
            m.id_mantenimiento,
            m.placa,
            m.id_tipo_mantenimiento,
            m.fecha_programada,
            m.fecha_realizada,
            m.observaciones,
            m.kilometraje_actual,
            m.proximo_cambio_km,
            m.proximo_cambio_fecha,
            tm.descripcion as tipo_descripcion
        FROM mantenimiento m
        LEFT JOIN tipo_mantenimiento tm ON m.id_tipo_mantenimiento = tm.id_tipo_mantenimiento
        WHERE m.id_mantenimiento = :id
    ");
    
    $query->bindParam(':id', $id);
    $query->execute();
    
    $mantenimiento = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($mantenimiento) {
        echo json_encode([
            'success' => true, 
            'mantenimiento' => $mantenimiento
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mantenimiento no encontrado']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>