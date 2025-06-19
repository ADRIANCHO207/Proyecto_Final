<?php
require_once('../conecct/conex.php');
$db = new Database();
$con = $db->conectar();
session_start();
$estado = 1 ;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="shortcut icon" href="/Proyecto/css/img/logo_sinfondo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/Proyecto/css/stylelog_re.css">
</head>
<body onload="formulario.doc.focus()">  
    
        <div class = "content">
            <div class="regresar"><a href="/Proyecto/index" class="re">
                <i class="bi bi-house-door-fill"></i>
            </a>
            </div>

            <div class = "conten_form">
                <div class = "form-info">
                    
                    <div class = "form-infor">
                        <img src="/Proyecto/css/img/logo_sinfondo.png" alt="logo" class="logo">
                        <h1 class = "titu">Login</h1>
                    
                        <form action="../includes/inicio.php" method= "POST"  id="formulario" enctype = "multipart/form-data" autocomplete="off">

                            <div class = "input-gruop">
                                <div>
                                    <div class = "input_field_doc" id="grupo_doc">
                                        <label for="doc" class = "input_label"></label>
                                        <i class="bi bi-person-vcard"></i>
                                        <input type="number" name = "doc" id="doc" placeholder = "Documento">
                                    </div>
                                    <div class="formulario_error_doc" id="formulario_correcto_doc">
                                        <p class="validacion" id="validacion">El documento solo debe contener numeros y el minimo son 6 digitos y el maximo son 10 dígitos.</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class = "input_field_passw" id="grupo_passw">
                                        <label for="passw"></label>
                                        <i class="bi bi-eye-slash" id="showpass1" onclick="showpass1()"></i>
                                        <input type="password" name = "passw" id = "passw" placeholder = "Contraseña">
                                    </div>
                                    <div class="formulario_error_passw" id=" formulario_correcto_passw">
                                        <p class="validacion2" id="validacion2">La contraseña debe tener entre 4 y 12 caracteres...</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="formulario_error" id="formulario_error"><b>Error:</b> Por favor rellena el formulario correctamente.</p>
                            </div>
                            <div class = "btn-field">
                                <button type="submit" name = "log" id="log" value = "Log" class="btn btn-primary">Log in</button>
                            </div>
                            <p class="formulario_exito" id="formulario_exito">Iniciando sesion...</p>
                            <a href="/Proyecto/login/recovery"><label>Olvidaste tu contraseña?</label></a> 
                            <a href="/Proyecto/login/register"><label class="col">No tienes cuenta, Registrate</label></a> 
                        </form>
                    </div>
                </div>
            </div>
       </div>
    <script>
        function showpass1() {
            const passw = document.getElementById("passw");
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
    </script>
</body>

<script src="../js/scriptlogin.js"></script>
</html>
