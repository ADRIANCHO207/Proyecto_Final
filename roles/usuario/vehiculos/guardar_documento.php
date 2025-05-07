<?php
session_start();
require_once '../../../conecct/conex.php';
include '../../../includes/validarsession.php';

// Instantiate the Database class and get the PDO connection
$database = new Database();
$conn = $database->conectar();

// Check if the connection is successful
if (!$conn) {
    die("Error: No se pudo conectar a la base de datos. Verifique el archivo conex.php.");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $placa = $_POST['placa'] ?? '';
    $empresa_tramite = $_POST['empresa_tramite'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? '';
    $tipo_documento = $_POST['tipo_documento'] ?? '';

    // Validate required fields
    if (empty($placa) || empty($empresa_tramite) || empty($fecha_inicio) || empty($fecha_vencimiento) || empty($tipo_documento)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Generate a unique id_documento (e.g., using timestamp and random number)
    $id_documento = time() . '-' . rand(1000, 9999);

    // Insert into documentacion
    $query = "INSERT INTO documentacion (id_documento, placa, id_tipo_documento, Empresa_Tramtie, fecha_inicio, fecha_vencimiento) 
              VALUES (:id_documento, :placa, :tipo_documento, :empresa_tramite, :fecha_inicio, :fecha_vencimiento)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_documento', $id_documento);
    $stmt->bindParam(':placa', $placa);
    $stmt->bindParam(':tipo_documento', $tipo_documento);
    $stmt->bindParam(':empresa_tramite', $empresa_tramite);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_vencimiento', $fecha_vencimiento);

    if ($stmt->execute()) {
        // Redirect back to the form with success parameter
        header("Location: gestionar_documento.php?tipo=" . urlencode($tipo) . "&success=true");
        exit;
    } else {
        die("Error: No se pudo guardar la documentación.");
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>