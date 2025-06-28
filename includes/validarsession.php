<?php
if (!isset($_SESSION['documento'])) {

    unset($_SESSION['documento']);
    unset($_SESSION['tipo']);
    unset($_SESSION['estado']);
    $_SESSION = array();
    session_destroy();
    session_write_close();

    // Definir BASE_URL si no está definida
    if (!defined('BASE_URL')) {
        if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            define('BASE_URL', '/Proyecto');
        } else {
            define('BASE_URL', ''); // O '/subcarpeta' si tu proyecto está en una subcarpeta en el hosting
        }
    }

    echo "<script>alert('INGRESE CREDENCIALES DE LOGIN');</script>";
    echo "<script>window.location = '" . BASE_URL . "/login/login';</script>";
    exit();
}
?>