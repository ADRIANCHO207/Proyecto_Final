<?php
session_start();

// Verificar autenticación de superadmin
if (!isset($_SESSION['superadmin_logged']) || $_SESSION['superadmin_logged'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

require_once '../conecct/conex.php';

$database = new Database();
$conexion = $database->conectar();

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'crear_licencia':
        // Get POST data and call function with proper parameters
        $datos = [
            'nombre_empresa' => $_POST['nombre_empresa'] ?? '',
            'usuario_asignado' => $_POST['usuario_asignado'] ?? '',
            'tipo_licencia' => $_POST['tipo_licencia'] ?? '',
            'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
            'fecha_vencimiento' => $_POST['fecha_vencimiento'] ?? ''
        ];
        $resultado = crearLicencia($conexion, $datos);
        echo json_encode($resultado);
        break;
    case 'renovar_licencia':
        renovarLicencia();
        break;
    case 'actualizar_limites':
        actualizarLimites();
        break;
    case 'suspender_licencia':
        suspenderLicencia();
        break;
    case 'obtener_licencias':
        obtenerLicencias();
        break;
    case 'validar_licencia':
        validarLicenciaCompleta();
        break;
    case 'obtener_estadisticas':
        obtenerEstadisticasCompletas();
        break;
    case 'exportar_licencia':
        exportarInformacionLicencia();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}

function crearLicencia($conexion, $datos) {
    try {
        $stmt = $conexion->prepare("
            INSERT INTO sistema_licencias 
            (nombre_empresa, usuario_asignado, tipo_licencia, fecha_inicio, fecha_vencimiento, max_usuarios, max_vehiculos, clave_licencia) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        // Definir límites según tipo de licencia
        $limites = [
            'basica' => ['usuarios' => 10, 'vehiculos' => 50],
            'profesional' => ['usuarios' => 50, 'vehiculos' => 200],
            'empresarial' => ['usuarios' => 100, 'vehiculos' => 500]
        ];
        
        $max_usuarios = $limites[$datos['tipo_licencia']]['usuarios'];
        $max_vehiculos = $limites[$datos['tipo_licencia']]['vehiculos'];
        $clave_licencia = 'LIC-' . strtoupper(uniqid());
        
        $stmt->execute([
            $datos['nombre_empresa'],
            $datos['usuario_asignado'],
            $datos['tipo_licencia'],
            $datos['fecha_inicio'],
            $datos['fecha_vencimiento'],
            $max_usuarios,
            $max_vehiculos,
            $clave_licencia
        ]);
        
        registrarLog($conexion, 'crear_licencia', "Licencia creada para empresa: {$datos['nombre_empresa']}, asignada a usuario: {$datos['usuario_asignado']}");
        
        return [
            'success' => true,
            'message' => 'Licencia creada exitosamente y asignada al usuario seleccionado',
            'clave_licencia' => $clave_licencia
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Error al crear la licencia: ' . $e->getMessage()
        ];
    }
}

function renovarLicencia() {
    global $conexion;
    
    try {
        $id_licencia = $_POST['id_licencia'] ?? '';
        
        if (empty($id_licencia)) {
            echo json_encode(['success' => false, 'message' => 'ID de licencia requerido']);
            return;
        }
        
        // Extender fecha de vencimiento por un año
        $stmt = $conexion->prepare("
            UPDATE sistema_licencias 
            SET fecha_vencimiento = DATE_ADD(fecha_vencimiento, INTERVAL 1 YEAR),
                estado = 'activa'
            WHERE id = ?
        ");
        
        $stmt->execute([$id_licencia]);
        
        registrarLog('Licencia renovada', "Licencia ID {$id_licencia} renovada por un año");
        
        echo json_encode(['success' => true, 'message' => 'Licencia renovada exitosamente']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al renovar licencia: ' . $e->getMessage()]);
    }
}

function actualizarLimites() {
    global $conexion;
    
    try {
        $id_licencia = $_POST['id_licencia'] ?? '';
        $max_usuarios = $_POST['max_usuarios'] ?? 0;
        $max_vehiculos = $_POST['max_vehiculos'] ?? 0;
        
        if (empty($id_licencia) || $max_usuarios <= 0 || $max_vehiculos <= 0) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }
        
        $stmt = $conexion->prepare("
            UPDATE sistema_licencias 
            SET max_usuarios = ?, max_vehiculos = ?
            WHERE id = ?
        ");
        
        $stmt->execute([$max_usuarios, $max_vehiculos, $id_licencia]);
        
        registrarLog('Límites actualizados', "Límites actualizados: {$max_usuarios} usuarios, {$max_vehiculos} vehículos");
        
        echo json_encode(['success' => true, 'message' => 'Límites actualizados exitosamente']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar límites: ' . $e->getMessage()]);
    }
}

function suspenderLicencia() {
    global $conexion;
    
    try {
        $id_licencia = $_POST['id_licencia'] ?? '';
        
        if (empty($id_licencia)) {
            echo json_encode(['success' => false, 'message' => 'ID de licencia requerido']);
            return;
        }
        
        $stmt = $conexion->prepare("
            UPDATE sistema_licencias 
            SET estado = 'suspendida'
            WHERE id = ?
        ");
        
        $stmt->execute([$id_licencia]);
        
        registrarLog('Licencia suspendida', "Licencia ID {$id_licencia} suspendida");
        
        echo json_encode(['success' => true, 'message' => 'Licencia suspendida exitosamente']);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al suspender licencia: ' . $e->getMessage()]);
    }
}

function obtenerLicencias() {
    global $conexion;
    
    try {
        $stmt = $conexion->prepare("SELECT * FROM sistema_licencias ORDER BY fecha_creacion DESC");
        $stmt->execute();
        $licencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $licencias]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener licencias: ' . $e->getMessage()]);
    }
}

function registrarLog($accion, $descripcion) {
    global $conexion;
    
    try {
        $documento_superadmin = $_SESSION['superadmin_documento'] ?? 'SYSTEM';
        
        $stmt = $conexion->prepare("
            INSERT INTO logs_sistema (usuario, accion, descripcion, fecha, ip_address) 
            VALUES (?, ?, ?, NOW(), ?)
        ");
        
        $stmt->execute([
            $documento_superadmin,
            $accion,
            $descripcion,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
        
    } catch (Exception $e) {
        error_log("Error al registrar log: " . $e->getMessage());
    }
}

function validarLicenciaCompleta() {
    require_once '../includes/LicenseValidator.php';
    global $conexion;
    
    $validator = new LicenseValidator($conexion);
    $resultado = $validator->validarLicencia();
    $estadisticas = $validator->obtenerEstadisticasUso();
    
    echo json_encode([
        'success' => true,
        'licencia_valida' => $resultado['valida'],
        'mensaje' => $resultado['mensaje'],
        'estadisticas' => $estadisticas,
        'dias_restantes' => $validator->diasRestantesLicencia()
    ]);
}

function obtenerEstadisticasCompletas() {
    require_once '../includes/LicenseValidator.php';
    global $conexion;
    
    $validator = new LicenseValidator($conexion);
    $estadisticas = $validator->obtenerEstadisticasUso();
    
    echo json_encode(['success' => true, 'data' => $estadisticas]);
}

function exportarInformacionLicencia() {
    require_once '../includes/LicenseValidator.php';
    global $conexion;
    
    $validator = new LicenseValidator($conexion);
    $info = $validator->obtenerInfoLicencia();
    $estadisticas = $validator->obtenerEstadisticasUso();
    
    $datos_exportacion = [
        'licencia' => $info,
        'estadisticas' => $estadisticas,
        'fecha_exportacion' => date('Y-m-d H:i:s')
    ];
    
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="licencia_info_' . date('Y-m-d') . '.json"');
    echo json_encode($datos_exportacion, JSON_PRETTY_PRINT);
}
?>