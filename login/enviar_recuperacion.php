<?php
date_default_timezone_set('America/Bogota'); // Ajusta la zona horaria según tu ubicación
require '../src/PHPMailer.php';
require '../src/SMTP.php';
require '../src/Exception.php';
require '../conecct/conex.php'; // Conexión a la base de datos


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        echo '<script>alert("Ningún dato puede estar vacío");</script>';
        echo '<script>window.location = "recovery";</script>';
        exit;
    }

    // Conectar a la base de datos
    $db = new Database();
    $con = $db->conectar();

    // Verificar si el usuario existe
    $stmt = $con->prepare("SELECT documento FROM usuarios WHERE email = ? ");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo '<script>alert("Email incorrecto");</script>';
        echo '<script>window.location = "recovery;</script>';
        exit;
    }

    // Generar un token único
    $token = bin2hex(random_bytes(50));
    $expira = date("Y-m-d H:i:s", strtotime("+1 hour")); // Expira en 1 hora

    // Guardar el token en la base de datos
    $stmt = $con->prepare("UPDATE usuarios SET reset_token = ?, reset_expira = ? WHERE documento = ?");
    $stmt->execute([$token, $expira, $user['documento']]);

    // Configurar PHPMailer
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'flotavehicularagc@gmail.com';
        $mail->Password = 'brgl znfz eqfk mcct';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('flotavehicularagc@gmail.com', 'Recuperar Contraseña');
        $mail->addAddress($email);
        $mail->Subject = 'Recuperación de contraseña - Flota Vehicular';

        // Enlace de recuperación
        $reset_link = "https://flotaxagc.com/login/change?token=" . urlencode($token);

        // Contenido del correo.php
        $logoUrl = 'https://logosinfondo.netlify.app/logo_sinfondo.png'; // Asegúrate que esta sea la URL exacta de tu imagen

        $mail->isHTML(true);
        $mail->Body = "
        <div style='background-color: #1a1a1a; width: 100%; padding: 20px 0; font-family: Arial, sans-serif;'>
            <div style='background-color: #262626; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.5);'>
                <!-- Logo centrado -->
                <div style='width: 150px; height: 150px; margin: auto; background-image: url(https://logosinfondo.netlify.app/logo_sinfondo.png); 
                background-size: contain; background-repeat: no-repeat; background-position: center;'></div>
                
                <h2 style='color: #ffffff; text-align: center; margin-bottom: 20px; font-size: 24px;'>Recuperación de contraseña</h2>
                
                <div style='color: #e0e0e0; text-align: center; line-height: 1.6;'>
                    <h4>Hola, has solicitado recuperar tu contraseña.</h4>
                    <p>Haz clic en el siguiente botón para restablecerla:</p>
                    
                    <div style='margin: 30px 0;'>
                        <a href='$reset_link' style='background-color: #d32f2f; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; transition: background-color 0.3s ease;'>Restablecer contraseña</a>
                    </div>
                    
                    <p style='color: #888888; font-size: 14px;'>Si no solicitaste este cambio, ignora este mensaje.</p>
                    <p style='color: #888888; font-size: 14px;'>Este enlace expira en 1 hora.</p>
                </div>
                
                <div style='margin-top: 30px; border-top: 1px solid #444444; padding-top: 20px; text-align: center;'>
                    <p style='color: #888888; font-size: 12px;'>© 2024 Flota Vehicular. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>";

        // Enviar correo
        $mail->send();
        echo '<script>alert("Revisa tu correo para restablecer la contraseña.");</script>';
        echo '<script>window.location = "login";</script>';
    } catch (Exception $e) {
        echo '<script>alert("Error al enviar el correo: ' . $mail->ErrorInfo . '");</script>';
    }
}
?>
