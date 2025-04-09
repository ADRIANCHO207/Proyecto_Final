<?php
require_once('conecct/conex.php');
$db = new Database();
$con = $db->conectar();
session_start();
$estado = 1 ;
?>

<?php
    if (isset ($_POST['enviar'])){
        $doc = $_POST['doc'];
        $nom =$_POST['nom'];
        $correo = $_POST['correo'];
        $cont = $_POST['con'];
        $con2 = $_POST['con2'];
        $cel = $_POST['cel'];
        $rol = $_POST['id_rol'];

        // echo $doc,"\n", $nom, "\n", $correo, "\n", $cont, "\n", $con2, "\n", $cel, "\n", $rol, "\n", $estado;

        if ($cont != $con2){
            echo '<script>alert ("Contraseñas no son iguales...")</script>';
            echo '<script>window.location = "register.php"</script>';
            
        }
        else{
            // vamos a encriptar la contraseña ingresada por el usuario
            $cont_enc = password_hash($cont, PASSWORD_DEFAULT, array ("pass"=>12));
        
            

            $sql1 = $con-> prepare("SELECT * FROM usuarios WHERE documento = '$doc'");
            $sql1->execute();
            $fila = $sql1->fetchAll(PDO::FETCH_ASSOC);

            if($fila){
                echo '<script>alert ("Documento ya existe no se puede repetir")</script>';
                echo '<script>window.location = "register.php"</script>';
            }
            if ($doc=="" || $nom == "" || $correo == "" || $cont=="" || $con2=="" || $cel=="" || $rol==""){
                echo '<script>alert("Existen datos vacios...")</script>';
            echo '<script>window.location = "register.php"</script>';
            }
            else{

            $inserto = $con->prepare("INSERT INTO usuarios(documento, nombre_completo, email, contraseña, telefono, id_estado_usuario, id_rol) 
            VALUES('$doc', '$nom', '$correo', '$cont_enc','$cel', '$estado', '$rol')");
            $inserto->execute();
            echo '<script>alert ("Registros Guardados") </script>';
            echo '<script>window.location = "login.php"</script>';
            }
        }   
    }

            
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="shortcut icon" href="css/img/logo_sinfondo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/stylelog.css">
</head>
<body>
    <div class ="contenido">
        <div class = "conten_form">
            <div class="form_infor">
                <a href="index.php"><img src="css/img/logo_sinfondo.png" alt="logo" class="logo"></a>
                
                <h1 class= "titulo" >Registro</h1>
                <form action = "" method = "post" enctype = "multipart/form-data">
                    <div class= "input_grupo">
                        <div class = "input_field">
                            <label for="doc"></label>
                            <i class="bi bi-person-vcard"></i>
                            <input type="number" name = "doc" id = "doc" placeholder = "Documento">
            
                        </div>
                        
                        <div class = "input_field">
                            <label for="nom"></label>
                            <i class="bi bi-card-heading"></i>
                            <input type="text" name = "nom" id = "nom" placeholder = "Nombre Completo">
                        </div>

                        <div class = "input_field">
                            <label for="correo"></label>
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" name = "correo" id = "correo" placeholder = "Correo">
                        </div>

                        <div class = "input_field">
                            <label for="con"></label>
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" name = "con" id = "con" placeholder = "Contraseña" value="" maxlength= "15" minlength = "8">
                        </div>

                        <div class = "input_field">
                            <label for="con2"></label>
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" name = "con2" id = "con2" placeholder = "Confirmar Contraseña" value="" maxlength= "15" minlength = "8">
                        </div>

                        <div class = "input_field">
                            <label for="cel"></label>
                            <i class="bi bi-telephone-fill"></i>
                            <input type="number" name = "cel" id = "cel" placeholder = "Telefono">
                        </div>


        
                        <div class = "input_field">
                        <label for="id_rol"></label>
                        <select name="id_rol"class="form-select" aria-label="Default select example">
                            <option value="">Selecciona el rol</option>
                            <?php
                                $sql = $con ->prepare("SELECT * FROM roles WHERE id_rol >1");
                                $sql->execute();
                                while ($fila=$sql->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value =" . $fila['id_rol'] . ">" . $fila['tip_rol'] ."</option>";
                                }
                            ?>

                        </select>
                        </div>
                    </div>
                    <div class = "btn-field">
                        <button type="submit" name = "enviar" id="enviar" value = "Guardar" class="btn btn-primary">Guardar</button>
                    </div>
                    <p>¿Ya tienes una cuenta?<a class="re"href="login.php">Inicia Sesion</a></p>
            
                </form>
            </div>
        </div>
    </div>



</body>
</html>