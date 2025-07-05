<?php
session_start();
require_once('../../../conecct/conex.php');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Validar sesión ANTES del header JSON
if (!isset($_SESSION['documento'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Sesión no válida', 'redirect' => true]);
    exit;
}

$db = new Database();
$con = $db->conectar();

header('Content-Type: application/json');

if (!$con) {
    error_log("Failed to connect to database");
    echo json_encode(['error' => 'No se pudo conectar a la base de datos']);
    exit;
}

try {
    $placa = $_POST['placa'] ?? '';
    if (empty($placa)) {
        error_log("No placa provided in delete_vehicle.php");
        echo json_encode(['error' => 'Placa no proporcionada']);
        exit;
    }

    // Verificar dependencias ANTES de eliminar
    $hasDependencies = false;
    $checkQueries = [
        "SELECT COUNT(*) FROM mantenimiento WHERE placa = :placa",
        "SELECT COUNT(*) FROM llantas WHERE placa = :placa",
        "SELECT COUNT(*) FROM multas WHERE placa = :placa",
        "SELECT COUNT(*) FROM soat WHERE id_placa = :placa",
        "SELECT COUNT(*) FROM tecnomecanica WHERE id_placa = :placa"
    ];

    foreach ($checkQueries as $queryStr) {
        $table = explode(' FROM ', $queryStr)[1];
        $table = strtok($table, ' ');
        $query = $con->prepare($queryStr);
        $query->bindParam(':placa', $placa, PDO::PARAM_STR);
        $query->execute();
        $count = $query->fetchColumn();
        if ($count > 0) {
            echo json_encode(['error' => "No se puede eliminar el vehículo porque tiene registros asociados en $table"]);
            exit;
        }
    }

    // Si no hay dependencias, proceder con la eliminación
    // Obtener y eliminar imagen
    $image_query = $con->prepare("SELECT foto_vehiculo FROM vehiculos WHERE placa = :placa");
    $image_query->bindParam(':placa', $placa, PDO::PARAM_STR);
    $image_query->execute();
    $image = $image_query->fetchColumn();
    if ($image && file_exists('../../../roles/usuario/vehiculos/listar/guardar_foto_vehiculo/' . $image)) {
        if (!unlink('../../../roles/usuario/vehiculos/listar/guardar_foto_vehiculo/' . $image)) {
            error_log("Failed to delete image for placa $placa: $image");
        }
    }

    // Eliminar vehículo
    $query = $con->prepare("DELETE FROM vehiculos WHERE placa = :placa");
    $query->bindParam(':placa', $placa, PDO::PARAM_STR);

    if ($query->execute()) {
        echo json_encode(['success' => true, 'message' => 'Vehículo eliminado correctamente']);
    } else {
        error_log("Failed to delete vehicle with placa: $placa");
        echo json_encode(['error' => 'Error al eliminar el vehículo']);
    }
} catch (PDOException $e) {
    error_log("Database error in delete_vehicle.php: Query failed - " . $e->getMessage());
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>
