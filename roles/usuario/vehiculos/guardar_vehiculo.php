<?php
session_start();
require_once '../../../conecct/conex.php';
include '../../../includes/validarsession.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Instantiate the Database class and get the PDO connection
$database = new Database();
$conn = $database->conectar();

// Check if the connection is successful
if (!$conn) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $placa = $_POST['placa'] ?? '';
    $documento = $_POST['documento'] ?? '';
    $id_marca = $_POST['id_marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $kilometraje = $_POST['kilometraje'] ?? '';
    $id_estado = $_POST['estado'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $foto_vehiculo = null;

    // Log form submission
    error_log("Form submitted: placa=$placa, documento=$documento, id_marca=$id_marca, modelo=$modelo");

    // Debug: Log entire $_FILES array
    error_log("FILES array: " . print_r($_FILES, true));

    // Check if file input exists and was uploaded
    if (isset($_FILES['foto_vehiculo']) && $_FILES['foto_vehiculo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file_error = $_FILES['foto_vehiculo']['error'];
        $file_tmp = $_FILES['foto_vehiculo']['tmp_name'];
        $file_name = $_FILES['foto_vehiculo']['name'];
        $file_size = $_FILES['foto_vehiculo']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        // Log file details
        error_log("File upload attempt: name=$file_name, size=$file_size, error=$file_error, tmp_name=$file_tmp, ext=$file_ext");

        // Check for upload errors
        if ($file_error !== UPLOAD_ERR_OK) {
            $upload_errors = [
                UPLOAD_ERR_INI_SIZE => "El archivo excede el tamaño máximo permitido por el servidor.",
                UPLOAD_ERR_FORM_SIZE => "El archivo excede el tamaño máximo permitido por el formulario.",
                UPLOAD_ERR_PARTIAL => "El archivo se subió parcialmente.",
                UPLOAD_ERR_NO_TMP_DIR => "Falta un directorio temporal.",
                UPLOAD_ERR_CANT_WRITE => "No se pudo escribir el archivo en el disco.",
                UPLOAD_ERR_EXTENSION => "Una extensión de PHP detuvo la subida del archivo."
            ];
            $_SESSION['error'] = $upload_errors[$file_error] ?? "Error desconocido al subir el archivo (código: $file_error).";
            error_log("Upload error: " . $_SESSION['error']);
            header('Location: formulario.php');
            exit;
        }

        // Check if temporary file exists
        if (!file_exists($file_tmp) || !is_uploaded_file($file_tmp)) {
            $_SESSION['error'] = "El archivo temporal no existe o no es un archivo subido válido.";
            error_log("Invalid temporary file: $file_tmp");
            header('Location: formulario.php');
            exit;
        }

        // Validate file extension
        if (!in_array($file_ext, $allowed_exts)) {
            $_SESSION['error'] = "Formato de imagen no permitido. Use JPG, JPEG, PNG o GIF.";
            error_log("Invalid file extension: $file_ext");
            header('Location: formulario.php');
            exit;
        }

        // Generate unique file name
        $new_file_name = uniqid('vehiculo_') . '.' . $file_ext;
        $upload_dir = 'vehiculos/listar/guardar_foto_vehiculo/';
        $upload_path = $upload_dir . $new_file_name;

        // Resolve absolute path for logging
        $absolute_upload_path = realpath(__DIR__ . '/../../../') . '/' . $upload_dir . $new_file_name;
        error_log("Attempting to save file to: $absolute_upload_path");

        // Ensure upload directory exists
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                $_SESSION['error'] = "No se pudo crear el directorio de subida: $upload_dir";
                error_log("Failed to create directory: $upload_dir");
                header('Location: formulario.php');
                exit;
            }
            error_log("Created directory: $upload_dir");
        }

        // Check directory permissions
        if (!is_writable($upload_dir)) {
            $_SESSION['error'] = "El directorio de subida no tiene permisos de escritura: $upload_dir";
            error_log("Directory not writable: $upload_dir");
            header('Location: formulario.php');
            exit;
        }

        // Move the uploaded file
        if (move_uploaded_file($file_tmp, $upload_path)) {
            $foto_vehiculo = 'vehiculos/listar/guardar_foto_vehiculo/' . $new_file_name;
            error_log("File successfully uploaded to: $absolute_upload_path, stored as: $foto_vehiculo");
        } else {
            $_SESSION['error'] = "Error al mover la imagen al servidor.";
            error_log("Failed to move file from $file_tmp to $absolute_upload_path");
            header('Location: formulario.php');
            exit;
        }
    } else {
        error_log("No file uploaded or file input was empty for foto_vehiculo.");
        $_SESSION['error'] = "No se seleccionó ninguna imagen.";
    }

    // If no image was uploaded or upload failed, use the default image
    if (!$foto_vehiculo) {
        $foto_vehiculo = 'vehiculos/listar/guardar_foto_vehiculo/sin_foto_carro.png';
        error_log("Using default image: $foto_vehiculo");
    }

    // Prepare the SQL query
    $query = "INSERT INTO vehiculos (placa, Documento, id_marca, modelo, kilometraje_actual, id_estado, fecha_registro, foto_vehiculo) 
              VALUES (:placa, :documento, :id_marca, :modelo, :kilometraje, :id_estado, :fecha, :foto_vehiculo)";
    $stmt = $conn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':placa', $placa, PDO::PARAM_STR);
    $stmt->bindParam(':documento', $documento, PDO::PARAM_STR);
    $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_STR);
    $stmt->bindParam(':modelo', $modelo, PDO::PARAM_STR);
    $stmt->bindParam(':kilometraje', $kilometraje, PDO::PARAM_INT);
    $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_STR);
    $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $stmt->bindParam(':foto_vehiculo', $foto_vehiculo, PDO::PARAM_STR);

    // Execute the query
    try {
        $stmt->execute();
        $_SESSION['mensaje'] = "Vehículo registrado exitosamente.";
        error_log("Vehicle registered successfully: placa=$placa, foto_vehiculo=$foto_vehiculo");
        header('Location: formulario.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al registrar el vehículo: " . $e->getMessage();
        error_log("Database error: " . $e->getMessage());
        header('Location: formulario.php');
        exit;
    }
} else {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    header('Location: formulario.php');
    exit;
}
?>