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
        case 'reporte_usuarios_activos':
            $stmt = $conexion->prepare("
                SELECT COUNT(*) as total_activos,
                       SUM(CASE WHEN id_rol = 1 THEN 1 ELSE 0 END) as administradores,
                       SUM(CASE WHEN id_rol = 2 THEN 1 ELSE 0 END) as usuarios_normales
                FROM usuarios WHERE estado = 'Activo'
            ");
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $resultado]);
            break;
            
        case 'reporte_vehiculos_estado':
            $stmt = $conexion->prepare("
                SELECT ev.estado, COUNT(*) as cantidad
                FROM vehiculos v
                LEFT JOIN estado_vehiculo ev ON v.id_estado = ev.id_estado
                GROUP BY v.id_estado, ev.estado
                ORDER BY cantidad DESC
            ");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $resultado]);
            break;
            
        case 'reporte_mantenimientos_mes':
            $mes = $_GET['mes'] ?? date('m');
            $año = $_GET['año'] ?? date('Y');
            
            $stmt = $conexion->prepare("
                SELECT 
                    COUNT(*) as total_mantenimientos,
                    SUM(CASE WHEN estado = 'Completado' THEN 1 ELSE 0 END) as completados,
                    SUM(CASE WHEN estado = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
                    SUM(CASE WHEN estado = 'En Proceso' THEN 1 ELSE 0 END) as en_proceso
                FROM mantenimiento 
                WHERE MONTH(fecha_programada) = ? AND YEAR(fecha_programada) = ?
            ");
            $stmt->execute([$mes, $año]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $resultado]);
            break;
            
        case 'reporte_actividad_usuarios':
            $stmt = $conexion->prepare("
                SELECT u.nombre, u.apellido, u.documento, u.fecha_registro,
                       COUNT(v.placa) as vehiculos_registrados
                FROM usuarios u
                LEFT JOIN vehiculos v ON u.documento = v.registrado_por
                WHERE u.estado = 'Activo'
                GROUP BY u.documento
                ORDER BY vehiculos_registrados DESC
                LIMIT 10
            ");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $resultado]);
            break;
            
        case 'reporte_licencias_vencimiento':
            $stmt = $conexion->prepare("
                SELECT l.*, u.nombre, u.apellido, cl.categoria
                FROM licencias l
                LEFT JOIN usuarios u ON l.documento = u.documento
                LEFT JOIN categoria_licencia cl ON l.id_categoria = cl.id_categoria
                WHERE l.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                ORDER BY l.fecha_vencimiento ASC
            ");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $resultado]);
            break;
            
        case 'exportar_reporte':
            $tipo = $_GET['tipo'] ?? '';
            $formato = $_GET['formato'] ?? 'excel';
            
            // Aquí implementarías la lógica de exportación
            echo json_encode(['success' => true, 'message' => 'Funcionalidad de exportación en desarrollo']);
            break;
            
        default:
            echo json_encode(['error' => 'Acción no válida']);
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}
?>