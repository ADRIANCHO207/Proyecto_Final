<?php
require_once '../../../conecct/conex.php';
require '../../../src/Exception.php';
require '../../../src/PHPMailer.php';
require '../../../src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Forzar zona horaria de Colombia
date_default_timezone_set('America/Bogota');

// Crear conexión a la base de datos
$database = new Database();
$con = $database->conectar();

// Obtener fecha actual en hora local
$hoy = new DateTime('now', new DateTimeZone('America/Bogota'));

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

// Consulta para obtener las tecnomecánicas registradas con sus datos relacionados
$query = "SELECT t.*, v.placa, u.email, u.nombre_completo, c.centro_revision 
          FROM tecnomecanica t 
          INNER JOIN vehiculos v ON t.id_placa = v.placa 
          INNER JOIN usuarios u ON v.Documento = u.documento 
          INNER JOIN centro_rtm c ON t.id_centro_revision = c.id_centro
          WHERE t.id_estado = 1";

$stmt = $con->prepare($query);
$stmt->execute();
$tecnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($tecnos as $tecno) {
    try {
        $fechaVencimiento = new DateTime($tecno['fecha_vencimiento']);
        $interval = $hoy->diff($fechaVencimiento);
        $diasRestantes = (int)$interval->format('%r%a'); // Permite negativos

        // Limpiar destinatarios anteriores
        $mail->clearAddresses();
        $mail->addAddress($tecno['email']);

        // Lógica de recordatorios
        if ($diasRestantes == 90) {
            $mail->Subject = 'Recordatorio: Tu Tecnomecánica vence en 3 meses';
            $mensaje = generarMensaje($tecno, '3 meses');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $tecno['email'] . " (90 días)<br>";

        } else if ($diasRestantes == 30) {
            $mail->Subject = 'Recordatorio: Tu Tecnomecánica vence en 1 mes';
            $mensaje = generarMensaje($tecno, '1 mes');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $tecno['email'] . " (30 días)<br>";

        } else if ($diasRestantes == 1) {
            $mail->Subject = '¡URGENTE! Tu Tecnomecánica vence mañana';
            $mensaje = generarMensaje($tecno, '1 día');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $tecno['email'] . " (1 día)<br>";

        } else if ($diasRestantes == 0) {
            $mail->Subject = '¡ATENCIÓN! Tu Tecnomecánica ha vencido hoy';
            $mensaje = generarMensaje($tecno, 'hoy');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $tecno['email'] . " (vencido)<br>";

            // Actualizar estado de la tecnomecánica a vencido
            $updateQuery = "UPDATE tecnomecanica SET id_estado = 2 WHERE id_rtm = :id_rtm";
            $updateStmt = $con->prepare($updateQuery);
            $updateStmt->execute(['id_rtm' => $tecno['id_rtm']]);
        }

    } catch (Exception $e) {
        error_log("Error al enviar correo a {$tecno['email']}: {$mail->ErrorInfo}");
        continue;
    }
}

function generarMensaje($tecno, $tiempo) {
    return "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <h2>Recordatorio de Vencimiento de Tecnomecánica</h2>
        <p>Estimado/a {$tecno['nombre_completo']},</p>
        <p>Le informamos que la revisión tecnomecánica de su vehículo con placa <strong>{$tecno['placa']}</strong> 
        realizada en {$tecno['centro_revision']} vence en {$tiempo}.</p>
        <p>Detalles de la Tecnomecánica:</p>
        <ul>
            <li>Fecha de vencimiento: {$tecno['fecha_vencimiento']}</li>
            <li>Placa del vehículo: {$tecno['placa']}</li>
            <li>Centro de revisión: {$tecno['centro_revision']}</li>
        </ul>
        <p>Por favor, programe su revisión tecnomecánica a tiempo para evitar inconvenientes.</p>
        <p>Atentamente,<br>Sistema de Recordatorios</p>
    </body>
    </html>";
}

function enviarNotificacion($mail, $mensaje) {
    $mail->isHTML(true);
    $mail->Body = $mensaje;
    $timestamp = date('Y-m-d H:i:s');
    $result = $mail->send();
    
    if($result) {
        error_log("Correo enviado exitosamente a las $timestamp (Hora Colombia)");
    }
    
    return $result;
}
?>