<?php
session_start();
require_once('../conecct/conex.php');
// include 'validarsesion.php';
$db = new Database();
$con = $db->conectar();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doc = $_POST['doc'] ?? '';
    $passw = $_POST['passw'] ?? '';

    if (empty($doc) || empty($passw)) {
        echo json_encode(['status' => 'error', 'message' => 'Error :Todos los campos son obligatorios.']);
        exit;
    }

    $sql = $con->prepare("SELECT * FROM usuarios WHERE documento = ?");
    $sql->execute([$doc]);
    $fila = $sql->fetch();

    if (!$fila) {
        echo json_encode(['status' => 'error', 'message' => 'Error: Documento no encontrado']);
        exit;
    }

    if (!password_verify($passw, $fila['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Error: Contraseña incorrecta']);
        exit;
    }

    if ($fila['id_estado_usuario'] != 1) {
        echo json_encode(['status' => 'error', 'message' => 'Error: Usuario inactivo']);
        exit;
    }

    $_SESSION['documento'] = $fila['documento'];
    $_SESSION['tipo'] = $fila['id_rol'];

    echo json_encode([
        'status' => 'success',
        'rol' => $fila['id_rol'] == 1 ? 'admin' : 'usuario'
    ]);
}else {
    echo json_encode(['status' => 'error', 'message' => 'Petición no válida']);
    exit;
}
?>
