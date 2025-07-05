<?php
session_start();
require_once('../../../conecct/conex.php');

// Validación de sesión personalizada para respuestas JSON
if (!isset($_SESSION['documento'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Sesión no válida',
        'redirect' => true,
        'redirect_url' => '/Proyecto/login/login.php'
    ]);
    exit();
}

$db = new Database();
$con = $db->conectar();

header('Content-Type: application/json');

if (isset($_GET['documento'])) {
    $documento = $_GET['documento'];
    
    try {
        $query = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
        $query->bindParam(':documento', $documento, PDO::PARAM_STR);
        $query->execute();
        $usuario = $query->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            echo json_encode(['success' => true, 'data' => $usuario]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Documento no proporcionado']);
}
?>