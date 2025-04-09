<?php
require_once('conecct/conex.php');
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
    <link rel="shortcut icon" href="css/img/logo_sinfondo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/stylelog.css">
</head>
<body>  
    
        <div class = "contenido">
            <div class="re">
                <a href="index.php" class="regresar">
                    <i class="bi bi-house-door-fill"></i>
                </a>
            </div>

            <div class = "conten_form">
                <div class = "form-info">
                    
                    <div class = "form-infor">
                        <a href="index.php"><img src="css/img/logo_sinfondo.png" alt="logo" class="logo"></a>
                        <h1 class = "titu">Login</h1>
                    
                        <form action="includes/inicio.php" method= "POST" enctype = "multipart/form-data">

                            <div class = "input-gruop">
                                <div class = "input_field">
                                    <label for="doc" class = "input_label"></label>
                                    <i class="bi bi-person-vcard"></i>
                                    <input type="number" name = "doc" id="doc" placeholder = "Documento">
                                </div>

                                <div class = "input_field">
                                    <label for="nom"></label>
                                    <i class="bi bi-card-heading"></i>
                                    <input type="text" name = "nom" id = "nom" placeholder = "Nombre completo">
                                </div>

                                <div class = "input_field">
                                    <label for="passw"></label>
                                    <i class="bi bi-lock-fill"></i>
                                    <input type="password" name = "passw" id = "passw" placeholder = "Contraseña">
                                </div>

                            </div>
                            <div class = "btn-field">
                                <button type="submit" name = "log" id="log" value = "Log" class="btn btn-primary">Log in</button>
                            </div>
                            <a href="#"><label>Olvidaste tu contraseña?</label></a> 
                            <a href="register.php"><label class="col">No tienes cuenta, Registrate</label></a> 
                        </form>
                    </div>
                </div>
            </div>
       </div>
</body>
</html>