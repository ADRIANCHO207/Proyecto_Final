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
    $dia = $_POST['dia'] ?? '';

    // Validate required fields
    if (empty($placa) || empty($dia)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Insert into pico_placa
    $query = "INSERT INTO pico_placa (placa, dia) VALUES (:placa, :dia)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':placa', $placa);
    $stmt->bindParam(':dia', $dia);

    if ($stmt->execute()) {
        header("Location: gestionar_pico_placa.php?success=true");
        exit;
    } else {
        die("Error: No se pudo guardar el dato de Pico y Placa.");
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>