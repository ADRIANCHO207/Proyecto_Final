<?php
session_start();
require_once('../../../conecct/conex.php');
$db = new Database();
$con = $db->conectar();
include '../../../includes/validarsession.php';

header('Content-Type: application/json');

$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    echo json_encode(['status' => 'error', 'message' => 'No se encontró la sesión del usuario.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibir los datos del formulario
    $tipo_vehiculo = $_POST['tipo_vehiculo'] ?? '';
    $id_marca = $_POST['id_marca'] ?? '';
    $placa = $_POST['placa'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $kilometraje = $_POST['kilometraje'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $fecha = $_POST['fecha'] ?? '';

    // Validar campos vacíos
    if (empty($tipo_vehiculo) || empty($id_marca) || empty($placa) || empty($modelo) || empty($kilometraje) || empty($estado) || empty($fecha)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Validar existencia de placa duplicada
    $stmt = $con->prepare("SELECT * FROM vehiculos WHERE placa = ?");
    $stmt->execute([$placa]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'La placa ya está registrada.']);
        exit;
    }

    // Manejar la imagen del vehículo
    $nombreArchivo = null;
    if (isset($_FILES['foto_vehiculo']) && $_FILES['foto_vehiculo']['error'] === UPLOAD_ERR_OK) {
        $archivoTmp = $_FILES['foto_vehiculo']['tmp_name'];
        $nombreOriginal = basename($_FILES['foto_vehiculo']['name']);
        $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $permitidas)) {
            echo json_encode(['status' => 'error', 'message' => 'Formato de imagen no permitido. Solo JPG, JPEG y PNG.']);
            exit;
        }

        if ($_FILES['foto_vehiculo']['size'] > (2 * 1024 * 1024)) {
            echo json_encode(['status' => 'error', 'message' => 'La imagen supera el tamaño máximo de 2MB.']);
            exit;
        }

        $nombreArchivo = uniqid('vehiculo_') . '.' . $ext;

        // Ruta absoluta de la carpeta destino
        $carpetaDestino = $_SERVER['DOCUMENT_ROOT'] . '/PROYECTO/roles/usuario/vehiculos/listar/guardar_foto_vehiculo/';

        // Crear carpeta si no existe
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $rutaDestino = $carpetaDestino . $nombreArchivo;

        if (!move_uploaded_file($archivoTmp, $rutaDestino)) {
            echo json_encode(['status' => 'error', 'message' => 'Error al guardar la imagen.']);
            exit;
        }
    }

    // Validar formato de la placa según el tipo de vehículo
    $sql = $con-> prepare("SELECT * FROM tipo_vehiculo");
    $res = $sql->execute();

    $placa = strtoupper($placa); // Convertir a mayúsculas para uniformidad

    if ($tipo_vehiculo == 2 && !preg_match('/^[A-Z]{3}[0-9]{2}[A-Z]{1}$/', $placa)) {
        echo json_encode(['status' => 'error', 'message' => 'Para Motocicleta, la placa debe tener 4 letras y 2 números. Ej: ABC12D']);
        exit;
    }

    if ($tipo_vehiculo == 1 && $tipo_vehiculo <= 3 && !preg_match('/^[A-Z]{3}[0-9]{3}$/', $placa)) {
        echo json_encode(['status' => 'error', 'message' => 'Para los vehiculos diferente a Motocicleta , la placa debe tener 3 letras y 3 números. Ej: ABC123']);
        exit;
    }


    // Insertar datos en la tabla de vehículos
    $sql = "INSERT INTO vehiculos (tipo_vehiculo, id_marca, placa, modelo, kilometraje_actual, id_estado, fecha_registro, foto_vehiculo, Documento)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $resultado = $stmt->execute([
        $tipo_vehiculo,
        $id_marca,
        strtoupper($placa),
        $modelo,
        $kilometraje,
        $estado,
        $fecha,
        $nombreArchivo,
        $documento
    ]);

    if ($resultado) {
        echo json_encode(['status' => 'success', 'message' => 'Vehículo registrado exitosamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar el vehículo.']);
    }
}
?>
