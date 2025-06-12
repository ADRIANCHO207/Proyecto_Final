<?php
session_start();
require_once('../../conecct/conex.php');
include '../../includes/validarsession.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['documento'])) {
    header('Location: ../../login.php');
    exit;
}

$db = new Database();
$con = $db->conectar();
$response = ['success' => false, 'message' => 'No se ha realizado ninguna acción'];

// Verificar que se haya enviado una acción
if (!isset($_POST['accion'])) {
    $response['message'] = 'Acción no especificada';
    echo json_encode($response);
    exit;
}

$accion = $_POST['accion'];

// Función para procesar y guardar la imagen
function procesarImagen($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Validar tipo de archivo
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($file['type'], $allowed_types)) {
        return null;
    }

    // Validar tamaño (2MB máximo)
    if ($file['size'] > 2 * 1024 * 1024) {
        return null;
    }

    // Crear directorio si no existe
    $upload_dir = '../usuario/uploads/vehiculos/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Generar nombre único para el archivo
    $filename = uniqid() . '_' . basename($file['name']);
    $upload_path = $upload_dir . $filename;

    // Mover el archivo
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return 'uploads/vehiculos/' . $filename;
    }

    return null;
}

// Agregar un nuevo vehículo
if ($accion === 'agregar') {
    try {
        // Validar campos requeridos
        $campos_requeridos = ['placa', 'documento', 'id_marca', 'modelo', 'color', 'kilometraje_actual', 'id_estado', 'tipo_vehiculo'];
        foreach ($campos_requeridos as $campo) {
            if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                $response['message'] = 'El campo ' . $campo . ' es requerido';
                echo json_encode($response);
                exit;
            }
        }

        // Verificar si la placa ya existe
        $check = $con->prepare("SELECT COUNT(*) FROM vehiculos WHERE placa = :placa");
        $check->bindParam(':placa', $_POST['placa']);
        $check->execute();
        if ($check->fetchColumn() > 0) {
            $response['message'] = 'La placa ya está registrada en el sistema';
            echo json_encode($response);
            exit;
        }

        // Procesar imagen si se ha subido
        $foto_vehiculo = null;
        if (isset($_FILES['foto_vehiculo']) && $_FILES['foto_vehiculo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $foto_vehiculo = procesarImagen($_FILES['foto_vehiculo']);
            if ($foto_vehiculo === null) {
                $response['message'] = 'Error al procesar la imagen. Verifique el formato y tamaño';
                echo json_encode($response);
                exit;
            }
        }

        // Preparar la consulta SQL
        $sql = "INSERT INTO vehiculos (placa, documento, id_marca, modelo, color, kilometraje_actual, id_estado, tipo_vehiculo, observaciones, foto_vehiculo, fecha_registro) 
                VALUES (:placa, :documento, :id_marca, :modelo, :color, :kilometraje_actual, :id_estado, :tipo_vehiculo, :observaciones, :foto_vehiculo, NOW())";
        
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':placa', $_POST['placa']);
        $stmt->bindParam(':documento', $_POST['documento']);
        $stmt->bindParam(':id_marca', $_POST['id_marca']);
        $stmt->bindParam(':modelo', $_POST['modelo']);
        $stmt->bindParam(':color', $_POST['color']);
        $stmt->bindParam(':kilometraje_actual', $_POST['kilometraje_actual']);
        $stmt->bindParam(':id_estado', $_POST['id_estado']);
        $stmt->bindParam(':tipo_vehiculo', $_POST['tipo_vehiculo']);
        $stmt->bindParam(':observaciones', $_POST['observaciones']);
        $stmt->bindParam(':foto_vehiculo', $foto_vehiculo);
        
        if ($stmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'Vehículo registrado correctamente',
                'redirect' => 'index.php'
            ];
        } else {
            $response['message'] = 'Error al registrar el vehículo';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
    }
}

// Editar un vehículo existente
elseif ($accion === 'editar') {
    try {
        // Validar campos requeridos
        if (!isset($_POST['id_vehiculo']) || empty($_POST['id_vehiculo'])) {
            $response['message'] = 'ID de vehículo no especificado';
            echo json_encode($response);
            exit;
        }

        $campos_requeridos = ['documento', 'id_marca', 'modelo', 'color', 'kilometraje_actual', 'id_estado', 'tipo_vehiculo'];
        foreach ($campos_requeridos as $campo) {
            if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                $response['message'] = 'El campo ' . $campo . ' es requerido';
                echo json_encode($response);
                exit;
            }
        }

        // Verificar si el vehículo existe
        $check = $con->prepare("SELECT foto_vehiculo FROM vehiculos WHERE id_vehiculo = :id_vehiculo");
        $check->bindParam(':id_vehiculo', $_POST['id_vehiculo']);
        $check->execute();
        $vehiculo = $check->fetch(PDO::FETCH_ASSOC);
        
        if (!$vehiculo) {
            $response['message'] = 'El vehículo no existe';
            echo json_encode($response);
            exit;
        }

        // Procesar imagen si se ha subido una nueva
        $foto_vehiculo = $vehiculo['foto_vehiculo']; // Mantener la foto actual por defecto
        
        // Si no se marca "mantener foto" y se sube una nueva
        if (!isset($_POST['mantener_foto']) && isset($_FILES['foto_vehiculo']) && $_FILES['foto_vehiculo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $nueva_foto = procesarImagen($_FILES['foto_vehiculo']);
            if ($nueva_foto === null) {
                $response['message'] = 'Error al procesar la imagen. Verifique el formato y tamaño';
                echo json_encode($response);
                exit;
            }
            $foto_vehiculo = $nueva_foto;
        }

        // Preparar la consulta SQL
        $sql = "UPDATE vehiculos SET 
                documento = :documento,
                id_marca = :id_marca,
                modelo = :modelo,
                color = :color,
                kilometraje_actual = :kilometraje_actual,
                id_estado = :id_estado,
                tipo_vehiculo = :tipo_vehiculo,
                observaciones = :observaciones,
                foto_vehiculo = :foto_vehiculo,
                fecha_actualizacion = NOW()
                WHERE id_vehiculo = :id_vehiculo";
        
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id_vehiculo', $_POST['id_vehiculo']);
        $stmt->bindParam(':documento', $_POST['documento']);
        $stmt->bindParam(':id_marca', $_POST['id_marca']);
        $stmt->bindParam(':modelo', $_POST['modelo']);
        $stmt->bindParam(':color', $_POST['color']);
        $stmt->bindParam(':kilometraje_actual', $_POST['kilometraje_actual']);
        $stmt->bindParam(':id_estado', $_POST['id_estado']);
        $stmt->bindParam(':tipo_vehiculo', $_POST['tipo_vehiculo']);
        $stmt->bindParam(':observaciones', $_POST['observaciones']);
        $stmt->bindParam(':foto_vehiculo', $foto_vehiculo);
        
        if ($stmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'Vehículo actualizado correctamente',
                'redirect' => 'index.php'
            ];
        } else {
            $response['message'] = 'Error al actualizar el vehículo';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
    }
}

// Eliminar un vehículo
elseif ($accion === 'eliminar') {
    try {
        if (!isset($_POST['id_vehiculo']) || empty($_POST['id_vehiculo'])) {
            $response['message'] = 'ID de vehículo no especificado';
            echo json_encode($response);
            exit;
        }

        $stmt = $con->prepare("DELETE FROM vehiculos WHERE id_vehiculo = :id_vehiculo");
        $stmt->bindParam(':id_vehiculo', $_POST['id_vehiculo']);
        
        if ($stmt->execute()) {
            $response = [
                'success' => true,
                'message' => 'Vehículo eliminado correctamente'
            ];
        } else {
            $response['message'] = 'Error al eliminar el vehículo';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
    }
}

// Devolver respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
