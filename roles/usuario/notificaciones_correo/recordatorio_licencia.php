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

// Consulta para obtener las licencias registradas
$query = "SELECT l.*, u.email, u.nombre_completo, c.nombre_categoria, s.nombre_servicios
          FROM licencias l 
          INNER JOIN usuarios u ON l.id_documento = u.documento 
          INNER JOIN categoria_licencia c ON l.id_categoria = c.id_categoria
          INNER JOIN servicios_licencias s ON l.id_servicio = s.id_servicio
          WHERE l.fecha_vencimiento >= CURDATE()";  // Solo licencias vigentes

$stmt = $con->prepare($query);
$stmt->execute();
$licencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($licencias as $licencia) {
    try {
        $fechaVencimiento = new DateTime($licencia['fecha_vencimiento']);
        $interval = $hoy->diff($fechaVencimiento);
        $diasRestantes = (int)$interval->format('%r%a');

        // Limpiar destinatarios anteriores
        $mail->clearAddresses();
        $mail->addAddress($licencia['email']);

        // Lógica de recordatorios sin actualización de estado
        if ($diasRestantes == 90) {
            $mail->Subject = 'Recordatorio: Tu Licencia vence en 3 meses';
            $mensaje = generarMensaje($licencia, '3 meses');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $licencia['email'] . " (90 días)<br>";

        } else if ($diasRestantes == 30) {
            $mail->Subject = 'Recordatorio: Tu Licencia vence en 1 mes';
            $mensaje = generarMensaje($licencia, '1 mes');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $licencia['email'] . " (30 días)<br>";

        } else if ($diasRestantes == 1) {
            $mail->Subject = '¡URGENTE! Tu Licencia vence mañana';
            $mensaje = generarMensaje($licencia, '1 día');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $licencia['email'] . " (1 día)<br>";

        } else if ($diasRestantes == 0) {
            $mail->Subject = '¡ATENCIÓN! Tu Licencia ha vencido hoy';
            $mensaje = generarMensaje($licencia, 'hoy');
            enviarNotificacion($mail, $mensaje);
            echo "Correo enviado a: " . $licencia['email'] . " (vencido)<br>";
        }

    } catch (Exception $e) {
        error_log("Error al enviar correo a {$licencia['email']}: {$mail->ErrorInfo}");
        continue;
    }
}

function generarMensaje($licencia, $tiempo) {
    return "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <h2>Recordatorio de Vencimiento de Licencia</h2>
        <p>Estimado/a {$licencia['nombre_completo']},</p>
        <p>Le informamos que su licencia de conducción categoría <strong>{$licencia['nombre_categoria']}</strong> 
        para servicio {$licencia['nombre_servicios']} vence en {$tiempo}.</p>
        <p>Detalles de la Licencia:</p>
        <ul>
            <li>Fecha de vencimiento: {$licencia['fecha_vencimiento']}</li>
            <li>Categoría: {$licencia['nombre_categoria']}</li>
            <li>Tipo de servicio: {$licencia['nombre_servicios']}</li>
            <li>Restricciones: {$licencia['observaciones']}</li>
        </ul>
        <p>Por favor, renueve su licencia a tiempo para evitar inconvenientes.</p>
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