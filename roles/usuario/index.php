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
    $foto_perfil = $user['foto_perfil'] ?: 'css/img/perfil.jpg';
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

<div id="profileModal" class="modal">
    <div class="modal-content">
        <button class="close" onclick="closeModal()">Cerrar</button>
        <h2>Información del Usuario</h2>
        <?php
        // Usar la misma lógica que en el header: simplemente mostrar la imagen con cache-busting
        $imagePath = htmlspecialchars($foto_perfil) . '?v=' . time();
        ?>
        <img src="<?php echo $imagePath; ?>" alt="Foto de Perfil" class="usu_imagen" style="max-width: 100%; height: auto;">
        <?php if ($foto_perfil === 'css/img/perfil.jpg'): ?>
            <p>Debug: Usando imagen predeterminada.</p>
        <?php endif; ?>
        <form action="actualizar_foto.php" method="post" enctype="multipart/form-data">
            <label for="foto_perfil">Cambiar Foto de Perfil:</label>
            <p class="upload-instructions">Formatos: JPEG, PNG, GIF. Máximo 5MB. Recomendado: 512x512 píxeles.</p>
            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/jpeg,image/png,image/gif">
            <button type="submit" class="boton">Actualizar Foto</button>
        </form>
        <form action="actualizar_foto.php" method="post">
            <input type="hidden" name="reset_image" value="1">
            <button type="submit" class="boton">Restablecer Imagen</button>
        </form>
    </div>
</div>

<footer class="footer">
    <div class="footer-content">
        <p>© 2024 Flotax AGC. Todos los derechos reservados.</p>
        <div class="social-media">
            <a href="https://facebook.com" target="_blank" class="social-icon"><i class="bi bi-facebook"></i></a>
            <a href="https://twitter.com" target="_blank" class="social-icon"><i class="bi bi-twitter"></i></a>
            <a href="https://instagram.com" target="_blank" class="social-icon"><i class="bi bi-instagram"></i></a>
            <a href="https://wa.me/1234567890" target="_blank" class="social-icon"><i class="bi bi-whatsapp"></i></a>
        </div>
    </div>
</footer>

<script>
function openModal() {
    document.getElementById('profileModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('profileModal').style.display = 'none';
}
</script>

</body>
</html>