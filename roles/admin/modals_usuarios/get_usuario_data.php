<?php
require_once('../../../conecct/conex.php');

$db = new Database();
$con = $db->conectar();

header('Content-Type: application/json');

if (isset($_GET['documento'])) {
    $documento = $_GET['documento'];
    $query = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
    $query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $query->execute();
    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        echo json_encode(['success' => true, 'data' => $usuario]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Documento no proporcionado']);
}
?>