<?php
session_start();
require_once('../conecct/conex.php');
$db = new Database();
$con = $db->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $correo = trim($_POST['email']);

    if (empty($correo)) {
        echo '<script>alert("Ningún dato puede estar vacío");</script>';
    } else {
        // Verificar si el usuario existe
        $sql = $con->prepare("SELECT email FROM usuarios WHERE email = :email");
        $sql->bindParam(':email', $correo, PDO::PARAM_STR);
        $sql->execute();

        $fila = $sql->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $_SESSION['email'] = $fila['email'];

            // Redirigir con datos de recuperación
            echo '<form id="sendForm" action="enviar_recuperacion.php" method="POST">
                      <input type="hidden" name="email" value="' . htmlspecialchars($correo, ENT_QUOTES, 'UTF-8') . '">
                  </form>
                  <script>document.getElementById("sendForm").submit();</script>';
            exit;
        } else {
            echo '<script>alert("Correo incorrecto");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="shortcut icon" href="../css/img/logo_sinfondo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/stylelog.css">

</head>
<body>
<div class = "contenido">
            <div class="re">
                <a href="login.php" class="regresar">
                    <i class="bi bi-house-door-fill"></i>
                </a>
            </div>

    <div class="conten_form">
        <div class="form-infor">
          <a href="index.php"><img src="../css/img/logo_sinfondo.png" alt="logo" class="logo"></a>
          <h2>¿Olvidaste tu contraseña?</h2>
          <p>No te preocupes, restableceremos tu contraseña. </p> <p> Solo dinos con qué dirección de email te registraste al sistema.</p> 
            <form action="" method="POST" autocomplete="off">

            <div class = "input_field">
                            <label for="correo"></label>
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" name = "email" id = "email" placeholder = "Correo">
                        </div>


                <div class="btn-field">
                    <button type="submit" class="re" name="submit">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
