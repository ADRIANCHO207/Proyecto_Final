<?php
session_start();

// Verificar autenticación de superadmin
if (!isset($_SESSION['superadmin_logged']) || $_SESSION['superadmin_logged'] !== true) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

require_once '../conecct/conex.php';

$database = new Database();
$conexion = $database->conectar();

header('Content-Type: application/json');

try {
    $accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
    
    switch ($accion) {
        case 'listar':
            $stmt = $conexion->prepare("
                SELECT u.*, r.tip_rol,
                       CONCAT(u.nombres, ' ', u.apellidos) as nombre_completo
                FROM usuarios u 
                LEFT JOIN roles r ON u.id_rol = r.id_rol 
                ORDER BY u.fecha_registro DESC
            ");
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $usuarios]);
            break;
            
        case 'obtener':
            $id = $_POST['id'] ?? $_GET['id'] ?? 0;
            $stmt = $conexion->prepare("
                SELECT u.*, r.tip_rol 
                FROM usuarios u 
                LEFT JOIN roles r ON u.id_rol = r.id_rol 
                WHERE u.id_usuario = ?
            ");
            $stmt->execute([$id]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                echo json_encode(['status' => 'success', 'data' => $usuario]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
            }
            break;
            
        case 'crear':
            $documento = $_POST['documento'] ?? '';
            $tipo_documento = $_POST['tipo_documento'] ?? '';
            $nombres = $_POST['nombres'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
            $direccion = $_POST['direccion'] ?? '';
            $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
            $id_rol = $_POST['id_rol'] ?? 2;
            $id_estado_usuario = $_POST['id_estado_usuario'] ?? 1;
            
            // Verificar si el documento ya existe
            $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE documento = ?");
            $stmt->execute([$documento]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'El documento ya está registrado']);
                break;
            }
            
            // Verificar si el email ya existe
            $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'El email ya está registrado']);
                break;
            }
            
            $stmt = $conexion->prepare("
                INSERT INTO usuarios (documento, tipo_documento, nombres, apellidos, email, telefono, 
                                    fecha_nacimiento, direccion, password, id_rol, id_estado_usuario, fecha_registro) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$documento, $tipo_documento, $nombres, $apellidos, $email, $telefono, 
                          $fecha_nacimiento, $direccion, $password, $id_rol, $id_estado_usuario]);
            
            echo json_encode(['status' => 'success', 'message' => 'Usuario creado exitosamente']);
            break;
            
        case 'actualizar':
            $id = $_POST['id'] ?? 0;
            $documento = $_POST['documento'] ?? '';
            $tipo_documento = $_POST['tipo_documento'] ?? '';
            $nombres = $_POST['nombres'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
            $direccion = $_POST['direccion'] ?? '';
            $id_rol = $_POST['id_rol'] ?? 2;
            $id_estado_usuario = $_POST['id_estado_usuario'] ?? 1;
            
            $sql = "UPDATE usuarios SET documento = ?, tipo_documento = ?, nombres = ?, apellidos = ?, 
                    email = ?, telefono = ?, fecha_nacimiento = ?, direccion = ?, id_rol = ?, id_estado_usuario = ?";
            $params = [$documento, $tipo_documento, $nombres, $apellidos, $email, $telefono, 
                      $fecha_nacimiento, $direccion, $id_rol, $id_estado_usuario];
            
            if (!empty($_POST['password'])) {
                $sql .= ", password = ?";
                $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            
            $sql .= " WHERE id_usuario = ?";
            $params[] = $id;
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute($params);
            
            echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado exitosamente']);
            break;
            
        case 'eliminar':
            $id = $_POST['id'] ?? 0;
            $stmt = $conexion->prepare("UPDATE usuarios SET id_estado_usuario = 2 WHERE id_usuario = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['status' => 'success', 'message' => 'Usuario desactivado exitosamente']);
            break;
            
        case 'obtener_roles':
            $stmt = $conexion->prepare("SELECT * FROM roles ORDER BY id_rol");
            $stmt->execute();
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $roles]);
            break;
            
        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
    }
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>