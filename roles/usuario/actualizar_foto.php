<?php
session_start();
require_once('../../conecct/conex.php');
$db = new Database();
$con = $db->conectar();

// Check for documento in session
$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    $_SESSION['error'] = "Por favor, inicia sesión para continuar.";
    header('Location: ../../../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    $file = $_FILES['foto_perfil'];
    // Use absolute path for reliability, save directly to css/img/
    $upload_dir = __DIR__ . '/css/img/';
    $relative_dir = 'css/img/';
    
    // Ensure directory exists and is writable
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            $_SESSION['error'] = "No se pudo crear el directorio de carga.";
            header('Location: index.php');
            exit;
        }
    }
    if (!is_writable($upload_dir)) {
        $_SESSION['error'] = "El directorio de carga no tiene permisos de escritura.";
        header('Location: index.php');
        exit;
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => "El archivo excede el tamaño máximo permitido por el servidor (5MB).",
            UPLOAD_ERR_FORM_SIZE => "El archivo excede el tamaño máximo del formulario.",
            UPLOAD_ERR_PARTIAL => "El archivo se cargó parcialmente.",
            UPLOAD_ERR_NO_FILE => "No se seleccionó ningún archivo.",
            UPLOAD_ERR_NO_TMP_DIR => "Falta la carpeta temporal del servidor.",
            UPLOAD_ERR_CANT_WRITE => "No se pudo escribir el archivo en el disco.",
            UPLOAD_ERR_EXTENSION => "Una extensión PHP detuvo la carga."
        ];
        $_SESSION['error'] = $errors[$file['error']] ?? "Error desconocido al cargar el archivo.";
        header('Location: index.php');
        exit;
    }

    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['error'] = "Solo se permiten archivos JPEG, PNG o GIF.";
        header('Location: index.php');
        exit;
    }

    if ($file['size'] > $max_size) {
        $_SESSION['error'] = "El archivo no debe superar los 5MB.";
        header('Location: index.php');
        exit;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = $documento . '_' . time() . '.' . $ext;
    $destination = $upload_dir . $filename;
    $relative_path = $relative_dir . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        // Verify file exists
        if (!file_exists($destination)) {
            $_SESSION['error'] = "El archivo se movió pero no se encuentra en la ubicación esperada.";
            header('Location: index.php');
            exit;
        }

        // Update database
        $query = $con->prepare("UPDATE usuarios SET foto_perfil = :foto_perfil WHERE documento = :documento");
        $query->bindParam(':foto_perfil', $relative_path, PDO::PARAM_STR);
        $query->bindParam(':documento', $documento, PDO::PARAM_STR);
        if ($query->execute()) {
            // Add cache-busting parameter
            $_SESSION['foto_perfil'] = $relative_path . '?v=' . time();
            $_SESSION['success'] = "Foto de perfil actualizada correctamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar la foto en la base de datos.";
        }
    } else {
        $_SESSION['error'] = "Error al mover el archivo. Verifica los permisos del directorio o el espacio en disco.";
    }
} else {
    $_SESSION['error'] = "No se recibió ningún archivo válido.";
}

header('Location: index.php');
exit;
?>