<?php
require_once('../conecct/conex.php');
$db = new Database();
$con = $db->conectar();
session_start();
$estado = 1 ;
$rol = 2;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="shortcut icon" href="../css/img/logo_sinfondo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/stylelog_re.css">
</head>
<body onload="formulario.doc.focus()">
    <div class ="content">
        <div class="regresar"><a href="../index" class="re">
            <i class="bi bi-house-door-fill"></i>
        </a>
        </div>
        <div class = "conten_form">
            <div class="form_infor">
                <img src="../css/img/logo_sinfondo.png" alt="logo" class="logo">
                
                <h1 class= "titulo" >Registro</h1>
                <form action = "" method = "post" id="formulario" enctype = "multipart/form-data" autocomplete="off">
                    <div class= "input_grupo">
                        <div>
                            <div class = "input_field_doc" id="grupo_doc">
                                <label for="doc"></label>
                                <i class="bi bi-person-vcard"></i>
                                <input type="number" name = "doc" id = "doc" placeholder = "Documento">
                            </div>
                            <div class="formulario_error_doc">
                                <p class="validacion" id="validacion">El documento solo debe contener numeros y el minimo son 6 digitos y el maximo son 10 dígitos.</p>
                            </div>  
                        </div>
                        <div>
                            <div class = "input_field_nom" id="grupo_nom">
                                <label for="nom"></label>
                                <i class="bi bi-card-heading"></i>
                                <input type="text" name = "nom" id = "nom" placeholder = "Nombre">
                            </div>
                            <div class="formulario_error_nom">
                                <p class="validacion1" id="validacion1">Ingrese el nombre completo sin caracteres especiales</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class = "input_field_correo" id="grupo_correo">
                                <label for="correo"></label>
                                <i class="bi bi-envelope-fill"></i>
                                <input type="email" name = "correo" id = "correo" placeholder = "Correo">
                            </div>
                            <div class="formulario_error_correo">
                                <p class="validacion2" id="validacion2">Ingrese un correo electrónico válido (ejemplo@gmail.com).</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class = "input_field_con" id="grupo_con">
                                <label for="con"></label>
                                <i class="bi bi-eye-slash" id="showpass1" onclick="showpass1()"></i>
                                <input type="password" name = "con" id = "con" placeholder = "Contraseña" value="" maxlength= "15" minlength = "8">
                            </div>
                            <div class="formulario_error_con">
                                <p class="validacion3" id="validacion3">La contraseña debe tener entre 4 y 12 caracteres...</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class = "input_field_con2" id="grupo_con2">
                                <label for="con2"></label>
                                <i class="bi bi-eye-slash" id="showpass2" onclick="showpass2()"></i>
                                <input type="password" name = "con2" id = "con2" placeholder = "Confirmar Contraseña" value="" maxlength= "15" minlength = "8">
                            </div>
                            <div class="formulario_error_con2">
                                <p class="validacion4" id="validacion4">Las contraseñas deben ser iguales...</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class = "input_field_cel" id="grupo_cel">
                                <label for="cel"></label>
                                <i class="bi bi-telephone-fill"></i>
                                <input type="number" name = "cel" id = "cel" placeholder = "Telefono">
                            </div>
                            <div class="formulario_error_cel">
                                <p class="validacion5" id="validacion5">El numero telefonico solo debe contener numeros y el maximo son 10 dígitos.</p>
                            </div>
                        </div>
                        


                    </div>
                    <div>
                        <p class="formulario_error" id="formulario_error"><b>Error:</b> Por favor rellena el formulario correctamente.</p>
                    </div>
                    <div class = "btn-field">
                        <button type="submit" name = "enviar" id="enviar" value = "Guardar" class="btn btn-primary">Registrarse</button>
                    </div>
                    <p class="formulario_exito" id="formulario_exito">Registro exitoso...</p>
                    <p>¿Ya tienes una cuenta?<a class="res" href="login">Inicia Sesion</a></p>
                </form>
            </div>
        </div>
    </div>
    <script>
        function showpass1() {
            const passw = document.getElementById("con");
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
            const passw = document.getElementById("con2");
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
    <script src="../js/scriptregistro.js"></script>
</body>
</html>
