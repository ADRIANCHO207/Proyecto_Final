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
            echo '<script>alert("Correo no registrado");</script>';
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/stylelog_re.css">

</head>
<body onload="formulario_olvidate_con.email.focus()">
<div class = "content">
    <div class="regresar"><a href="login.php" class="re">
            <i class="bi bi-house-door-fill"></i>
        </a>
    </div>

    <div class="conten_form">
        <div class="form-infor">
          <a href="index.php"><img src="../css/img/logo_sinfondo.png" alt="logo" class="logo"></a>
          <h2>¿Olvidaste tu contraseña?</h2>
          <p>No te preocupes, restableceremos tu contraseña. </p> <p> Solo dinos con qué dirección de email te registraste al sistema.</p> 
            <form action="" method="POST" id="formulario_olvidate_con" autocomplete="off">
                <div>
                    <div class = "input_field_correo" id="input_field_correo">
                        <label for="correo"></label>
                        <i class="bi bi-envelope-fill"></i>
                        <input type="email" name = "email" id = "email" placeholder = "Correo">
                    </div>
                    <div>
                        <p class="formulario_error_olv_con" id="vali_correo">Ingrese un correo electrónico válido (ejemplo@gmail.com).</p>
                    </div>
                    


                </div>
                        

                <p class="formulario_error" id="formulario_error"><b>Error:</b> Por favor coloca el correo correctamente.</p>
                <div class="btn-field">
                    <button type="submit" class="re" name="submit">Enviar</button>
                </div>
                <p class="formulario_exito" id="formulario_exito">Enviando correo...</p>
            </form>
        </div>
    </div>

    <script>
        const formulario_con = document.getElementById('formulario_olvidate_con')
        const inputs = document.querySelectorAll('#formulario_olvidate_con input')


        const expresion = {
            validacorreo:  /^[a-zA-Z0-9._%+-]+@gmail\.com$/
        }

        const validcorreo = (e) =>{
            switch (e.target.name) {
                case "email": 
                    if(expresion.validacorreo.test(e.target.value)){
                        document.getElementById('input_field_correo').classList.remove('input_field_correo')
                        document.getElementById('input_field_correo').classList.add('input_field_correo_correcto')
                        document.getElementById('vali_correo').style.opacity = 0;
                    }else{
                        document.getElementById('input_field_correo').classList.remove('input_field_correo')
                        document.getElementById('input_field_correo').classList.add('input_field_correo_incorrecto')
                        document.getElementById('vali_correo').style.opacity = 1;
                    }
                break
            }
        }

        inputs.forEach((input) => {
            input.addEventListener('keyup', validcorreo)
            input.addEventListener('blur', validcorreo)
        })  
    
        formulario_con.addEventListener('submit', (e) => {
            if (!expresion.validacorreo.test(inputs[0].value)) {
                e.preventDefault();
                document.getElementById('input_field_correo').classList.add('input_field_correo_incorrecto')
                document.getElementById('vali_correo').style.opacity = 1;
                document.getElementById('formulario_error').style.opacity = 1;
                document.getElementById('formulario_error').style.color = "#d32f2f"
                document.getElementById('email').focus();

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

    </script>
</body>
</html>
