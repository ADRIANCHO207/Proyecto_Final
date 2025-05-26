<?php

session_start();
require_once('../conecct/conex.php');
// include 'validarsesion.php';
$db = new Database();
$con = $db->conectar();

?>
<?php
if (isset($_POST['log'])) {

    $doc = $_POST['doc'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $passw = $_POST['passw'] ?? '';



    $sql = $con->prepare("SELECT * FROM usuarios WHERE documento = ?");
    $sql->execute([$doc]);
    $fila = $sql->fetch();

    if (!$fila) {
        echo "Documento no encontrado";
        exit();
    }

    if ($nom != $fila['nombre_completo']) {
        echo "Nombre incorrecto";
        exit();
    }

    if (!password_verify($passw, $fila['password'])) {
        echo "Contraseña incorrecta";
        exit();
    }

    if ($fila['id_estado_usuario'] != 1) {
        echo "Usuario inactivo";
        exit();
    }

    $_SESSION['documento'] = $fila['documento'];
    $_SESSION['tipo'] = $fila['id_rol'];

    echo $fila['id_rol'] == 1 ? "OK_ADMIN" : "OK_USUARIO";

}

?>