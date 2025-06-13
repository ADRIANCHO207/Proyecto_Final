<?php
session_start();
header('Content-Type: application/json');

try {
    error_log("update_vehicle.php started");
    require_once('../../conecct/conex.php');
    $db = new Database();
    $con = $db->conectar();

    if (!isset($_SESSION['documento'])) {
        error_log("No session documento");
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $placa = $_POST['placa'] ?? '';
        $documento = $_POST['documento'] ?? '';
        $id_marca = $_POST['marca'] ?? ''; // Cambiado a string
        $modelo = $_POST['modelo'] ?? '';
        $id_estado = $_POST['estado'] ?? ''; // Cambiado a string
        $kilometraje = $_POST['kilometraje'] ?? '';
        error_log("Updating vehicle with placa: $placa");
        error_log("POST data: " . print_r($_POST, true));
        
        $query = $con->prepare("UPDATE vehiculos SET documento = :documento, id_marca = :id_marca, 
                               modelo = :modelo, id_estado = :id_estado, kilometraje_actual = :kilometraje 
                               WHERE placa = :placa");
        $query->bindParam(':placa', $placa, PDO::PARAM_STR);
        $query->bindParam(':documento', $documento, PDO::PARAM_STR);
        $query->bindParam(':id_marca', $id_marca, PDO::PARAM_STR); // Cambiado a STR
        $query->bindParam(':modelo', $modelo, PDO::PARAM_STR);
        $query->bindParam(':id_estado', $id_estado, PDO::PARAM_STR); // Cambiado a STR
        $query->bindParam(':kilometraje', $kilometraje, PDO::PARAM_INT);
        
        if ($query->execute()) {
            error_log("Vehicle updated for placa: $placa");
            echo json_encode(['success' => true]);
        } else {
            error_log("Failed to update vehicle for placa: $placa");
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el vehículo']);
        }
    } else {
        error_log("Invalid request: " . print_r($_POST, true));
        echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
    }
} catch (Exception $e) {
    error_log("Exception in update_vehicle.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
}
?>