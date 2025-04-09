<?php
if(!isset($_SESSION['documento'])){

    unset($_SESSION['documento']);
    unset($_SESSION['tipo']);
    unset($_SESSION['estado']);
    $_SESSION = array();
    session_destroy();
    session_write_close();
    echo "<script>alert ('INGRESE CREDENCIALES DE LOGIN')</script>";
    echo "<script>window.location = '../../login.php' </script>";
    exit();

}
?>