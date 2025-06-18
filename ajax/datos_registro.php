<?php
require_once('../conecct/conex.php');
$db = new Database();
$con = $db->conectar();
session_start();
$estado = 1;
$rol = 2;

header('Content-Type: application/json');

$response = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $doc = $_POST['doc'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $cont = $_POST['con'] ?? '';
    $con2 = $_POST['con2'] ?? '';
    $cel = $_POST['cel'] ?? '';

    if (empty($doc) || empty($nom) || empty($correo) || empty($cont) || empty($con2) || empty($cel)) {
        echo json_encode(['status' => 'error', 'message' => 'Campos vacíos']);
        exit;
    }

    if ($cont !== $con2) {
        echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden']);
        exit;
    }

    $cont_enc = password_hash($cont, PASSWORD_DEFAULT, array("cost" => 12));

    // Validar documento
    $sql1 = $con->prepare("SELECT * FROM usuarios WHERE documento = ?");
    $sql1->execute([$doc]);
    if ($sql1->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Documento ya registrado']);
        exit;
    }

    // Validar nombre
    $sql3 = $con->prepare("SELECT * FROM usuarios WHERE nombre_completo = ?");
    $sql3->execute([$nom]);
    if ($sql3->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Nombre ya registrado']);
        exit;
    }

    // Validar correo
    $sql2 = $con->prepare("SELECT * FROM usuarios WHERE email = ?");
    $sql2->execute([$correo]);
    if ($sql2->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Correo ya registrado']);
        exit;
    }

    // Validar celular
    $sql4 = $con->prepare("SELECT * FROM usuarios WHERE telefono = ?");
    $sql4->execute([$cel]);
    if ($sql4->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Celular ya registrado']);
        exit;
    }

    // Insertar nuevo usuario
    $inserto = $con->prepare("INSERT INTO usuarios(documento, nombre_completo, email, password, telefono, id_estado_usuario, id_rol) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($inserto->execute([$doc, $nom, $correo, $cont_enc, $cel, $estado, $rol])) {
        echo json_encode(['status' => 'success', 'message' => 'Registro exitoso']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar']);
    }
}
?>