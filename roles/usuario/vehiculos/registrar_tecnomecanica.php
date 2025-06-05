<?php
  session_start();
  require_once '../../../conecct/conex.php';
  include '../../../includes/validarsession.php';
  $database = new Database();
  $con = $database->conectar();

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
      $foto_perfil = $user['foto_perfil'] ?: '/proyecto/roles/usuario/css/img/perfil.jpg';
      $_SESSION['nombre_completo'] = $nombre_completo;
      $_SESSION['foto_perfil'] = $foto_perfil;
  }

  // Fetch vehicles for the user
  $query_vehiculos = "SELECT placa FROM vehiculos WHERE Documento = :documento";
  $stmt_vehiculos = $con->prepare($query_vehiculos);
  $stmt_vehiculos->bindParam(':documento', $documento);
  $stmt_vehiculos->execute();
  $vehiculos = $stmt_vehiculos->fetchAll(PDO::FETCH_ASSOC);

  // fetch estado
  $sql_estado = $con->prepare("SELECT id_stado, soat_est FROM estado_soat");
  $sql_estado->execute();
  $estado = $sql_estado->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Técnico-Mecánica</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles_tecno.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body onload="formtecnico.placa.focus()">
  <?php 
    include('../header.php') 
  ?>

  <div class="contenedor">
      <form id="formtecnico" class="formtecnico" method="post" action="">
          <div class="titulo">
              <h1>Registrar Técnico-Mecánica</h1>
              <p class="instructions">Completa los detalles de la revisión para recibir alertas de vencimiento.</p>
          </div>

          <!-- Placa -->
          <div class="input_field_placa" id="grupo_placa">
              <label for="placa">Placa del Vehículo:*</label>
              <i class="bi bi-car-front"></i>
              <select id="placa" name="placa" required>
                  <option value="">Seleccione una placa</option>
                  <?php foreach ($vehiculos as $row) { ?>
                      <option value="<?php echo htmlspecialchars($row['placa']); ?>">
                          <?php echo htmlspecialchars($row['placa']); ?>
                      </option>
                  <?php } ?>
              </select>
          </div>
          <div class="formulario_error_placa" id="formulario_correcto_placa">
              <p class="validacion">Seleccione una placa válida.</p>
          </div>

          <!-- Centro de Revisión -->
          <div class="input_field_centro" id="grupo_centro">
              <label for="centro">Centro de Revisión:*</label>
              <i class="bi bi-buildings-fill"></i>
              <input type="text" id="centro" name="centro" required>
          </div>
          <div class="formulario_error_centro" id="formulario_correcto_centro">
              <p class="validacion">Ingrese un centro válido.</p>
          </div>

          <!-- Fecha de Expedición -->
          <div class="input_field_expedicion" id="grupo_expedicion">
              <label for="fechaExpedicion">Fecha de Expedición:*</label>
              <i class="bi bi-calendar-check"></i>
              <input type="date" id="fechaExpedicion" name="fechaExpedicion" required>
          </div>
          <div class="formulario_error_expedicion" id="formulario_correcto_expedicion">
              <p class="validacion" id="validacion1">Seleccione una fecha válida.</p>
          </div>

          <!-- Fecha de Vencimiento -->
          <div class="input_field_vencimiento" id="grupo_vencimiento">
              <label for="fechaVencimiento">Fecha de Vencimiento:*</label>
              <i class="bi bi-calendar-x"></i>
              <input type="date" id="fechaVencimiento" name="fechaVencimiento" required>
          </div>
          <div class="formulario_error_vencimiento" id="formulario_correcto_vencimiento">
              <p class="validacion">Seleccione una fecha válida.</p>
          </div>

          <!-- estado tecno -->
          <div class="input_field_estado" id="grupo_estado">
              <label for="estado">Estado:*</label>
              <i class="bi bi-check-circle-fill"></i>
              <select id="estado" name="estado" required>
                  <option value="">Seleccione un estado</option>
                  <?php foreach ($estado as $row) { ?>
                      <option value="<?php echo htmlspecialchars($row['id_stado']); ?>">
                          <?php echo htmlspecialchars($row['soat_est']); ?>
                      </option>
                  <?php } ?>
              </select>
          </div>
          <div class="formulario_error_estado" id="formulario_correcto_estado">
              <p class="validacion" id="validacion4">Seleccione un estado valido.</p>
          </div>

          <!-- Error general -->
          <div>
              <p class="formulario_error" id="formulario_error"><b>Error:</b> Por favor complete todos los campos correctamente.</p>
          </div>

          <!-- Botón -->
          <div class="btn-field">
              <button type="submit" class="btn btn-success">Registrar Técnico-Mecánica</button>
          </div>

          <!-- Mensaje de éxito -->
          <p class="formulario_exito" id="formulario_exito">Revisión técnico-mecánica registrada correctamente.</p>

      </form>

      <!-- Bloque adicional promocional -->
      <div class="comprar">
          <p class="info">¿Necesitas agendar tu revisión? Hazlo aquí</p>
          <div class="cont_com">
              <a href="https://redamarilla.co/agenda-tecnomecanica/" class="link" target="_blank">Agendar revisión</a>
          </div>
      </div>
  </div>


  <script src="../js/registrar_tecnomecanica.js"></script>
  
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

</body>

</html>