<?php
require_once('../../../conecct/conex.php');

$db = new Database();
$con = $db->conectar();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['documento'])) {
    $documento = $_POST['documento'];

    try {
        // Iniciar transacción
        $con->beginTransaction();

        // Eliminar el usuario
        $query = $con->prepare("DELETE FROM usuarios WHERE documento = :documento");
        $query->bindParam(':documento', $documento, PDO::PARAM_STR);

        if ($query->execute()) {
            // Confirmar transacción
            $con->commit();
            echo json_encode(['success' => true, 'message' => 'Usuario y sus vehículos eliminados exitosamente']);
        } else {
            // Revertir transacción en caso de error
            $con->rollBack();
            echo json_encode(['error' => 'Error al eliminar el usuario']);
        }
    } catch (PDOException $e) {
        // Revertir transacción en caso de excepción
        $con->rollBack();
        error_log("Database error in eliminar_usuario.php: " . $e->getMessage());
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Documento no proporcionado']);
}
?>