<?php

require_once '../../../conecct/conex.php';
require '../../../src/Exception.php';
require '../../../src/PHPMailer.php';
require '../../../src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('America/Bogota');

$database = new Database();
$con = $database->conectar();

$tomorrow = date('l', strtotime('+1 day'));
$dia_semana = [
    'Monday' => 'Lunes',
    'Tuesday' => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday' => 'Jueves',
    'Friday' => 'Viernes'
];

if (isset($dia_semana[$tomorrow])) {
    $dia_esp = $dia_semana[$tomorrow];

    // Consultar los dígitos restringidos para el día
    $stmt = $con->prepare("SELECT digitos_restringidos FROM pico_placa WHERE dia = ?");
    $stmt->execute([$dia_esp]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $digitos = explode(',', $resultado['digitos_restringidos']);

        if (count($digitos) === 0) {
            echo "No hay dígitos restringidos para mañana.<br>";
            exit;
        }

        $placeholders = rtrim(str_repeat('?,', count($digitos)), ',');
        $sql = "
            SELECT v.placa, u.email, u.nombre_completo
            FROM vehiculos v
            INNER JOIN usuarios u ON v.Documento = u.documento
            WHERE RIGHT(v.placa, 1) IN ($placeholders)
        ";

        $stmtVehiculos = $con->prepare($sql);
        $stmtVehiculos->execute($digitos);
        $vehiculos = $stmtVehiculos->fetchAll(PDO::FETCH_ASSOC);

        if (empty($vehiculos)) {
            echo "No hay vehículos para enviar recordatorio mañana.<br>";
            exit;
        }

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

        foreach ($vehiculos as $vehiculo) {
            try {
                $mail->clearAddresses();
                $mail->addAddress($vehiculo['email']);

                $fecha_envio = date('Y-m-d', strtotime('+1 day'));
                $verifica = $con->prepare("SELECT COUNT(*) FROM correos_enviados_pico_placa WHERE placa = :placa AND email = :email AND fecha_envio = :fecha");
                $verifica->execute([
                    'placa' => $vehiculo['placa'],
                    'email' => $vehiculo['email'],
                    'fecha' => $fecha_envio
                ]);
                if ($verifica->fetchColumn() > 0) {
                    echo "Ya se envió recordatorio a {$vehiculo['email']} para la placa {$vehiculo['placa']} el $fecha_envio<br>";
                    continue;
                }

                $mail->Subject = "Recordatorio: Pico y Placa mañana para su vehículo";
                $mensaje = generarMensaje($vehiculo, $dia_esp);
                if (enviarNotificacion($mail, $mensaje)) {
                    $registra = $con->prepare("INSERT INTO correos_enviados_pico_placa (placa, email, fecha_envio) VALUES (:placa, :email, :fecha)");
                    $registra->execute([
                        'placa' => $vehiculo['placa'],
                        'email' => $vehiculo['email'],
                        'fecha' => $fecha_envio
                    ]);
                    echo "Correo enviado a: " . $vehiculo['email'] . " (Pico y Placa $fecha_envio)<br>";
                } else {
                    echo "Error al enviar correo a {$vehiculo['email']}: {$mail->ErrorInfo}<br>";
                }

            } catch (Exception $e) {
                echo "Excepción al enviar a {$vehiculo['email']}: {$mail->ErrorInfo}<br>";
                error_log("Error al enviar a {$vehiculo['email']}: {$mail->ErrorInfo}");
                continue;
            }
        }
    } else {
        echo "No hay configuración de pico y placa para el día $dia_esp.<br>";
    }
} else {
    echo "Mañana no hay pico y placa.<br>";
}

function generarMensaje($vehiculo, $dia) {
    return "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <h2>Recordatorio de Pico y Placa</h2>
        <p>Estimado/a {$vehiculo['nombre_completo']},</p>
        <p>Le recordamos que mañana <strong>{$dia}</strong> su vehículo con placa 
           <strong>{$vehiculo['placa']}</strong> tiene restricción de Pico y Placa.</p>
        <p>Horarios de restricción:</p>
        <ul>
            <li>Mañana: <strong>6:00 AM - 11:00 AM</strong></li>
            <li>Tarde: <strong>3:00 PM - 5:00 PM</strong></li>
        </ul>
        <p>Planifique sus desplazamientos teniendo en cuenta esta restricción.</p>
        <p>Atentamente,<br>Sistema de Recordatorios</p>
    </body>
    </html>";
}

function enviarNotificacion($mail, $mensaje) {
    $mail->isHTML(true);
    $mail->Body = $mensaje;
    return $mail->send();
}

?>