<?php
// Inicia la sesión y verifica el acceso del usuario
session_start();
require_once('../../conecct/conex.php');
include '../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();

// Verificación de inicio de sesión
$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    header('Location: ../../login.php');
    exit;
}

// Obtiene nombre completo y foto de perfil si no están en sesión
$nombre_completo = $_SESSION['nombre_completo'] ?? null;
$foto_perfil = $_SESSION['foto_perfil'] ?? null;
if (!$nombre_completo || !$foto_perfil) {
    $user_query = $con->prepare("SELECT nombre_completo, foto_perfil FROM usuarios WHERE documento = :documento");
    $user_query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $user_query->execute();
    $user = $user_query->fetch(PDO::FETCH_ASSOC);
    $nombre_completo = $user['nombre_completo'] ?? 'Usuario';
    $foto_perfil = $user['foto_perfil'] ?: '/roles/usuario/css/img/perfil.jpg';
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['foto_perfil'] = $foto_perfil;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flotax AGC - Inicio</title>
    <!-- Favicon y estilos principales -->
    <link rel="shortcut icon" href="css/img/logo_sinfondo.png">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php
    // Incluye el header del usuario
    include('header.php');
?>

<!-- Notificaciones de éxito o error -->
<?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
<div class="notification">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Sección de accesos rápidos a alertas de documentos y mantenimientos -->
<div class="alertas">
    <h1>Mis Alertas</h1>
    <div class="alertas-grid">
        <a href="vehiculos/registrar_soat" class="boton">
            <i class="bi bi-shield-check"></i> SOAT
        </a>
        <a href="vehiculos/registrar_tecnomecanica" class="boton">
            <i class="bi bi-tools"></i> Tecnomecánica
        </a>
        <a href="vehiculos/registrar_licencia" class="boton">
            <i class="bi bi-card-heading"></i> Licencia de Conducción
        </a>
        <a href="vehiculos/pico_placa" class="boton">
            <i class="bi bi-sign-stop"></i> Pico y Placa
        </a>
        <a href="vehiculos/registrar_llantas" class="boton">
            <i class="bi bi-circle"></i> Llantas
        </a>
        <a href="vehiculos/registrar_mantenimiento" class="boton">
            <i class="bi bi-gear"></i> Mantenimiento y Aceite
        </a>
        <a href="vehiculos/multas" class="boton">
            <i class="bi bi-receipt-cutoff"></i> Multas
        </a>
    </div>
</div>

<!-- Sección de selección y visualización de vehículos del usuario -->
<div class="garage">
    <div class="garage-content">
        <h2>Mis Vehículos</h2>
        <form action="vehiculos/listar/listar" method="get">
            <div class="form-group">
                <select name="vehiculo">
                    <option value="">Seleccionar Vehículo</option>
                    <?php
                    $vehiculos_query = $con->prepare("SELECT placa FROM vehiculos WHERE Documento = :documento");
                    $vehiculos_query->bindParam(':documento', $documento, PDO::PARAM_STR);
                    $vehiculos_query->execute();
                    $vehiculos = $vehiculos_query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($vehiculos as $vehiculo) {
                        echo '<option value="' . htmlspecialchars($vehiculo['placa']) . '">' . htmlspecialchars($vehiculo['placa']) . '</option>';
                    }
                    ?>
                </select>
                <button type="submit">Mostrar</button>
            </div>
        </form>
    </div>
</div>

<!-- Botón de cierre de sesión -->
<div class="sidebar">
    <a href="../../includes/salir" class="logout" title="Cerrar Sesión">
        <i class="bi bi-box-arrow-right"></i>
    </a>
</div>

<?php
    // Incluye el modal de cierre de sesión automático
    include('../../includes/auto_logout_modal.php');
?>


</body>
</html>