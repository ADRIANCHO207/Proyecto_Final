<?php
session_start();
require_once('../../../conecct/conex.php');
require_once('../../../includes/validarsession.php');
$db = new Database();
$con = $db->conectar();

$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    header('Location: ../../../login/login.php');
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

// Fetch tipos de mantenimiento
$tipos_mantenimiento_query = $con->prepare("SELECT id_tipo_mantenimiento, descripcion FROM tipo_mantenimiento");
$tipos_mantenimiento_query->execute();
$tipos_mantenimiento = $tipos_mantenimiento_query->fetchAll(PDO::FETCH_ASSOC);

// Fetch clasificaciones de trabajo
$trabajos_query = $con->prepare("SELECT id, Trabajo, Precio FROM clasificacion_trabajo");
$trabajos_query->execute();
$trabajos = $trabajos_query->fetchAll(PDO::FETCH_ASSOC);

// Convertimos los trabajos a JSON para usarlos en JavaScript
$trabajos_json = json_encode($trabajos);

// Fetch mantenimientos
$mantenimientos_query = $con->prepare("
    SELECT m.*, v.placa, tm.descripcion AS tipo_mantenimiento,
           GROUP_CONCAT(c.Trabajo, ': $', d.subtotal) AS detalles_trabajos
    FROM mantenimiento m
    JOIN vehiculos v ON m.placa = v.placa
    JOIN tipo_mantenimiento tm ON m.id_tipo_mantenimiento = tm.id_tipo_mantenimiento
    LEFT JOIN detalles_mantenimiento_clasificacion d ON m.id_mantenimiento = d.id_mantenimiento
    LEFT JOIN clasificacion_trabajo c ON d.id_trabajo = c.id
    WHERE v.Documento = :documento
    GROUP BY m.id_mantenimiento
");
$mantenimientos_query->bindParam(':documento', $documento, PDO::PARAM_STR);
$mantenimientos_query->execute();
$mantenimientos = $mantenimientos_query->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = trim($_POST['placa'] ?? '');
    $id_tipo_mantenimiento = trim($_POST['id_tipo_mantenimiento'] ?? '');
    $fecha_programada = trim($_POST['fecha_programada'] ?? '');
    $fecha_realizada = trim($_POST['fecha_realizada'] ?? '');
    $kilometraje_actual = trim($_POST['kilometraje_actual'] ?? '');
    $proximo_cambio_km = trim($_POST['proximo_cambio_km'] ?? '');
    $proximo_cambio_fecha = trim($_POST['proximo_cambio_fecha'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');
    $trabajos_seleccionados = $_POST['trabajos'] ?? [];
    $cantidades = $_POST['cantidades'] ?? [];

    $errors = [];

    // Validaciones
    if (empty($placa)) $errors[] = "El vehículo es obligatorio.";
    if (empty($id_tipo_mantenimiento)) $errors[] = "El tipo de mantenimiento es obligatorio.";
    if (empty($fecha_programada)) $errors[] = "La fecha programada es obligatoria.";
    if (!empty($fecha_realizada)) {
        $date = new DateTime($fecha_realizada);
        if ($date > new DateTime()) $errors[] = "La fecha realizada no puede ser futura.";
    }
    if (!empty($kilometraje_actual) && (!is_numeric($kilometraje_actual) || $kilometraje_actual < 0)) {
        $errors[] = "El kilometraje actual debe ser un número positivo.";
    }
    if (!empty($proximo_cambio_km) && (!is_numeric($proximo_cambio_km) || $proximo_cambio_km < 0)) {
        $errors[] = "El próximo cambio (km) debe ser un número positivo.";
    }
    if (!empty($proximo_cambio_fecha)) {
        $date = new DateTime($proximo_cambio_fecha);
        if ($date < new DateTime()) $errors[] = "La fecha de próximo cambio no puede ser pasada.";
    }
    if (!empty($observaciones) && (strlen($observaciones) > 500 || !preg_match('/^[a-zA-Z0-9\s.,!?\'-]+$/', $observaciones))) {
        $errors[] = "Las observaciones deben tener máximo 500 caracteres y solo letras, números y puntuación básica.";
    }
    if (empty($trabajos_seleccionados)) $errors[] = "Debe seleccionar al menos un trabajo.";

    if (empty($errors)) {
        // Insertar mantenimiento
        $insert_mantenimiento = $con->prepare("
            INSERT INTO mantenimiento (placa, id_tipo_mantenimiento, fecha_programada, fecha_realizada, kilometraje_actual, proximo_cambio_km, proximo_cambio_fecha, observaciones, documento_usuario)
            VALUES (:placa, :id_tipo_mantenimiento, :fecha_programada, :fecha_realizada, :kilometraje_actual, :proximo_cambio_km, :proximo_cambio_fecha, :observaciones, :documento)
        ");
        $insert_mantenimiento->execute([
            ':placa' => $placa,
            ':id_tipo_mantenimiento' => $id_tipo_mantenimiento,
            ':fecha_programada' => $fecha_programada,
            ':fecha_realizada' => $fecha_realizada ?: null,
            ':kilometraje_actual' => $kilometraje_actual ?: null,
            ':proximo_cambio_km' => $proximo_cambio_km ?: null,
            ':proximo_cambio_fecha' => $proximo_cambio_fecha ?: null,
            ':observaciones' => $observaciones,
            ':documento' => $documento
        ]);

        $id_mantenimiento = $con->lastInsertId();

        // Insertar detalles de trabajos
        foreach ($trabajos_seleccionados as $index => $id_trabajo) {
            $cantidad = $cantidades[$index] ?? 1;
            $trabajo_query = $con->prepare("SELECT Precio FROM clasificacion_trabajo WHERE id = :id_trabajo");
            $trabajo_query->execute([':id_trabajo' => $id_trabajo]);
            $trabajo = $trabajo_query->fetch(PDO::FETCH_ASSOC);
            $subtotal = $trabajo['Precio'] * $cantidad;

            $insert_detalle = $con->prepare("
                INSERT INTO detalles_mantenimiento_clasificacion (id_mantenimiento, id_trabajo, cantidad, subtotal)
                VALUES (:id_mantenimiento, :id_trabajo, :cantidad, :subtotal)
            ");
            $insert_detalle->execute([
                ':id_mantenimiento' => $id_mantenimiento,
                ':id_trabajo' => $id_trabajo,
                ':cantidad' => $cantidad,
                ':subtotal' => $subtotal
            ]);
        }

        $_SESSION['success'] = 'Mantenimiento registrado exitosamente.';
        header('Location: gestionar_mantenimiento.php');
        exit;
    } else {
        $_SESSION['errors'] = $errors;
        header('Location: gestionar_mantenimiento.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flotax AGC - Mantenimiento General</title>
    <link rel="stylesheet" href="../css/styles_mantenimiento.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/scriptmantenimiento.js" defer></script>
</head>
<body onload="formulario.placa.focus()">
<?php include('../header.php'); ?>

<div class="container">
    <form action="gestionar_mantenimiento.php" method="post" class="form-llantas" id="formulario">
        <h1>Gestión de Mantenimientos</h1>
        <p class="instructions">Selecciona el vehículo y completa los detalles del mantenimiento, incluyendo fechas, kilometraje y trabajos realizados. Luego, presiona "Registrar Mantenimiento" para guardar la información.</p>
        
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
            <!-- Primer grupo de 3 campos -->
            <div class="input-subgroup">
                <div class="input-box">
                    <label for="placa">Vehículo:</label>
                    <div class="input_field_placa" id="grupo_placa">
                        <select name="placa" id="placa" required>
                            <option value="">Seleccionar Vehículo</option>
                            <?php
                            $vehiculos_query = $con->prepare("SELECT placa FROM vehiculos WHERE Documento = :documento");
                            $vehiculos_query->bindParam(':documento', $documento, PDO::PARAM_STR);
                            $vehiculos_query->execute();
                            foreach ($vehiculos_query->fetchAll(PDO::FETCH_ASSOC) as $vehiculo) {
                                $selected = (isset($_POST['placa']) && $_POST['placa'] === $vehiculo['placa']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($vehiculo['placa']) . '" ' . $selected . '>' . htmlspecialchars($vehiculo['placa']) . '</option>';
                            }
                            ?>
                        </select>
                        <i class="bi bi-car-front"></i>
                    </div>
                    <p class="validacion_placa" id="validacion_placa">Seleccione un vehículo.</p>
                </div>

                <div class="input-box">
                    <label for="id_tipo_mantenimiento">Tipo de Mantenimiento:</label>
                    <div class="input_field_id_tipo_mantenimiento" id="grupo_id_tipo_mantenimiento">
                        <select name="id_tipo_mantenimiento" id="id_tipo_mantenimiento" required>
                            <option value="">Seleccionar Tipo</option>
                            <?php foreach ($tipos_mantenimiento as $tipo): ?>
                                <option value="<?php echo htmlspecialchars($tipo['id_tipo_mantenimiento']); ?>" <?php echo (isset($_POST['id_tipo_mantenimiento']) && $_POST['id_tipo_mantenimiento'] === $tipo['id_tipo_mantenimiento']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tipo['descripcion']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="bi bi-wrench"></i>
                    </div>
                    <p class="validacion_id_tipo_mantenimiento" id="validacion_id_tipo_mantenimiento">Seleccione un tipo de mantenimiento.</p>
                </div>

                <div class="input-box">
                    <label for="fecha_programada">Fecha Programada:</label>
                    <div class="input_field_fecha_programada" id="grupo_fecha_programada">
                        <input type="date" name="fecha_programada" id="fecha_programada" required value="<?php echo htmlspecialchars($_POST['fecha_programada'] ?? ''); ?>">
                        <i class="bi bi-calendar"></i>
                    </div>
                    <p class="validacion_fecha_programada" id="validacion_fecha_programada">Seleccione una fecha válida.</p>
                </div>
            </div>

            <!-- Segundo grupo de 3 campos -->
            <div class="input-subgroup">
                <div class="input-box">
                    <label for="fecha_realizada">Fecha Realizada (opcional):</label>
                    <div class="input_field_fecha_realizada" id="grupo_fecha_realizada">
                        <input type="date" name="fecha_realizada" id="fecha_realizada" value="<?php echo htmlspecialchars($_POST['fecha_realizada'] ?? ''); ?>">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <p class="validacion_fecha_realizada" id="validacion_fecha_realizada">Fecha no puede ser futura.</p>
                </div>

                <div class="input-box">
                    <label for="kilometraje_actual">Kilometraje Actual:</label>
                    <div class="input_field_kilometraje_actual" id="grupo_kilometraje_actual">
                        <input type="number" name="kilometraje_actual" id="kilometraje_actual" value="<?php echo htmlspecialchars($_POST['kilometraje_actual'] ?? ''); ?>">
                        <i class="bi bi-speedometer"></i>
                    </div>
                    <p class="validacion_kilometraje_actual" id="validacion_kilometraje_actual">Ingrese un número positivo.</p>
                </div>

                <div class="input-box">
                    <label for="proximo_cambio_km">Próximo Mantenimiento (km):</label>
                    <div class="input_field_proximo_cambio_km" id="grupo_proximo_cambio_km">
                        <input type="number" name="proximo_cambio_km" id="proximo_cambio_km" value="<?php echo htmlspecialchars($_POST['proximo_cambio_km'] ?? ''); ?>">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <p class="validacion_proximo_cambio_km" id="validacion_proximo_cambio_km">Ingrese un número positivo.</p>
                </div>
            </div>

            <!-- Tercer grupo de 2 campos -->
            <div class="input-subgroup">
                <div class="input-box">
                    <label for="proximo_cambio_fecha">Próximo Mantenimiento (Fecha):</label>
                    <div class="input_field_proximo_cambio_fecha" id="grupo_proximo_cambio_fecha">
                        <input type="date" name="proximo_cambio_fecha" id="proximo_cambio_fecha" value="<?php echo htmlspecialchars($_POST['proximo_cambio_fecha'] ?? ''); ?>">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <p class="validacion_proximo_cambio_fecha" id="validacion_proximo_cambio_fecha">Fecha no puede ser pasada.</p>
                </div>

                <div class="input-box" style="flex: 1 1 65%;">
                    <label for="observaciones">Observaciones:</label>
                    <div class="input_field_observaciones" id="grupo_observaciones">
                        <textarea name="observaciones" id="observaciones" rows="4"><?php echo htmlspecialchars($_POST['observaciones'] ?? ''); ?></textarea>
                        <i class="bi bi-pencil"></i>
                    </div>
                    <p class="validacion_observaciones" id="validacion_observaciones">Máximo 500 caracteres, solo letras, números y puntuación básica.</p>
                </div>
            </div>
        
        </div>

        <div class="btn-field">
            <button type="submit" class="btn btn-primary">Registrar Mantenimiento</button>
        </div>
    </form>
</div>

</body>
</html>