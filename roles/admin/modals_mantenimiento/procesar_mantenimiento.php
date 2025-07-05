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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$accion = $_POST['accion'] ?? '';

try {
    switch ($accion) {
        case 'agregar':
            $placa = $_POST['placa'] ?? '';
            $id_tipo_mantenimiento = $_POST['id_tipo_mantenimiento'] ?? '';
            $fecha_programada = $_POST['fecha_programada'] ?? '';
            $fecha_realizada = !empty($_POST['fecha_realizada']) ? $_POST['fecha_realizada'] : null;
            $observaciones = $_POST['observaciones'] ?? null;
            $kilometraje_actual = !empty($_POST['kilometraje_actual']) ? (int)$_POST['kilometraje_actual'] : null;
            $proximo_cambio_km = !empty($_POST['proximo_cambio_km']) ? (int)$_POST['proximo_cambio_km'] : null;
            $proximo_cambio_fecha = !empty($_POST['proximo_cambio_fecha']) ? $_POST['proximo_cambio_fecha'] : null;
            
            // Validaciones
            if (empty($placa) || empty($id_tipo_mantenimiento) || empty($fecha_programada)) {
                echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
                exit;
            }
            
            // Verificar que la placa existe
            $check_placa = $con->prepare("SELECT placa FROM vehiculos WHERE placa = :placa");
            $check_placa->bindParam(':placa', $placa);
            $check_placa->execute();
            
            if ($check_placa->rowCount() === 0) {
                echo json_encode(['success' => false, 'message' => 'La placa del vehículo no existe']);
                exit;
            }
            
            // Insertar mantenimiento
            $query = $con->prepare("
                INSERT INTO mantenimiento 
                (placa, id_tipo_mantenimiento, fecha_programada, fecha_realizada, observaciones, 
                 kilometraje_actual, proximo_cambio_km, proximo_cambio_fecha) 
                VALUES 
                (:placa, :id_tipo_mantenimiento, :fecha_programada, :fecha_realizada, :observaciones, 
                 :kilometraje_actual, :proximo_cambio_km, :proximo_cambio_fecha)
            ");
            
            $query->bindParam(':placa', $placa);
            $query->bindParam(':id_tipo_mantenimiento', $id_tipo_mantenimiento);
            $query->bindParam(':fecha_programada', $fecha_programada);
            $query->bindParam(':fecha_realizada', $fecha_realizada);
            $query->bindParam(':observaciones', $observaciones);
            $query->bindParam(':kilometraje_actual', $kilometraje_actual);
            $query->bindParam(':proximo_cambio_km', $proximo_cambio_km);
            $query->bindParam(':proximo_cambio_fecha', $proximo_cambio_fecha);
            
            if ($query->execute()) {
                echo json_encode(['success' => true, 'message' => 'Mantenimiento agregado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar el mantenimiento']);
            }
            break;
            
        case 'editar':
            $id_mantenimiento = $_POST['id_mantenimiento'] ?? '';
            $placa = $_POST['placa'] ?? '';
            $id_tipo_mantenimiento = $_POST['id_tipo_mantenimiento'] ?? '';
            $fecha_programada = $_POST['fecha_programada'] ?? '';
            $fecha_realizada = !empty($_POST['fecha_realizada']) ? $_POST['fecha_realizada'] : null;
            $observaciones = $_POST['observaciones'] ?? null;
            $kilometraje_actual = !empty($_POST['kilometraje_actual']) ? (int)$_POST['kilometraje_actual'] : null;
            $proximo_cambio_km = !empty($_POST['proximo_cambio_km']) ? (int)$_POST['proximo_cambio_km'] : null;
            $proximo_cambio_fecha = !empty($_POST['proximo_cambio_fecha']) ? $_POST['proximo_cambio_fecha'] : null;
            
            // Validaciones
            if (empty($id_mantenimiento) || empty($placa) || empty($id_tipo_mantenimiento) || empty($fecha_programada)) {
                echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
                exit;
            }
            
            // Verificar que el mantenimiento existe
            $check_mant = $con->prepare("SELECT id_mantenimiento FROM mantenimiento WHERE id_mantenimiento = :id");
            $check_mant->bindParam(':id', $id_mantenimiento);
            $check_mant->execute();
            
            if ($check_mant->rowCount() === 0) {
                echo json_encode(['success' => false, 'message' => 'El mantenimiento no existe']);
                exit;
            }
            
            // Actualizar mantenimiento
            $query = $con->prepare("
                UPDATE mantenimiento SET 
                placa = :placa, 
                id_tipo_mantenimiento = :id_tipo_mantenimiento, 
                fecha_programada = :fecha_programada, 
                fecha_realizada = :fecha_realizada, 
                observaciones = :observaciones, 
                kilometraje_actual = :kilometraje_actual, 
                proximo_cambio_km = :proximo_cambio_km, 
                proximo_cambio_fecha = :proximo_cambio_fecha 
                WHERE id_mantenimiento = :id_mantenimiento
            ");
            
            $query->bindParam(':id_mantenimiento', $id_mantenimiento);
            $query->bindParam(':placa', $placa);
            $query->bindParam(':id_tipo_mantenimiento', $id_tipo_mantenimiento);
            $query->bindParam(':fecha_programada', $fecha_programada);
            $query->bindParam(':fecha_realizada', $fecha_realizada);
            $query->bindParam(':observaciones', $observaciones);
            $query->bindParam(':kilometraje_actual', $kilometraje_actual);
            $query->bindParam(':proximo_cambio_km', $proximo_cambio_km);
            $query->bindParam(':proximo_cambio_fecha', $proximo_cambio_fecha);
            
            if ($query->execute()) {
                echo json_encode(['success' => true, 'message' => 'Mantenimiento actualizado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el mantenimiento']);
            }
            break;
            
        case 'eliminar':
            $id_mantenimiento = $_POST['id_mantenimiento'] ?? '';
            
            if (empty($id_mantenimiento)) {
                echo json_encode(['success' => false, 'message' => 'ID de mantenimiento requerido']);
                exit;
            }
            
            // Verificar que el mantenimiento existe
            $check_mant = $con->prepare("SELECT id_mantenimiento FROM mantenimiento WHERE id_mantenimiento = :id");
            $check_mant->bindParam(':id', $id_mantenimiento);
            $check_mant->execute();
            
            if ($check_mant->rowCount() === 0) {
                echo json_encode(['success' => false, 'message' => 'El mantenimiento no existe']);
                exit;
            }
            
            // Eliminar mantenimiento
            $query = $con->prepare("DELETE FROM mantenimiento WHERE id_mantenimiento = :id_mantenimiento");
            $query->bindParam(':id_mantenimiento', $id_mantenimiento);
            
            if ($query->execute()) {
                echo json_encode(['success' => true, 'message' => 'Mantenimiento eliminado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el mantenimiento']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            break;
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>