<?php
session_start();

if (!isset($_SESSION['cedula'])) {
    echo "No hay una cédula registrada en la sesión.";
    exit;
}

$documentNumber = $_SESSION['cedula'];
$documentType = 'CC';
$token = 'TU_TOKEN_AQUI'; // Reemplaza con tu token

$url = "https://api.verifik.co/v2/co/simit/consultar?documentType=$documentType&documentNumber=$documentNumber";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    file_put_contents("respuesta.json", $response);
    header("Location: resultado.php");
    exit;
} else {
    echo "Error HTTP $httpCode:<br>$response";
}
