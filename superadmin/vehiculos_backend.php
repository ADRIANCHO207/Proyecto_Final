<?php
session_start();

// Verificar autenticación de superadmin
if (!isset($_SESSION['superadmin_logged']) || $_SESSION['superadmin_logged'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

require_once '../conecct/conex.php';

$database = new Database();
$conexion = $database->conectar();

header('Content-Type: application/json');

try {
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    
    switch ($action) {
        case 'listar_vehiculos':
            $stmt = $conexion->prepare("
                SELECT v.*, m.nombre_marca, tv.vehiculo as tipo_vehiculo, ev.estado, u.nombre as registrado_por_nombre
                FROM vehiculos v
                LEFT JOIN marca m ON v.id_marca = m.id_marca
                LEFT JOIN tipo_vehiculo tv ON v.tipo_vehiculo = tv.id_tipo_vehiculo
                LEFT JOIN estado_vehiculo ev ON v.id_estado = ev.id_estado
                LEFT JOIN usuarios u ON v.registrado_por = u.documento
                ORDER BY v.fecha_registro DESC
            ");
            $stmt->execute();
            $vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $vehiculos]);
            break;
            
        case 'obtener_vehiculo':
            $placa = $_GET['placa'] ?? '';
            $stmt = $conexion->prepare("
                SELECT v.*, m.nombre_marca, tv.vehiculo as tipo_vehiculo, ev.estado
                FROM vehiculos v
                LEFT JOIN marca m ON v.id_marca = m.id_marca
                LEFT JOIN tipo_vehiculo tv ON v.tipo_vehiculo = tv.id_tipo_vehiculo
                LEFT JOIN estado_vehiculo ev ON v.id_estado = ev.id_estado
                WHERE v.placa = ?
            ");
            $stmt->execute([$placa]);
            $vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $vehiculo]);
            break;
            
        case 'crear_vehiculo':
            $placa = $_POST['placa'] ?? '';
            $tipo_vehiculo = $_POST['tipo_vehiculo'] ?? '';
            $documento = $_POST['documento'] ?? '';
            $id_marca = $_POST['id_marca'] ?? '';
            $modelo = $_POST['modelo'] ?? '';
            $kilometraje_actual = $_POST['kilometraje_actual'] ?? 0;
            $id_estado = $_POST['id_estado'] ?? 1;
            $registrado_por = $_SESSION['superadmin_documento'] ?? '';
            
            $stmt = $conexion->prepare("
                INSERT INTO vehiculos (placa, tipo_vehiculo, Documento, id_marca, modelo, kilometraje_actual, id_estado, fecha_registro, registrado_por) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)
            ");
            $stmt->execute([$placa, $tipo_vehiculo, $documento, $id_marca, $modelo, $kilometraje_actual, $id_estado, $registrado_por]);
            
            echo json_encode(['success' => true, 'message' => 'Vehículo creado exitosamente']);
            break;
            
        case 'actualizar_vehiculo':
            $placa = $_POST['placa'] ?? '';
            $tipo_vehiculo = $_POST['tipo_vehiculo'] ?? '';
            $documento = $_POST['documento'] ?? '';
            $id_marca = $_POST['id_marca'] ?? '';
            $modelo = $_POST['modelo'] ?? '';
            $kilometraje_actual = $_POST['kilometraje_actual'] ?? 0;
            $id_estado = $_POST['id_estado'] ?? 1;
            
            $stmt = $conexion->prepare("
                UPDATE vehiculos SET 
                tipo_vehiculo = ?, Documento = ?, id_marca = ?, modelo = ?, 
                kilometraje_actual = ?, id_estado = ?
                WHERE placa = ?
            ");
            $stmt->execute([$tipo_vehiculo, $documento, $id_marca, $modelo, $kilometraje_actual, $id_estado, $placa]);
            
            echo json_encode(['success' => true, 'message' => 'Vehículo actualizado exitosamente']);
            break;
            
        case 'eliminar_vehiculo':
            $placa = $_POST['placa'] ?? '';
            $stmt = $conexion->prepare("UPDATE vehiculos SET id_estado = 3 WHERE placa = ?"); // Estado inactivo
            $stmt->execute([$placa]);
            
            echo json_encode(['success' => true, 'message' => 'Vehículo desactivado exitosamente']);
            break;
            
        case 'obtener_marcas':
            $stmt = $conexion->prepare("SELECT * FROM marca ORDER BY nombre_marca");
            $stmt->execute();
            $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $marcas]);
            break;
            
        case 'obtener_tipos_vehiculo':
            $stmt = $conexion->prepare("SELECT * FROM tipo_vehiculo ORDER BY vehiculo");
            $stmt->execute();
            $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $tipos]);
            break;
            
        case 'obtener_estados':
            $stmt = $conexion->prepare("SELECT * FROM estado_vehiculo ORDER BY id_estado");
            $stmt->execute();
            $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $estados]);
            break;
            
        default:
            echo json_encode(['error' => 'Acción no válida']);
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}
?>