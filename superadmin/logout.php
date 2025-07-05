<?php
session_start();

// Destruir todas las variables de sesión de superadmin
unset($_SESSION['superadmin_documento']);
unset($_SESSION['superadmin_nombre']);
unset($_SESSION['superadmin_email']);
unset($_SESSION['superadmin_rol']);
unset($_SESSION['superadmin_logged']);

// Destruir la sesión completamente
session_destroy();

// Redirigir al login
header('Location: login.php');
exit;
?>