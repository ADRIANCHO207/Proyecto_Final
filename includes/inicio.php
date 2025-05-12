<?php

session_start();
require_once('../conecct/conex.php');
// include 'validarsesion.php';
$db = new Database();
$con = $db->conectar();

?>
<?php
if (isset($_POST['log'])) {

<<<<<<< HEAD
    $doc = $_POST['doc'];
    $nom = $_POST['nom'];
    
    $cont_descrip = htmlentities(addslashes($_POST['passw']));
        
    if ($doc == '' || $nom == '' || $_POST['passw'] ==''){
        echo '<script>alert("Documento, Nombre Completo o Contraseña estan vacios...")</script>';
        echo '<script>window.location = "../login/login.php"</script>';
    }
=======
    $doc = $_POST['doc'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $passw = $_POST['passw'] ?? '';
>>>>>>> a7018a5 (apis y admin)



    $sql = $con->prepare("SELECT * FROM usuarios WHERE documento = ?");
    $sql->execute([$doc]);
    $fila = $sql->fetch();

<<<<<<< HEAD
    if ($doc != $fila['documento']){
        echo '<script>alert("Documento no Encontrado...")</script>';
        echo '<script>window.location = "../login/login.php"</script>';

    }

    if ($nom != $fila['nombre_completo']){
        echo '<script>alert("Nombre incorrecto...")</script>';
        echo '<script>window.location = "../login/login.php"</script>';


    }else{


    

    // if ($fila) {
    //     if ($fila && password_verify($cont_descrip, $fila['contraseña'])) {
    //         echo "Contraseña verificada correctamente.";
    //         exit();
    //     } else {
    //         echo "Contraseña incorrecta.";
    //         exit();
    //     }
    // } else {
    //     echo "Usuario no encontrado.";
    //     exit();
    // }
    if ($fila) {
        if ($fila && password_verify($cont_descrip, $fila['contraseña'])&& ($fila['id_estado_usuario']== 1)) {
            $_SESSION['documento'] = $fila ['documento'];
            $_SESSION['tipo'] = $fila['id_rol'];
            $_SESSION['estado'] = $fila['id_estado_usuario'];

            if ($_SESSION['tipo'] == 1){
                header("location: ../roles/admin/index.php");
                exit();
            }

            if ($_SESSION['tipo'] == 2){
                header("location: ../roles/usuario/index.php");
                exit();
            }

            
        } 
        else {
            echo '<script>alert("Contraseña Incorrecta...")</script>';
            echo '<script>window.location = "../login/login.php"</script>';
        }
    } 
    else {
        echo '<script>alert("Documento No Encontrado.")</script>';
        echo '<script>window.location = "../login/login.php"</script>';
=======
    if (!$fila) {
        echo "Documento no encontrado";
        exit();
    }

    if ($nom != $fila['nombre_completo']) {
        echo "Nombre incorrecto";
        exit();
>>>>>>> a7018a5 (apis y admin)
    }

    if (!password_verify($passw, $fila['password'])) {
        echo "Contraseña incorrecta";
        exit();
    }

    if ($fila['id_estado_usuario'] != 1) {
        echo "Usuario inactivo";
        exit();
    }

    $_SESSION['documento'] = $fila['documento'];
    $_SESSION['tipo'] = $fila['id_rol'];

    echo $fila['id_rol'] == 1 ? "OK_ADMIN" : "OK_USUARIO";

}

?>