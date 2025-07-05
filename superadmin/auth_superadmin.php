<?php
session_start();
require_once('../conecct/conex.php');

$db = new Database();
$con = $db->conectar();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($documento) || empty($password)) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Todos los campos son obligatorios'
        ]);
        exit;
    }

    try {
        // Buscar usuario en la base de datos
        $sql = $con->prepare("SELECT u.*, r.tip_rol FROM usuarios u 
                             INNER JOIN roles r ON u.id_rol = r.id_rol 
                             WHERE u.documento = ? AND u.id_rol = 3");
        $sql->execute([$documento]);
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Credenciales inválidas o acceso no autorizado'
            ]);
            exit;
        }

        // Verificar contraseña
        if (!password_verify($password, $usuario['password'])) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Credenciales inválidas'
            ]);
            exit;
        }

        // Verificar que el usuario esté activo
        if ($usuario['id_estado_usuario'] != 1) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Usuario inactivo'
            ]);
            exit;
        }

        // Crear sesión
        $_SESSION['superadmin_documento'] = $usuario['documento'];
        $_SESSION['superadmin_nombre'] = $usuario['nombre_completo'];
        $_SESSION['superadmin_email'] = $usuario['email'];
        $_SESSION['superadmin_rol'] = $usuario['id_rol'];
        $_SESSION['superadmin_logged'] = true;

        // Registrar el acceso (opcional)
        $log_sql = $con->prepare("INSERT INTO log_accesos_superadmin (documento, fecha_acceso, ip_acceso) VALUES (?, NOW(), ?)");
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $log_sql->execute([$documento, $ip]);

        echo json_encode([
            'status' => 'success', 
            'message' => 'Acceso autorizado. Redirigiendo...'
        ]);

    } catch (Exception $e) {
        error_log("Error en autenticación superadmin: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'Error interno del servidor'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Método no permitido'
    ]);
}
?>