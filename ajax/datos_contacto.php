<?php
require_once('../conecct/conex.php');
$db = new Database();
$con = $db->conectar(); // ← corregido aquí

$nom = $_POST['nom'] ?? '';
$ape = $_POST['ape'] ?? '';
$corre = $_POST['corre'] ?? '';
$mensa = $_POST['mensa'] ?? '';

if ($nom && $ape && $corre && $mensa) {
    $inserto = $con->prepare("INSERT INTO contacto(nom, apellido, email, mensaje) VALUES(?, ?, ?, ?)");
    $inserto->execute([$nom, $ape, $corre, $mensa]);
    echo "Datos subidos";
} else {
    echo "Datos no enviados";
}
?>
