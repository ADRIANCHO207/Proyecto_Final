<?php
session_start();
require_once('../../../conecct/conex.php');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

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

    // Eliminar registros relacionados primero
    $tablasRelacionadas = [
        'correos_enviados_pico_placa',
        'mantenimiento',
        'llantas',
        'multas',
        'soat',
        'tecnomecanica'
    ];

    foreach ($tablasRelacionadas as $tabla) {
        $campo = ($tabla == 'soat' || $tabla == 'tecnomecanica') ? 'id_placa' : 'placa';
        $sqlDelete = "DELETE FROM $tabla WHERE $campo = :placa";
        $stmtDelete = $con->prepare($sqlDelete);
        $stmtDelete->bindParam(':placa', $placa, PDO::PARAM_STR);
        $stmtDelete->execute();
    }

    // Check for dependencies with explicit queries
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
        error_log("Executing query: $queryStr for placa: $placa");
        $query = $con->prepare($queryStr);
        $query->bindParam(':placa', $placa, PDO::PARAM_STR);
        $query->execute();
        $count = $query->fetchColumn();
        error_log("Result for $table: $count records");
        if ($count > 0) {
            error_log("Cannot delete vehicle with placa $placa due to dependencies in $table");
            echo json_encode(['error' => "No se puede eliminar el vehículo porque tiene registros asociados en $table"]);
            $hasDependencies = true;
            break;
        }
    }

    if ($hasDependencies) {
        exit;
    }

    // Get and delete image
    $image_query = $con->prepare("SELECT foto_vehiculo FROM vehiculos WHERE placa = :placa");
    $image_query->bindParam(':placa', $placa, PDO::PARAM_STR);
    $image_query->execute();
    $image = $image_query->fetchColumn();
    if ($image && file_exists('../' . $image)) { // Adjusted path to match your schema
        if (!unlink('../' . $image)) {
            error_log("Failed to delete image for placa $placa: ../$image");
        }
    }

    // Delete vehicle
    $query = $con->prepare("DELETE FROM vehiculos WHERE placa = :placa");
    $query->bindParam(':placa', $placa, PDO::PARAM_STR);

    if ($query->execute()) {
        echo json_encode(['success' => true]);
    } else {
        error_log("Failed to delete vehicle with placa: $placa");
        echo json_encode(['error' => 'Error al eliminar el vehículo']);
    }
} catch (PDOException $e) {
    error_log("Database error in delete_vehicle.php: Query failed - " . $e->getMessage());
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>
