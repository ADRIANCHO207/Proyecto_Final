<?php
require_once('conecct/conex.php');
$db = new Database();
$con = $db->conectar();

?>
<?php

if (isset ($_POST['enviar'])){
    $nom = $_POST['nom'];
    $ape =$_POST['ape'];
    $correo = $_POST['corre'];
    $mensa = $_POST['mensa'];

    echo $nom, $ape, $correo, $mensa;

    $inserto = $con->prepare("INSERT INTO contacto(nom, apellido, email, mensaje)
    VALUES('$nom', '$ape', '$correo', '$mensa')");
    $inserto->execute();
    echo '<script>alert ("Gracias por dejar tu mensaje estaremos al tanto...") </script>';
    echo '<script>window.location = "contacto.php"</script>';
}





?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="css/img/logo_sinfondo.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/stylos_generales.css">
</head>
<body>
<?php
    include ('header.html');
?>
<h1>Contactanos</h1>
<div class="contenido_info">
    <div class="informa">
        <h2>¿Tienes dudas o necesitas más información?</h2>
        <p>Si tienes alguna pregunta o necesitas más detalles sobre nuestros servicios, no dudes en
            ponerte en contacto con nosotros. Para todas las consultas, envíanos un correo electrónico
            a:
        </p>
        <h2>Correo Electrónico:</h2>
        <a href="">flotavehicular69@gmail.com</a>
        <h2>telefono:</h2>
        <a href="">+57 310 857 1290</a>

        <br><br>

        <p>Estamos aquí para ayudarte y responder a todas tus inquietudes.</p>
        <div class="img2">
            <img src="css/img/ima2.jpg" alt="img">
        </div>



    </div>

    <div class="contenido_form">

        <div class="formulario">
            <form action="" method= "post" class = "form"  id = "form" enctype = "multipart/form-data">
                <div class = "input-gruop">
                    <div class = "input_field" id = "grupo_nom">
                        <label for="nom" class = "input_label">Nombre:*</label>
                        <input type="text" name = "nom" id="nom" placeholder = "Juan" >
                        <p class= "warnings" id = "warnings">Ingrese el nombre sin caracteres especiales</p>
                    </div>

                    <div class = "input_field">
                        <label for="ape">Apellido:*</label>
                        <input type="text" name = "ape" id = "ape" placeholder = "Lopez">
                        <p class= "warnings" id = "warnings1">Ingrese el apellido sin caracteres especiales</p>
                    </div>

                    <div class = "input_field">
                        <label for="corre">Correo Electrónico(Solo Gmail):*</label>
                        <input type="email" name = "corre" id = "corre"  placeholder = "aaaaaa30@gmail.com">
                        <p class= "warnings" id = "warnings2">Ingrese un correo electrónico válido (ejemplo@gmail.com).</p>
                    </div>

                    <div class = "input_fiel">
                        <label for="mensa">Mensaje:*</label>
                        <textarea class = "input_mensa" type="text" name = "mensa" id = "mensa" placeholder = "Escribe tu mensaje aquí..." ></textarea>
                        <p class= "warnings" id = "warnings3"></p>
                    </div>
                    <div>
                    <p class= "warnings" id = "warnings4">Rellena el formulario correctamente</p>
                    </div>
                </div>
                <div class = "boton">
                    <button type="submit" name = "enviar" id="enviar" value = "guardar" class="btn btn-primary">Enviar mensaje</button>
                </div>

                <div>
                    <p class= "warnings" id = "warnings5">Mensaje enviado correctamente</p>
                </div>
            </form>


        </div>
    </div>
</div>



<?php
    include ('footer.html');
?>
<script src="ajax/scriptcontacto.js"></script>

</body>
</html>