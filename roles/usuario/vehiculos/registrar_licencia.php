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

// fetch categorias
$sql_categoria = $con->prepare("SELECT id_categoria, nombre_categoria FROM categoria_licencia;");
$sql_categoria->execute();
$categorias = $sql_categoria->fetchAll(PDO::FETCH_ASSOC);


// fetch categorias
$sql_servicio = $con->prepare("SELECT id_servicio, nombre_servicios FROM servicios_licencias");
$sql_servicio->execute();
$servicios = $sql_servicio->fetchAll(PDO::FETCH_ASSOC);


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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flotax AGC - Registrar Licencia</title>
    <link rel="stylesheet" href="../css/styles_licencia.css">
    <link rel="shortcut icon" href="../../../css/img/logo_sinfondo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include('../header.php'); ?>

    <div class="container">
        <form action="" method="post" class="form-llantas" id="formulario">
            <h1>Registrar Licencia</h1>
            <p class="instructions">Completa los detalles de la licencia para recibir alertas de vencimiento.</p>
            
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

                <!-- Fecha y observaciones -->
                <div class="input-subgroup">
                    <div class="input-subgroup">
                        <div class="input-box">
                            <label for="categoria">Categoria*:</label>
                            <div class="input_field_categoria" id="grupo_categoria">
                                <select name="categoria" id="categoria" required>
                                    <option value="">Seleccionar Categoria</option>
                                    <?php foreach ($categorias as $row) { ?>
                                        <option value="<?php echo htmlspecialchars($row['id_categoria']); ?>">
                                            <?php echo htmlspecialchars($row['nombre_categoria']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <i class="bi bi-file-earmark-person"></i>
                            </div>
                            <p class="validacion_categoria" id="validacion_categoria">la categoria de licencia es obligatorio.</p>
                        </div>
                    </div>
                    <div class="input-box">
                        <label for="fecha_expedicion">Fecha de expedicion*:</label>
                        <div class="input_field_fecha_expedicion" id="grupo_fecha_expedicion">
                            <input type="date" name="fecha_expedicion" id="fecha_expedicion" required value="<?php echo htmlspecialchars($_POST['fecha_vencimiento'] ?? ''); ?>">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <p class="validacion_fecha_expedicion" id="validacion_fecha_expedicion">La fecha de expedicion es obligatoria y no puede ser futura.</p>
                    </div>
                    <div class="input-box">
                        <label for="fecha_vencimiento">Fecha de Vencimiento*:</label>
                        <div class="input_field_fecha_vencimiento" id="grupo_fecha_vencimiento">
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" required value="<?php echo htmlspecialchars($_POST['fecha_vencimiento'] ?? ''); ?>">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <p class="validacion_fecha_vencimiento" id="validacion_fecha_vencimiento">La fecha de vencimiento es obligatoria y debe ser futura.</p>
                    </div>

                    <div class="input-box">
                            <label for="tipo_servicio">Tipo de servicio*:</label>
                            <div class="input_field_tipo_servicio" id="grupo_tipo_servicio">
                                <select name="tipo_servicio" id="tipo_servicio" required>
                                    <option value="">Seleccionar servicio</option>
                                    <?php foreach ($servicios as $row) { ?>
                                        <option value="<?php echo htmlspecialchars($row['id_servicio']); ?>">
                                            <?php echo htmlspecialchars($row['nombre_servicios']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            <p class="validacion_tipo_servicio" id="validacion_tipo_servicio">Selecciona servicio de licencia.</p>
                        </div>
                    </div>

                    <div class="input-box">
                        <label for="observaciones">Restricciones del conductor (opcional):</label>
                        <div class="input_field_observaciones" id="grupo_observaciones">
                            <textarea name="observaciones" id="observaciones" rows="4"><?php echo htmlspecialchars($_POST['observaciones'] ?? ''); ?></textarea>
                            <i class="bi bi-pencil"></i>
                        </div>
                        <p class="validacion_observaciones" id="validacion_observaciones">Máximo 500 caracteres, solo letras, números y puntuación básica.</p>
                    </div>
                
                </div>
            
                <!-- Error general -->
                <div>
                    <p class="formulario_error" id="formulario_error"><b>Error:</b> Por favor complete todos los campos correctamente.</p>
                </div>
                <div class="btn-field">
                    <button type="submit" class="btn btn-primary">Registrar Licencia</button>
                </div>
                <!-- Mensaje de éxito -->   
                <p class="formulario_exito" id="formulario_exito">Licencia registrado correctamente.</p>
            </div>
        </form>
    </div>
    <script src="../js/scriptlicencia.js"></script>
    <?php
      include('../../../includes/auto_logout_modal.php');
    ?>
</body>
</html>