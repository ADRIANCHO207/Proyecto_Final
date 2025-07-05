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

    if (empty($placa) || empty($documento) || empty($id_marca) || empty($modelo) || empty($kilometraje_actual) || empty($id_estado)) {
        error_log("Missing required fields in update_vehicle.php for placa: $placa");
        echo json_encode(['error' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Ruta donde se guardan las imágenes
    $upload_dir = '../../../roles/usuario/vehiculos/listar/guardar_foto_vehiculo/';
    $foto_vehiculo = null;

    // Si viene una imagen nueva
    if (isset($_FILES['foto_vehiculo']) && $_FILES['foto_vehiculo']['size'] > 0) {
        $extension = strtolower(pathinfo($_FILES['foto_vehiculo']['name'], PATHINFO_EXTENSION));
        $file_name = 'vehiculo_' . uniqid() . '.' . $extension;
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['foto_vehiculo']['tmp_name'], $file_path)) {
            $foto_vehiculo = $file_name;

            // Eliminar imagen anterior si existe
            $old_query = $con->prepare("SELECT foto_vehiculo FROM vehiculos WHERE placa = :placa");
            $old_query->bindParam(':placa', $placa, PDO::PARAM_STR);
            $old_query->execute();
            $old_image = $old_query->fetchColumn();

            $old_image_path = $upload_dir . $old_image;
            if ($old_image && file_exists($old_image_path) && $old_image !== 'sin_foto_carro.png') {
                if (!unlink($old_image_path)) {
                    error_log("No se pudo eliminar la imagen anterior: $old_image_path");
                }
            }
        } else {
            error_log("Fallo al subir la nueva imagen para la placa: $placa");
            echo json_encode(['error' => 'Error al subir la imagen']);
            exit;
        }
    }

    // Construir SQL dinámico si hay nueva imagen
    $sql = "UPDATE vehiculos SET Documento = :documento, id_marca = :id_marca, modelo = :modelo, kilometraje_actual = :kilometraje_actual, id_estado = :id_estado";
    if ($foto_vehiculo) {
        $sql .= ", foto_vehiculo = :foto_vehiculo";
    }
    $sql .= " WHERE placa = :placa";

    $query = $con->prepare($sql);
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
        error_log("Error al actualizar el vehículo con placa: $placa");
        echo json_encode(['error' => 'Error al actualizar el vehículo']);
    }
} catch (PDOException $e) {
    error_log("Excepción PDO en update_vehicle.php: " . $e->getMessage());
    echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>
