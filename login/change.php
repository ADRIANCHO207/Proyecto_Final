<?php
session_start();
require_once('../conecct/conex.php');
$db = new Database();
$con = $db->conectar();

if (!isset($_GET['token'])) {
    echo '<script>alert("Acceso no autorizado.");</script>';
    echo '<script>window.location = "recovery";</script>';
    exit;
}

$token = $_GET['token'];
$expira = $_GET['token'];

$query = $con->prepare("SELECT * FROM usuarios WHERE reset_token = ? AND reset_expira >= NOW()");
$query->execute([$token] );
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo '<script>alert("El token es inválido o ha expirado.");</script>';
    echo '<script>window.location = "recovery";</script>';
    exit;
}

$id_usuario = $user['documento'];
$email = $user['email'];

if (isset($_POST['enviar'])) {
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    // Validar si la nueva contraseña es igual a la anterior
    if (password_verify($password1, $user['password'])) {
        echo '<script>alert("La nueva contraseña no puede ser igual a la anterior.");</script>';
    } elseif (strlen($password1) < 6) {
        echo '<script>alert("La contraseña debe tener al menos 6 caracteres.");</script>';
    } elseif ($password1 !== $password2) {
        echo '<script>alert("Las contraseñas no coinciden.");</script>';
    } else {
        $hashedPassword = password_hash($password2, PASSWORD_DEFAULT, array("cost" => 12));

        $update = $con->prepare("UPDATE usuarios SET password = ?, reset_token = NULL, reset_expira = NULL WHERE documento = ?");
        $update->execute([$hashedPassword, $id_usuario]);

        if ($update->rowCount() > 0) {
            echo '<script>alert("Contraseña actualizada exitosamente.");</script>';
            echo '<script>window.location = "login";</script>';
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
    <link rel="stylesheet" href="../css/stylelog_re.css">

</head>
<body onload="form_con.password1.focus()">
<div class = "contenido">
    <div class="regresar"><a href="login" class="re">
        <i class="bi bi-house-door-fill"></i>
        </a>
    </div>
    <div class="conten_form">
        <div class="form-infor">
            <img src="../css/img/logo_sinfondo.png" alt="logo" class="logo">
            <h2>Cambiar Contraseña</h2>
            <p>Por favor, ingresa tu nueva contraseña.</p>

            <form action="" method="POST" autocomplete="off" id="form_con">

                <div class = "input-gruop">
                    <div>
                        <div class = "input_field_passw1" id="grupo_passw1">
                            <label for="password1" class = "input_label"></label>
                            <i class="bi bi-eye-slash" id="showpass1" onclick="showpass1()"></i>
                            <input type="password" name = "password1" id="password1" placeholder = "Nueva contraseña">
                        </div>
                        <div class="formulario_error_passw1" id="formulario_correcto_passw1">
                            <p class="validacion_passw1" id="validacion_passw1">La contraseña debe tener entre 8 a 14 caracteres, debe llevar una mayucula, minuscula y un caracter especial.</p>
                        </div>
                    </div>
                    
                    
                    <div>
                        <div class = "input_field_passw2" id="grupo_passw2">
                            <label for="password2"></label>
                            <i class="bi bi-eye-slash" id="showpass2" onclick="showpass2()"></i>
                            <input type="password" name = "password2" id = "password2" placeholder = "Confirmar contraseña">
                        </div>
                        <div class="formulario_error_passw2" id="formulario_correcto_passw2">
                            <p class="validacion_passw2" id="validacion_passw2">Las contraseñas deben ser iguales...</p>
                        </div>
                    </div>
            
                    <div>
                        <p class="formulario_error" id="formulario_error"><b>Error:</b> Existen campos vacios, asegurate de digitar la nueva contraseña correctamente.</p>
                    </div>
                    <div class = "btn-field">
                        <button type="submit" name = "enviar" id="enviar" value = "Guardar" class="btn btn-primary">Cambiar contraseña</button>
                    </div>
                    <p class="formulario_exito" id="formulario_exito">Cambio de contraseña exitoso...</p>
                </div>
            </form>
        </div>
    </div>

    <script>
        const formulario = document.getElementById('form_con');
        const inputs = document.querySelectorAll('#form_con input')

        const expresiones = {
            validapassword: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,14}$/
        }

        const validpassw = (e) =>{
            switch (e.target.name) {
                case "password1": 
                    if(expresiones.validapassword.test(e.target.value)){
                        document.getElementById('grupo_passw1').classList.remove('input_field_passw1_incorrecto')
                        document.getElementById('grupo_passw1').classList.add('input_field_passw1_correcto')
                        document.getElementById('validacion_passw1').style.opacity = 0;
                        
                    }else{
                        document.getElementById('grupo_passw1').classList.remove('input_field_passw1_correcto')
                        document.getElementById('grupo_passw1').classList.add('input_field_passw1_incorrecto')
                        document.getElementById('validacion_passw1').style.opacity = 1;
                    }
                    validarPassword2()
                break
                case "password2":
                    validarPassword2()
                break
            }
        }

        const validarPassword2 = () => {
            const inputPassword1 = document.getElementById('password1');
            const inputPassword2 = document.getElementById('password2');
            const grupo = document.getElementById('grupo_passw2');
            const mensaje = document.getElementById('validacion_passw2');
            
            if (inputPassword1.value !== inputPassword2.value || inputPassword2.value.length === 0) {
                grupo.classList.add('input_field_passw2_incorrecto');
                grupo.classList.remove('input_field_passw2_correcto');
                mensaje.style.opacity = 1;
                mensaje.textContent = "Las contraseñas no coinciden...";
            } else {
                grupo.classList.remove('input_field_passw2_incorrecto');
                grupo.classList.add('input_field_passw2_correcto');
                mensaje.style.opacity = 0;
            }
        };

        inputs.forEach((input) => {
            input.addEventListener('keyup', validpassw);
            input.addEventListener('blur', validpassw);
        });

        formulario.addEventListener('submit', (e) => {
            if (!expresiones.validapassword.test(inputs[0].value)) {
                e.preventDefault();
                document.getElementById('grupo_passw1').classList.add('input_field_passw1_incorrecto')
                document.getElementById('validacion_passw1').style.opacity = 1;
                document.getElementById('formulario_error').style.opacity = 1;
                document.getElementById('formulario_error').style.color = "#d32f2f"
                document.getElementById('password1').focus();

                setTimeout(() => {
                document.getElementById('formulario_error').style.opacity = 0;
                }, 3000)
            }else{
                document.getElementById('formulario_exito').style.opacity = 1;
                document.getElementById('formulario_exito').style.color = "#158000"

                setTimeout(() => {
                document.getElementById('formulario_exito').style.opacity = 0;
                }, 3000)
            }
        })   


        function showpass1() {
            const passw = document.getElementById("password1");
            const icon = document.getElementById("showpass1");

            if (passw.type === "password") {
                passw.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                passw.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        }

        function showpass2() {
            const passw = document.getElementById("password2");
            const icon = document.getElementById("showpass2");

            if (passw.type === "password") {
                passw.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                passw.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        }



    </script>
</body>
</html>



