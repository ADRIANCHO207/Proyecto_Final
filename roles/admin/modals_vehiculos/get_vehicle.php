<?php
session_start();
require_once('../../conecct/conex.php');
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['placa'])) {
    $placa = $_POST['placa'];
    $query = $con->prepare("SELECT placa, documento, id_marca, modelo, id_estado, kilometraje_actual FROM vehiculos WHERE placa = :placa");
    $query->bindParam(':placa', $placa, PDO::PARAM_STR);
    $query->execute();
    $vehicle = $query->fetch(PDO::FETCH_ASSOC);
    if ($vehicle) {
        echo "success: " . implode('|', $vehicle);
    } else {
        echo "error: Vehículo no encontrado";
    }
} else {
    echo "error: Solicitud inválida";
}
?>  