<?php
require_once '../../../conecct/conex.php';
require '../../../src/Exception.php';
require '../../../src/PHPMailer.php';
require '../../../src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Crear conexión a la base de datos
$database = new Database();
$con = $database->conectar();

// Obtener fecha actual
$hoy = new DateTime();

// Crear una única instancia de PHPMailer
$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'flotavehicularagc@gmail.com';
$mail->Password = 'brgl znfz eqfk mcct';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;
$mail->setFrom('flotavehicularagc@gmail.com', 'Sistema de Recordatorios');

// Consulta para obtener los SOAT registrados con sus datos relacionados
$query = "SELECT s.*, v.placa, u.email, u.nombre_completo, a.nombre as nombre_aseguradora 
          FROM soat s 
          INNER JOIN vehiculos v ON s.id_placa = v.placa 
          INNER JOIN usuarios u ON v.Documento = u.documento 
          INNER JOIN aseguradoras_soat a ON s.id_aseguradora = a.id_asegura
          WHERE s.id_estado = 1";

$stmt = $con->prepare($query);
$stmt->execute();
$soats = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($soats as $soat) {
    try {
        $fechaVencimiento = new DateTime($soat['fecha_vencimiento']);
        $interval = $hoy->diff($fechaVencimiento);
        $diasRestantes = (int)$interval->format('%r%a'); // Permite negativos

        // Limpiar destinatarios anteriores
        $mail->clearAddresses();
        $mail->addAddress($soat['email']);

        // Lógica de recordatorios
        if ($diasRestantes == 90) {
            $mail->Subject = 'Recordatorio: Tu SOAT vence en 3 meses';
            $mensaje = generarMensaje($soat, '3 meses');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $soat['email'] . " (90 días)<br>";

        } else if ($diasRestantes == 30) {
            $mail->Subject = 'Recordatorio: Tu SOAT vence en 1 mes';
            $mensaje = generarMensaje($soat, '1 mes');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $soat['email'] . " (30 días)<br>";

        } else if ($diasRestantes == 1) {
            $mail->Subject = '¡URGENTE! Tu SOAT vence mañana';
            $mensaje = generarMensaje($soat, '1 día');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $soat['email'] . " (1 día)<br>";

        } else if ($diasRestantes == 0) {
            $mail->Subject = '¡ATENCIÓN! Tu SOAT ha vencido hoy';
            $mensaje = generarMensaje($soat, 'hoy');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $soat['email'] . " (vencido)<br>";

            // Actualizar estado del SOAT a vencido
            $updateQuery = "UPDATE soat SET id_estado = 2 WHERE id_soat = :id_soat";
            $updateStmt = $con->prepare($updateQuery);
            $updateStmt->execute(['id_soat' => $soat['id_soat']]);
        }

    } catch (Exception $e) {
        error_log("Error al enviar correo a {$soat['email']}: {$mail->ErrorInfo}");
        continue;
    }
}

function generarMensaje($soat, $tiempo) {
    return "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <h2>Recordatorio de Vencimiento de SOAT</h2>
        <p>Estimado/a {$soat['nombre_completo']},</p>
        <p>Le informamos que el SOAT de su vehículo con placa <strong>{$soat['placa']}</strong> 
        de la aseguradora {$soat['nombre_aseguradora']} vence en {$tiempo}.</p>
        <p>Detalles del SOAT:</p>
        <ul>
            <li>Fecha de vencimiento: {$soat['fecha_vencimiento']}</li>
            <li>Placa del vehículo: {$soat['placa']}</li>
            <li>Aseguradora: {$soat['nombre_aseguradora']}</li>
        </ul>
        <p>Por favor, renueve su SOAT a tiempo para evitar inconvenientes.</p>
        <p>Atentamente,<br>Sistema de Recordatorios</p>
    </body>
    </html>";
}

function enviarNotificacion($mail, $mensaje) {
    $mail->isHTML(true);
    $mail->Body = $mensaje;
    $mail->send();
}
?>