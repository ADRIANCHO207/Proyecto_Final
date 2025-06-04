<?php
session_start();
require_once('../../../conecct/conex.php');
require_once('../../../includes/validarsession.php');
$db = new Database();
$con = $db->conectar();

$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    header('Location: ../../login/login.php');
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
    $foto_perfil = $user['foto_perfil'] ?: '/proyecto/roles/usuario/css/img/perfil.jpg';
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['foto_perfil'] = $foto_perfil;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_licencia = trim($_POST['numero_licencia'] ?? '');
    $tipo_licencia = trim($_POST['tipo_licencia'] ?? '');
    $fecha_vencimiento = trim($_POST['fecha_vencimiento'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');
    $documento_usuario = $documento; // Usar directamente el documento de la sesión

    $errors = [];

    if (empty($numero_licencia) || !preg_match('/^[A-Z0-9]{5,15}$/', $numero_licencia)) {
        $errors[] = "El número de licencia es obligatorio y debe tener entre 5 y 15 caracteres (letras y números).";
    }
    if (empty($tipo_licencia)) {
        $errors[] = "El tipo de licencia es obligatorio.";
    }
    if (empty($fecha_vencimiento)) {
        $errors[] = "La fecha de vencimiento es obligatoria.";
    } else {
        $fecha_vencimiento_date = new DateTime($fecha_vencimiento);
        $hoy = new DateTime();
        if ($fecha_vencimiento_date < $hoy) {
            $errors[] = "La fecha de vencimiento no puede ser anterior a la fecha actual.";
        }
    }
    if (!empty($observaciones) && !preg_match('/^[a-zA-Z0-9\s.,!?\'-]{0,500}$/', $observaciones)) {
        $errors[] = "Las observaciones deben tener máximo 500 caracteres y solo letras, números y puntuación básica.";
    }

    if (empty($errors)) {
        $insert_licencia = $con->prepare("
            INSERT INTO licencias (numero_licencia, tipo_licencia, fecha_vencimiento, observaciones, documento_usuario)
            VALUES (:numero_licencia, :tipo_licencia, :fecha_vencimiento, :observaciones, :documento)
        ");
        $insert_licencia->execute([
            ':numero_licencia' => $numero_licencia,
            ':tipo_licencia' => $tipo_licencia,
            ':fecha_vencimiento' => $fecha_vencimiento,
            ':observaciones' => $observaciones,
            ':documento' => $documento_usuario
        ]);

        $_SESSION['success'] = 'Licencia registrada exitosamente.';
        header('Location: registrar_licencia.php');
        exit;
    } else {
        $_SESSION['errors'] = $errors;
        header('Location: registrar_licencia.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flotax AGC - Registrar Licencia</title>
    <link rel="stylesheet" href="../css/styles_licencia.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/scriptlicencia.js" defer></script>
</head>
<body>
<?php include('../header.php'); ?>

<div class="container">
    <form action="registrar_licencia.php" method="post" class="form-llantas" id="formulario">
        <h1>Registrar Licencia</h1>
        <p class="instructions">Completa los detalles de la licencia para recibir alertas de vencimiento.</p>
        
        <?php
        if (isset($_SESSION['errors'])) {
            echo '<div class="formulario_error" id="formulario_error"><b>Error:</b> ' . implode('<br>', $_SESSION['errors']) . '</div>';
            unset($_SESSION['errors']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="formulario_exito" id="formulario_exito">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <div class="input-gruop">
            <!-- Información del usuario -->
            <div class="input-subgroup user-info">
                <div class="input-box">
                    <label for="documento_usuario">Documento del Usuario:</label>
                    <div class="input_field_documento_usuario" id="grupo_documento_usuario">
                        <input type="text" name="documento_usuario" id="documento_usuario" value="<?php echo htmlspecialchars($documento); ?>" readonly>
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Campos principales -->
            <div class="input-subgroup">
                <div class="input-box">
                    <label for="numero_licencia">Número de Licencia:</label>
                    <div class="input_field_numero_licencia" id="grupo_numero_licencia">
                        <input type="text" name="numero_licencia" id="numero_licencia" required value="<?php echo htmlspecialchars($_POST['numero_licencia'] ?? ''); ?>">
                        <i class="bi bi-card-text"></i>
                    </div>
                    <p class="validacion_numero_licencia" id="validacion_numero_licencia">El número de licencia debe tener entre 5 y 15 caracteres (letras y números).</p>
                </div>

                <div class="input-box">
                    <label for="tipo_licencia">Tipo de Licencia:</label>
                    <div class="input_field_tipo_licencia" id="grupo_tipo_licencia">
                        <select name="tipo_licencia" id="tipo_licencia" required>
                            <option value="">Seleccionar Tipo</option>
                            <option value="A1" <?php echo (isset($_POST['tipo_licencia']) && $_POST['tipo_licencia'] === 'A1') ? 'selected' : ''; ?>>A1</option>
                            <option value="A2" <?php echo (isset($_POST['tipo_licencia']) && $_POST['tipo_licencia'] === 'A2') ? 'selected' : ''; ?>>A2</option>
                            <option value="B1" <?php echo (isset($_POST['tipo_licencia']) && $_POST['tipo_licencia'] === 'B1') ? 'selected' : ''; ?>>B1</option>
                            <option value="B2" <?php echo (isset($_POST['tipo_licencia']) && $_POST['tipo_licencia'] === 'B2') ? 'selected' : ''; ?>>B2</option>
                            <option value="C1" <?php echo (isset($_POST['tipo_licencia']) && $_POST['tipo_licencia'] === 'C1') ? 'selected' : ''; ?>>C1</option>
                        </select>
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <p class="validacion_tipo_licencia" id="validacion_tipo_licencia">El tipo de licencia es obligatorio.</p>
                </div>
            </div>

            <!-- Fecha y observaciones -->
            <div class="input-subgroup">
                <div class="input-box">
                    <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                    <div class="input_field_fecha_vencimiento" id="grupo_fecha_vencimiento">
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" required value="<?php echo htmlspecialchars($_POST['fecha_vencimiento'] ?? ''); ?>">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <p class="validacion_fecha_vencimiento" id="validacion_fecha_vencimiento">La fecha de vencimiento es obligatoria y debe ser futura.</p>
                </div>

                <div class="input-box">
                    <label for="observaciones">Observaciones (opcional):</label>
                    <div class="input_field_observaciones" id="grupo_observaciones">
                        <textarea name="observaciones" id="observaciones" rows="4"><?php echo htmlspecialchars($_POST['observaciones'] ?? ''); ?></textarea>
                        <i class="bi bi-pencil"></i>
                    </div>
                    <p class="validacion_observaciones" id="validacion_observaciones">Máximo 500 caracteres, solo letras, números y puntuación básica.</p>
                </div>
            </div>
        </div>

        <div class="btn-field">
            <button type="submit" class="btn btn-primary">Registrar Licencia</button>
        </div>
    </form>
</div>

</body>
</html>