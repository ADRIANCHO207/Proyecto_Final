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
    $documento = $_POST['documento'] ?? '';
    $id_marca = $_POST['id_marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $kilometraje_actual = $_POST['kilometraje_actual'] ?? '';
    $id_estado = $_POST['id_estado'] ?? '';

    // Validate inputs
    if (empty($placa) || empty($documento) || empty($id_marca) || empty($modelo) || empty($kilometraje_actual) || empty($id_estado)) {
        error_log("Missing required fields in update_vehicle.php for placa: $placa");
        echo json_encode(['error' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Handle image upload
    $foto_vehiculo = null;
    if (isset($_FILES['foto_vehiculo']) && $_FILES['foto_vehiculo']['size'] > 0) {
        $upload_dir = 'images/';
        $file_name = $placa . '_' . time() . '.' . pathinfo($_FILES['foto_vehiculo']['name'], PATHINFO_EXTENSION);
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['foto_vehiculo']['tmp_name'], $file_path)) {
            $foto_vehiculo = $file_name;
            // Delete old image if it exists
            $old_query = $con->prepare("SELECT foto_vehiculo FROM vehiculos WHERE placa = :placa");
            $old_query->bindParam(':placa', $placa, PDO::PARAM_STR);
            $old_query->execute();
            $old_image = $old_query->fetchColumn();
            if ($old_image && file_exists($upload_dir . $old_image)) {
                if (!unlink($upload_dir . $old_image)) {
                    error_log("Failed to delete old image for placa $placa: $upload_dir$old_image");
                }
            }
        } else {
            error_log("Failed to upload image for placa: $placa");
            echo json_encode(['error' => 'Error al subir la imagen']);
            exit;
        }
    }

    // Update vehicle
    $query = $con->prepare("UPDATE vehiculos SET Documento = :documento, id_marca = :id_marca, modelo = :modelo, kilometraje_actual = :kilometraje_actual, id_estado = :id_estado" . ($foto_vehiculo ? ", foto_vehiculo = :foto_vehiculo" : "") . " WHERE placa = :placa");
    $query->bindParam(':placa', $placa, PDO::PARAM_STR);
    $query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $query->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
    $query->bindParam(':modelo', $modelo, PDO::PARAM_STR);
    $query->bindParam(':kilometraje_actual', $kilometraje_actual, PDO::PARAM_INT);
    $query->bindParam(':id_estado', $id_estado, PDO::PARAM_STR);
    if ($foto_vehiculo) {
        $query->bindParam(':foto_vehiculo', $foto_vehiculo, PDO::PARAM_STR);
    }

    if ($query->execute()) {
        echo json_encode(['success' => true]);
    } else {
        error_log("Failed to update vehicle with placa: $placa");
        echo json_encode(['error' => 'Error al actualizar el vehículo']);
    }
} catch (PDOException $e) {
    error_log("Database error in update_vehicle.php: " . $e->getMessage());
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>