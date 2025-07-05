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

// Crear tabla de logs si no existe
$conexion->exec("
    CREATE TABLE IF NOT EXISTS logs_sistema (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50),
        accion TEXT,
        ip VARCHAR(45),
        user_agent TEXT,
        fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_fecha (fecha_hora),
        INDEX idx_usuario (usuario)
    )
");

try {
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    
    switch ($action) {
        case 'obtener_logs':
            $page = $_GET['page'] ?? 1;
            $limit = $_GET['limit'] ?? 50;
            $offset = ($page - 1) * $limit;
            
            $filtro_usuario = $_GET['usuario'] ?? '';
            $filtro_fecha = $_GET['fecha'] ?? '';
            
            $where = "WHERE 1=1";
            $params = [];
            
            if ($filtro_usuario) {
                $where .= " AND usuario LIKE ?";
                $params[] = "%{$filtro_usuario}%";
            }
            
            if ($filtro_fecha) {
                $where .= " AND DATE(fecha_hora) = ?";
                $params[] = $filtro_fecha;
            }
            
            $stmt = $conexion->prepare("
                SELECT * FROM logs_sistema 
                {$where}
                ORDER BY fecha_hora DESC 
                LIMIT {$limit} OFFSET {$offset}
            ");
            $stmt->execute($params);
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Contar total para paginación
            $stmt_count = $conexion->prepare("SELECT COUNT(*) as total FROM logs_sistema {$where}");
            $stmt_count->execute($params);
            $total = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
            
            echo json_encode([
                'success' => true, 
                'data' => $logs,
                'total' => $total,
                'page' => $page,
                'total_pages' => ceil($total / $limit)
            ]);
            break;
            
        case 'registrar_log':
            $usuario = $_POST['usuario'] ?? $_SESSION['superadmin_documento'] ?? 'Sistema';
            $accion = $_POST['accion'] ?? '';
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $stmt = $conexion->prepare("
                INSERT INTO logs_sistema (usuario, accion, ip, user_agent) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$usuario, $accion, $ip, $user_agent]);
            
            echo json_encode(['success' => true, 'message' => 'Log registrado']);
            break;
            
        case 'exportar_logs':
            $formato = $_GET['formato'] ?? 'csv';
            $fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
            $fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');
            
            $stmt = $conexion->prepare("
                SELECT * FROM logs_sistema 
                WHERE DATE(fecha_hora) BETWEEN ? AND ?
                ORDER BY fecha_hora DESC
            ");
            $stmt->execute([$fecha_inicio, $fecha_fin]);
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($formato === 'csv') {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="logs_' . date('Y-m-d') . '.csv"');
                
                $output = fopen('php://output', 'w');
                fputcsv($output, ['ID', 'Usuario', 'Acción', 'IP', 'Fecha/Hora']);
                
                foreach ($logs as $log) {
                    fputcsv($output, [
                        $log['id'],
                        $log['usuario'],
                        $log['accion'],
                        $log['ip'],
                        $log['fecha_hora']
                    ]);
                }
                
                fclose($output);
                exit;
            }
            
            echo json_encode(['success' => true, 'data' => $logs]);
            break;
            
        case 'limpiar_logs_antiguos':
            $dias = $_POST['dias'] ?? 90;
            
            $stmt = $conexion->prepare("DELETE FROM logs_sistema WHERE fecha_hora < DATE_SUB(NOW(), INTERVAL ? DAY)");
            $stmt->execute([$dias]);
            
            $eliminados = $stmt->rowCount();
            
            echo json_encode(['success' => true, 'message' => "Se eliminaron {$eliminados} registros antiguos"]);
            break;
            
        default:
            echo json_encode(['error' => 'Acción no válida']);
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}

// Función para registrar automáticamente las acciones del superadmin
function registrarAccion($accion) {
    global $conexion;
    
    $usuario = $_SESSION['superadmin_documento'] ?? 'Sistema';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    try {
        $stmt = $conexion->prepare("
            INSERT INTO logs_sistema (usuario, accion, ip, user_agent) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$usuario, $accion, $ip, $user_agent]);
    } catch (Exception $e) {
        error_log("Error registrando log: " . $e->getMessage());
    }
}
?>