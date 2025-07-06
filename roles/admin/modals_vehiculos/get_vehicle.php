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

$placa = $_GET['placa'] ?? '';
if (!$placa) {
    error_log("No placa provided in get_vehicle.php");
    echo json_encode(['error' => 'Placa no proporcionada']);
    exit;
}

try {
    $query = $con->prepare("SELECT * FROM vehiculos WHERE placa = :placa");
    $query->bindParam(':placa', $placa, PDO::PARAM_STR);
    $query->execute();
    $vehicle = $query->fetch(PDO::FETCH_ASSOC);

    if ($vehicle) {
        echo json_encode($vehicle);
    } else {
        error_log("Vehicle not found for placa: $placa");
        echo json_encode(['error' => 'Vehículo no encontrado']);
    }
} catch (PDOException $e) {
    error_log("Database error in get_vehicle.php: " . $e->getMessage());
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>