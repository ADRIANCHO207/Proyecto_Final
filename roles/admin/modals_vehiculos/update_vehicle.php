<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging, suppress notices
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

try {
    error_log("update_vehicle.php started at " . date('Y-m-d H:i:s'));

    // Test database connection
    require_once('../../../conecct/conex.php');
    $db = new Database();
    $con = $db->conectar();
    if (!$con) {
        error_log("Failed to connect to database: " . print_r($db->errorInfo(), true));
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
        exit;
    }
    error_log("Database connection successful");

    if (!isset($_SESSION['documento'])) {
        error_log("No session documento");
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log("Received POST data: " . print_r($_POST, true));

        $placa = $_POST['placa'] ?? '';
        $marca = $_POST['marca'] ?? null;
        $modelo = $_POST['modelo'] ?? null;
        $estado = $_POST['estado'] ?? null; // Recibe id_estado (e.g., '1', '2')
        $kilometraje = $_POST['kilometraje'] ?? null;

        if (empty($placa)) {
            error_log("Missing required field placa: " . print_r($_POST, true));
            echo json_encode(['success' => false, 'message' => 'Falta el campo placa']);
            exit;
        }

        // Construir la consulta dinámicamente solo con los campos enviados
        $updates = [];
        $params = [':placa' => $placa];

        if ($marca !== null) {
            // Mapear nombre de marca a id_marca desde la tabla marca
            $marca_query = $con->prepare("SELECT id_marca FROM marca WHERE nombre_marca = :marca LIMIT 1");
            $marca_query->bindParam(':marca', $marca, PDO::PARAM_STR);
            $marca_query->execute();
            $id_marca = $marca_query->fetchColumn();
            if ($id_marca) {
                $updates[] = "id_marca = :id_marca";
                $params[':id_marca'] = $id_marca;
            } else {
                error_log("Marca no encontrada: $marca");
                echo json_encode(['success' => false, 'message' => 'Marca no válida']);
                exit;
            }
        }

        if ($modelo !== null) {
            $updates[] = "modelo = :modelo";
            $params[':modelo'] = $modelo;
        }

        if ($estado !== null) {
            // $estado ya es id_estado, no necesita mapeo adicional
            $updates[] = "id_estado = :id_estado";
            $params[':id_estado'] = $estado;
            // Verificar que el id_estado exista
            $estado_check = $con->prepare("SELECT id_estado FROM estado_vehiculo WHERE id_estado = :id_estado LIMIT 1");
            $estado_check->bindParam(':id_estado', $estado, PDO::PARAM_STR);
            $estado_check->execute();
            if (!$estado_check->fetchColumn()) {
                error_log("Estado no encontrado: $estado");
                echo json_encode(['success' => false, 'message' => 'Estado no válido']);
                exit;
            }
        }

        if ($kilometraje !== null) {
            $updates[] = "kilometraje_actual = :kilometraje";
            $params[':kilometraje'] = $kilometraje;
        }

        if (empty($updates)) {
            error_log("No fields to update for placa: $placa");
            echo json_encode(['success' => false, 'message' => 'No se enviaron campos para actualizar']);
            exit;
        }

        $sql = "UPDATE vehiculos SET " . implode(', ', $updates) . " WHERE placa = :placa";
        $query = $con->prepare($sql);

        foreach ($params as $key => $value) {
            $query->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        if ($query->execute()) {
            error_log("Vehicle updated for placa: $placa with fields: " . print_r($updates, true));
            echo json_encode(['success' => true]);
        } else {
            error_log("Failed to update vehicle for placa: $placa - Error: " . print_r($con->errorInfo(), true));
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