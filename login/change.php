<?php
session_start();
require_once('../conecct/conex.php');
$db = new Database();
$con = $db->conectar();

if (!isset($_GET['token'])) {
    echo '<script>alert("Acceso no autorizado.");</script>';
    echo '<script>window.location = "recovery.php";</script>';
    exit;
}

$token = $_GET['token'];
$expira = $_GET['token'];

$query = $con->prepare("SELECT * FROM usuarios WHERE reset_token = ? AND reset_expira >= NOW()");
$query->execute([$token] );
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo '<script>alert("El token es inválido o ha expirado.");</script>';
    echo '<script>window.location = "recovery.php";</script>';
    exit;
}

$id_usuario = $user['documento'];
$email = $user['email'];

if (isset($_POST['submit'])) {
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    if (strlen($password1) < 6) {
        echo '<script>alert("La contraseña debe tener al menos 6 caracteres.");</script>';
    } elseif ($password1 !== $password2) {
        echo '<script>alert("Las contraseñas no coinciden.");</script>';
    } else {
        $hashedPassword = password_hash($password2, PASSWORD_DEFAULT, array("cost" => 12));

        $update = $con->prepare("UPDATE usuarios SET contraseña = ?, reset_token = NULL, reset_expira = NULL WHERE documento = ?");
        $update->execute([$hashedPassword, $id_usuario]);

        if ($update) {
            echo '<script>alert("Contraseña actualizada exitosamente.");</script>';
            echo '<script>window.location = "login.php";</script>';
        } else {
            echo '<script>alert("Error al actualizar la contraseña.");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="shortcut icon" href="../css/img/logo_sinfondo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/stylelog.css">

</head>
<body onload="form_con.password1.focus()">
<div class = "contenido">
            <div class="re">
                <a href="login.php" class="regresar">
                    <i class="bi bi-house-door-fill"></i>
                </a>
            </div>
    <div class="conten_form">
        <div class="form-infor">
        <a href="index.php"><img src="../css/img/logo_sinfondo.png" alt="logo" class="logo"></a>
        <h2>Cambiar Contraseña</h2>
        <p>Por favor, ingresa tu nueva contraseña.</p>

            <form action="" method="POST" autocomplete="off" id="form_con">
            
                <div class="input-field">
                    <input type="password" id="password1" name="password1" required>
                    <label for="password1">Nueva Contraseña</label>
                    <i class='bi bi-envelope-fill' id="showpass1" onclick="showpass1()"></i>
                </div>
                <div class="input-field">
                    <input type="password" id="password2" name="password2" required>
                    <label for="password2">Confirmar Contraseña</label>
                    <i class='bi bi-envelope-fill' id="showpass2" onclick="showpass2()"></i>
                </div>
                <div class="btn-field">
                    <button type="submit" class="primary-btn" name="submit">Cambiar Contraseña</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showpass1() {
            const passw = document.getElementById("password1");
            const iconshow = document.getElementById("showpass1");
            
            if (passw.type === "password") {
                passw.type = "text";
                iconshow.classList.replace("bx-show", "bx-hide");
            } else {
                passw.type = "password";
                iconshow.classList.replace("bx-hide", "bx-show");
            }
        }

        function showpass2() {
            const passw = document.getElementById("password2");
            const iconshow = document.getElementById("showpass2");
            
            if (passw.type === "password") {
                passw.type = "text";
                iconshow.classList.replace("bx-show", "bx-hide");
            } else {
                passw.type = "password";
                iconshow.classList.replace("bx-hide", "bx-show");
            }
        }
    </script>
</body>
</html>



