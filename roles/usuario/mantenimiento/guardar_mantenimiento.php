<?php
session_start();
require_once('../../../conecct/conex.php');
require_once('../../../includes/validarsession.php');

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/php_errors.log'); // Ajusta la ruta

$db = new Database();
$con = $db->conectar();

if (!$con) {
    error_log('Error de conexión a la base de datos.');
    echo json_encode(['success' => false, 'errors' => ['Error de conexión a la base de datos.']]);
    exit;
}

$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    error_log('Sesión no válida: documento no encontrado.');
    echo json_encode(['success' => false, 'errors' => ['Sesión no válida. Inicia sesión.']]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = trim($_POST['placa'] ?? '');
    $id_tipo_mantenimiento = trim($_POST['tipo_mantenimiento'] ?? '');
    $fecha_programada = trim($_POST['fecha_programada'] ?? '');
    $fecha_realizada = trim($_POST['fecha_realizada'] ?? '');
    $kilometraje_actual = trim($_POST['kilometraje_actual'] ?? '');
    $proximo_cambio_km = trim($_POST['proximo_cambio_km'] ?? '');
    $proximo_cambio_fecha = trim($_POST['proximo_cambio_fecha'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');

    $errors = [];
    error_log('Datos POST recibidos: ' . json_encode($_POST));

    // Validaciones
    if (empty($placa)) {
        $errors[] = 'El vehículo es obligatorio.';
    } else {
        $placa_query = $con->prepare('SELECT placa FROM vehiculos WHERE placa = :placa AND Documento = :documento');
        $placa_query->execute([':placa' => $placa, ':documento' => $documento]);
        if ($placa_query->rowCount() === 0) {
            $errors[] = 'Placa no válida o no pertenece al usuario.';
        }
    }

    if (empty($id_tipo_mantenimiento)) {
        $errors[] = 'El tipo de mantenimiento es obligatorio.';
    } else {
        $tipo_query = $con->prepare('SELECT id_tipo_mantenimiento FROM tipo_mantenimiento WHERE id_tipo_mantenimiento = :id_tipo_mantenimiento');
        $tipo_query->execute([':id_tipo_mantenimiento' => $id_tipo_mantenimiento]);
        if ($tipo_query->rowCount() === 0) {
            $errors[] = 'Tipo de mantenimiento no válido.';
        }
    }

    if (empty($fecha_programada)) {
        $errors[] = 'Fecha programada obligatoria.';
    } elseif (!DateTime::createFromFormat('Y-m-d', $fecha_programada)) {
        $errors[] = 'Formato de fecha programada inválido.';
    }

    if (!empty($fecha_realizada)) {
        $date = DateTime::createFromFormat('Y-m-d', $fecha_realizada);
        $now = new DateTime();
        if (!$date || $date > $now) {
            $errors[] = 'Fecha realizada no puede ser futura.';
        }
    }

    if (!empty($kilometraje_actual) && (!is_numeric($kilometraje_actual) || $kilometraje_actual < 0)) {
        $errors[] = 'Kilometraje actual debe ser positivo.';
    }

    if (!empty($proximo_cambio_km) && (!is_numeric($proximo_cambio_km) || $proximo_cambio_km < 0)) {
        $errors[] = 'Próximo cambio (km) debe ser positivo.';
    }

    if (!empty($proximo_cambio_fecha)) {
        $date = DateTime::createFromFormat('Y-m-d', $proximo_cambio_fecha);
        $now = new DateTime();
        if (!$date || $date < $now) {
            $errors[] = 'Fecha de próximo cambio no puede ser pasada.';
        }
    }

    if (!empty($observaciones) && (strlen($observaciones) > 500 || !preg_match('/^[a-zA-Z0-9\s.,!?\'-]+$/', $observaciones))) {
        $errors[] = 'Observaciones: máximo 500 caracteres, solo letras, números y puntuación básica.';
    }

    if (!empty($errors)) {
        error_log('Errores de validación: ' . implode('; ', $errors));
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    try {
        $con->beginTransaction();

        // Insertar mantenimiento
        $insert_mantenimiento = $con->prepare('
            INSERT INTO mantenimiento (placa, id_tipo_mantenimiento, fecha_programada, fecha_realizada, kilometraje_actual, proximo_cambio_km, proximo_cambio_fecha, observaciones, documento_usuario)
            VALUES (:placa, :id_tipo_mantenimiento, :fecha_programada, :fecha_realizada, :kilometraje_actual, :proximo_cambio_km, :proximo_cambio_fecha, :observaciones, :documento)
        ');
        $insert_mantenimiento->execute([
            ':placa' => $placa,
            ':id_tipo_mantenimiento' => $id_tipo_mantenimiento,
            ':fecha_programada' => $fecha_programada,
            ':fecha_realizada' => $fecha_realizada ?: null,
            ':kilometraje_actual' => $kilometraje_actual ? (int)$kilometraje_actual : null,
            ':proximo_cambio_km' => $proximo_cambio_km ? (int)$proximo_cambio_km : null,
            ':proximo_cambio_fecha' => $proximo_cambio_fecha ?: null,
            ':observaciones' => $observaciones ?: null,
            ':documento' => $documento
        ]);

        $con->commit();
        error_log('Mantenimiento registrado correctamente.');
        echo json_encode(['success' => true, 'message' => 'Mantenimiento registrado correctamente.']);
        exit;
    } catch (PDOException $e) {
        $con->rollBack();
        $error_msg = 'Error en la base de datos: ' . $e->getMessage();
        error_log($error_msg);
        echo json_encode(['success' => false, 'errors' => [$error_msg]]);
        exit;
    }
} else {
    error_log('Método de solicitud no válido.');
    echo json_encode(['success' => false, 'errors' => ['Método de solicitud no válido.']]);
    exit;
}
?>