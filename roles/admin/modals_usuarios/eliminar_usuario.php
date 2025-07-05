<?php
session_start();
require_once('../../../conecct/conex.php');
require_once('../../../includes/validarsession.php');

$db = new Database();
$con = $db->conectar();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['documento'])) {
    $documento = $_POST['documento'];

    try {
        // Verificar que el usuario existe
        $checkQuery = $con->prepare("SELECT COUNT(*) FROM usuarios WHERE documento = :documento");
        $checkQuery->bindParam(':documento', $documento, PDO::PARAM_STR);
        $checkQuery->execute();
        
        if ($checkQuery->fetchColumn() == 0) {
            echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
            exit;
        }

        // Eliminar el usuario
        $query = $con->prepare("DELETE FROM usuarios WHERE documento = :documento");
        $query->bindParam(':documento', $documento, PDO::PARAM_STR);

        if ($query->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar el usuario']);
        }
    } catch (PDOException $e) {
        error_log("Database error in eliminar_usuario.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Error en la base de datos']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Documento no proporcionado']);
}
?>