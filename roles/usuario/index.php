<?php
session_start();
require_once('../../conecct/conex.php');
include '../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();

// Check for documento in session
$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    header('Location: ../../login.php');
    exit;
}

// Fetch nombre_completo and foto_perfil if not in session
$nombre_completo = $_SESSION['nombre_completo'] ?? null;
$foto_perfil = $_SESSION['foto_perfil'] ?? null;
if (!$nombre_completo || !$foto_perfil) {
    $user_query = $con->prepare("SELECT nombre_completo, foto_perfil FROM usuarios WHERE documento = :documento");
    $user_query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $user_query->execute();
    $user = $user_query->fetch(PDO::FETCH_ASSOC);
    $nombre_completo = $user['nombre_completo'] ?? 'Usuario';
    $foto_perfil = $user['foto_perfil'] ?: 'proyecto/roles/usuario/css/img/perfil.jpg';
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
    <link rel="shortcut icon" href="../../css/img/logo_sinfondo.png">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php
include('header.php')
?>

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

<div class="alertas">
    <h1>Mis Alertas</h1>
    <div class="alertas-grid">
        <a href="vehiculos/gestionar_documento.php?tipo=SOAT" class="boton">
            <i class="bi bi-shield-check"></i> SOAT
        </a>
        <a href="vehiculos/gestionar_documento.php?tipo=Tecnomecanica" class="boton">
            <i class="bi bi-tools"></i> Tecnomecánica
        </a>
        <a href="vehiculos/gestionar_documento.php?tipo=Licencia_Conduccion" class="boton">
            <i class="bi bi-card-heading"></i> Licencia de Conducción
        </a>
        <a href="vehiculos/gestionar_pico_placa.php" class="boton">
            <i class="bi bi-sign-stop"></i> Pico y Placa
        </a>
        <a href="vehiculos/gestionar_llantas.php" class="boton">
            <i class="bi bi-circle"></i> Llantas
        </a>
        <a href="vehiculos/gestionar_mantenimiento.php" class="boton">
            <i class="bi bi-oil"></i> Mantenimiento y Aceite
        </a>
    </div>
</div>

<div class="garage">
    <div class="garage-content">
        <h2>Mis Vehículos</h2>
        <form action="vehiculos/listar/listar.php" method="get">
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

<div class="sidebar">
    <a href="../../includes/salir.php" class="logout" title="Cerrar Sesión">
        <i class="bi bi-box-arrow-right"></i>
    </a>
</div>


</body>
</html>