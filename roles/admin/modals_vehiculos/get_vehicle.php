<?php
session_start();
require_once('../../../conecct/conex.php');
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['placa'])) {
    $placa = $_POST['placa'];
    error_log("Fetching vehicle with placa: $placa");
    $query = $con->prepare("SELECT placa, documento, id_marca, modelo, id_estado, kilometraje_actual FROM vehiculos WHERE placa = :placa");
    $query->bindParam(':placa', $placa, PDO::PARAM_STR);
    $query->execute();
    $vehicle = $query->fetch(PDO::FETCH_ASSOC);
    if ($vehicle) {
        error_log("Vehicle found: " . print_r($vehicle, true));
        echo "success: " . implode('|', $vehicle);
    } else {
        error_log("Vehicle not found for placa: $placa");
        echo "error: Vehículo no encontrado";
    }
} else {
    error_log("Invalid request or missing placa: " . print_r($_POST, true));
    echo "error: Solicitud inválida";
}
?>