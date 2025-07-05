<?php
session_start();
require_once('../../conecct/conex.php');

// Establecer header JSON ANTES de cualquier validación
header('Content-Type: application/json');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['documento']) || !isset($_SESSION['rol'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Sesión no válida',
        'redirect' => true,
        'redirect_url' => '../../login/login.php'
    ]);
    exit;
}

$db = new Database();
$con = $db->conectar();
$response = ['success' => false, 'message' => 'No se ha especificado una placa'];

// Verificar que se haya enviado una placa
if (!isset($_POST['placa']) || empty($_POST['placa'])) {
    echo json_encode($response);
    exit;
}

$placa = $_POST['placa'];

try {
    // Consultar los datos del vehículo
    $sql = "SELECT v.*, m.nombre_marca, e.estado 
            FROM vehiculos v
            INNER JOIN marca m ON v.id_marca = m.id_marca
            INNER JOIN estado_vehiculo e ON v.id_estado = e.id_estado
            WHERE v.placa = :placa";
    
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':placa', $placa);
    $stmt->execute();
    
    $vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($vehiculo) {
        $response = [
            'success' => true,
            'vehiculo' => $vehiculo
        ];
    } else {
        $response['message'] = 'No se encontró el vehículo con la placa especificada';
    }
} catch (PDOException $e) {
    $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
}

// Devolver respuesta en formato JSON
echo json_encode($response);
