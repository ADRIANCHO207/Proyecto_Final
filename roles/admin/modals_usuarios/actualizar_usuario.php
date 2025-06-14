<?php
require_once('../../../conecct/conex.php');

$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'];
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $estado = $_POST['estado'];
    $rol = $_POST['rol'];

    $query = $con->prepare("UPDATE usuarios SET nombre_completo = :nombre_completo, email = :email, telefono = :telefono, id_estado_usuario = :estado, id_rol = :rol WHERE documento = :documento");
    $query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $query->bindParam(':nombre_completo', $nombre_completo, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':telefono', $telefono, PDO::PARAM_STR);
    $query->bindParam(':estado', $estado, PDO::PARAM_INT);
    $query->bindParam(':rol', $rol, PDO::PARAM_INT);

    if ($query->execute()) {
        echo "Usuario actualizado exitosamente";
    } else {
        echo "Error al actualizar el usuario";
    }
}
?>