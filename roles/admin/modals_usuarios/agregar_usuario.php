<?php
require_once('../../../conecct/conex.php');

$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'];
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar contraseña
    $telefono = $_POST['telefono'];
    $estado = $_POST['estado'];
    $rol = $_POST['rol'];

    // Verificar si el documento ya existe
    $checkQuery = $con->prepare("SELECT COUNT(*) FROM usuarios WHERE documento = :documento");
    $checkQuery->bindParam(':documento', $documento, PDO::PARAM_STR);
    $checkQuery->execute();
    if ($checkQuery->fetchColumn() > 0) {
        echo "El documento ya está registrado";
        exit;
    }

    // Insertar nuevo usuario
    $query = $con->prepare("INSERT INTO usuarios (documento, nombre_completo, email, password, telefono, id_estado_usuario, id_rol) VALUES (:documento, :nombre_completo, :email, :password, :telefono, :estado, :rol)");
    $query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $query->bindParam(':nombre_completo', $nombre_completo, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':telefono', $telefono, PDO::PARAM_STR);
    $query->bindParam(':estado', $estado, PDO::PARAM_INT);
    $query->bindParam(':rol', $rol, PDO::PARAM_INT);

    if ($query->execute()) {
        echo "Usuario agregado exitosamente";
    } else {
        echo "Error al agregar el usuario";
    }
}
?>