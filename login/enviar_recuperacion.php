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
        echo '<script>window.location = "recovery.php";</script>';
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
        echo '<script>window.location = "recovery.php";</script>';
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
        $reset_link = "http://localhost/Proyecto_Final/login/change.php?token=" . urlencode($token);

        // Contenido del correo.php
        $mail->isHTML(true);
        $mail->Body = "<h2>Recuperación de contrasena</h2>
                       <p>Hola, has solicitado recuperar tu contraseña.</p>
                       <p>Haz clic en el siguiente enlace para restablecerla:</p>
                       <p><a href='$reset_link' style='background-color: #d32f2f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Restablecer contrasena</a></p>
                       <p>Si no solicitaste este cambio, ignora este mensaje.</p>
                       <p>Este enlace expira en 1 hora.</p>";

        // Enviar correo
        $mail->send();
        echo '<script>alert("Revisa tu correo para restablecer la contraseña.");</script>';
        echo '<script>window.location = "login.php";</script>';
    } catch (Exception $e) {
        echo '<script>alert("Error al enviar el correo: ' . $mail->ErrorInfo . '");</script>';
    }
}
?>
