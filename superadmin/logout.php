<?php
// Línea 1: Apertura de etiqueta PHP para iniciar el script

session_start();
// Línea 2: Inicia o reanuda la sesión actual del usuario

// Destruir todas las variables de sesión de superadmin
// Línea 4: Comentario explicativo sobre la limpieza de variables de sesión

unset($_SESSION['superadmin_documento']);
// Línea 5: Elimina la variable de sesión que almacena el documento del superadmin

unset($_SESSION['superadmin_nombre']);
// Línea 6: Elimina la variable de sesión que almacena el nombre del superadmin

unset($_SESSION['superadmin_email']);
// Línea 7: Elimina la variable de sesión que almacena el email del superadmin

unset($_SESSION['superadmin_rol']);
// Línea 8: Elimina la variable de sesión que almacena el rol del superadmin

unset($_SESSION['superadmin_logged']);
// Línea 9: Elimina la variable de sesión que indica si el superadmin está logueado

// Destruir la sesión completamente
// Línea 11: Comentario explicativo sobre la destrucción total de la sesión

session_destroy();
// Línea 12: Destruye completamente la sesión actual, eliminando todos los datos

// Redirigir al login
// Línea 14: Comentario explicativo sobre la redirección

header('Location: login.php');
// Línea 15: Envía una cabecera HTTP para redirigir al usuario a la página de login

exit;
// Línea 16: Termina la ejecución del script inmediatamente después de la redirección

?>
