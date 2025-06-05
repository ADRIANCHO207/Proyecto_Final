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
    <form action="guardar_mantenimiento.php" method="post" class="form-llantas" id="formulario">
        <h1>Gestión de Mantenimientos</h1>
        <p class="instructions">Selecciona el vehículo y completa los detalles del mantenimiento, incluyendo fechas, kilometraje y trabajos realizados. Luego, presiona "Registrar Mantenimiento" para guardar la información.</p>
        
        <?php
        if (isset($_SESSION['form_errors'])) {
            echo '<div class="formulario_error" id="formulario_error"><b>Error:</b> ' . implode('<br>', $_SESSION['form_errors']) . '</div>';
            unset($_SESSION['form_errors']);
        }
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo '<div class="formulario_exito" id="formulario_exito">Mantenimiento registrado correctamente.</div>';
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
                    <label for="tipo_mantenimiento">Tipo de Mantenimiento:</label>
                    <div class="input_field_id_tipo_mantenimiento" id="grupo_id_tipo_mantenimiento">
                        <select name="tipo_mantenimiento" id="tipo_mantenimiento" required>
                            <option value="">Seleccionar Tipo</option>
                            <?php foreach ($tipos_mantenimiento as $tipo): ?>
                                <option value="<?php echo htmlspecialchars($tipo['id_tipo_mantenimiento']); ?>" <?php echo (isset($_POST['tipo_mantenimiento']) && $_POST['tipo_mantenimiento'] === $tipo['id_tipo_mantenimiento']) ? 'selected' : ''; ?>>
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
                    <p class="validacion_observaciones" id="validacion_observaciones">Máximo de 500 caracteres, solo letras, números y puntuación básica.</p>
                </div>
            </div>

           
        </div>

        <div class="btn-field">
            <button type="submit" class="btn btn-primary">Registrar Mantenimiento</button>
        </div>
    </form>
</div>

|<!-- Modal de advertencia -->
<div id="modalInactividad" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:9999; display:flex; align-items:center; justify-content:center;">
  <div style="background: rgba(255, 255, 255, 0.95); padding: 25px; border-radius: 12px; text-align: center; max-width: 320px; width: 90%; box-shadow: 0 4px 20px rgba(0,0,0,0.3); font-family: 'Poppins', sans-serif;">
    <h3 style="margin-bottom: 10px; font-weight: 600;">¿Sigues ahí?</h3>
    <p style="margin-bottom: 20px; font-size: 15px;">Por inactividad, la sesión se cerrará en <span id="tiempoRestante" style="font-weight: bold;">10</span> segundos.</p>
    <button onclick="cancelarCierre()" class="btn-grad">Seguir aquí</button>
  </div>
</div>

<style>
  .btn-grad {
    padding: 10px 20px;
    background: linear-gradient(270deg, #00c6ff, #0072ff, #00c6ff);
    background-size: 600% 600%;
    border: none;
    border-radius: 25px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 0 15px rgba(0, 114, 255, 0.4);
    animation: gradientMove 5s ease infinite;
    transition: transform 0.2s;
  }

  .btn-grad:hover {
    transform: scale(1.05);
    box-shadow: 0 0 20px rgba(0, 114, 255, 0.7);
  }

  @keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }
</style>

<script>
  let tiempoInactividad = 30000; // 30 segundos
  let advertenciaTiempo = 10000; // Mostrar advertencia a los 20s
  let temporizadorInactividad;
  let temporizadorAdvertencia;
  let tiempoRestante = 10;
  let cuentaRegresiva;

  function reiniciarTemporizador() {
    clearTimeout(temporizadorInactividad);
    clearTimeout(temporizadorAdvertencia);
    cerrarModal();

    temporizadorAdvertencia = setTimeout(() => {
      mostrarModal();
    }, tiempoInactividad - advertenciaTiempo);

    temporizadorInactividad = setTimeout(() => {
      window.location.href = "../../../includes/salir.php";
    }, tiempoInactividad);
  }

  function mostrarModal() {
    document.getElementById("modalInactividad").style.display = "flex";
    tiempoRestante = 10;
    document.getElementById("tiempoRestante").textContent = tiempoRestante;
    cuentaRegresiva = setInterval(() => {
      tiempoRestante--;
      document.getElementById("tiempoRestante").textContent = tiempoRestante;
      if (tiempoRestante <= 0) {
        clearInterval(cuentaRegresiva);
      }
    }, 1000);
  }

  function cerrarModal() {
    document.getElementById("modalInactividad").style.display = "none";
    clearInterval(cuentaRegresiva);
  }

  function cancelarCierre() {
    reiniciarTemporizador();
  }

  window.onload = reiniciarTemporizador;
  document.onmousemove = reiniciarTemporizador;
  document.onkeypress = reiniciarTemporizador;
  document.onscroll = reiniciarTemporizador;
  document.onclick = reiniciarTemporizador;
</script>
</div>
</body>
</html>