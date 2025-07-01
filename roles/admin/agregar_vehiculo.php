<?php
session_start();
require_once '../../conecct/conex.php';
include '../../includes/validarsession.php';
$database = new Database();
$con = $database->conectar();

// Check if the connection is successful
if (!$con) {
    die("Error: No se pudo conectar a la base de datos. Verifique el archivo conex.php.");
}

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
    $foto_perfil = $user['foto_perfil'] ?: 'roles/user/css/img/perfil.jpg';
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['foto_perfil'] = $foto_perfil;
}

// Fetch vehicle types from the tipo_vehiculo table using PDO
$query_tipos = "SELECT * FROM tipo_vehiculo";
$stmt_tipos = $con->prepare($query_tipos);
$stmt_tipos->execute();
$result_tipos = $stmt_tipos->fetchAll(PDO::FETCH_ASSOC);

// Fetch states from the estado_vehiculo table using PDO
$query_estados = "SELECT * FROM estado_vehiculo";
$stmt_estados = $con->prepare($query_estados);
$stmt_estados->execute();
$result_estados = $stmt_estados->fetchAll(PDO::FETCH_ASSOC);

$query_m


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Vehículos - Flotax AGC</title>
    <link rel="shortcut icon" href="../../../css/img/logo_sinfondo.png">
    <link rel="stylesheet" href="css/registro-vehiculos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body onload="form_vehiculo.tipo_vehiculo.focus()">
  
<?php include 'menu.php'; ?> <!-- Sidebar fuera del contenido principal -->

  <div class="content">
    <!-- <div class="buscador mb-3">
      <input type="text" id="buscar" class="form-control" placeholder="🔍 Buscar por nombre, documento o correo" onkeyup="filtrarTabla()">
    </div>
     -->
    <div class="contenido">
      <form method="POST" action="" enctype="multipart/form-data" class="form" id="form_vehiculo" autocomplete="off">
        <h2><i class="bi bi-truck"></i> Registrar Vehículo</h2>
        
        <div class="input-group">
          <!-- Tipo de Vehículo -->
          <div>
            <div class="input_field_tipo" id="grupo_tipo">
              <label for="tipo_vehiculo">Tipo de vehículo</label>
              <i class="bi bi-truck"></i>
              <select id="tipo_vehiculo" name="tipo_vehiculo">
                <option value="">Seleccione el tipo de vehículo</option>
                <?php if (isset($result_tipos)): ?>
                  <?php foreach ($result_tipos as $row): ?>
                    <option value="<?php echo htmlspecialchars($row['id_tipo_vehiculo']); ?>">
                      <?php echo htmlspecialchars($row['vehiculo']); ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="formulario_error_tipo" id="formulario_correcto_tipo">
              <p class="validacion" id="validacion">Seleccione un tipo de vehículo válido.</p>
            </div>
          </div>

          <!-- Marca -->
          <div>
            <div class="input_field_marca" id="grupo_marca">
              <label for="id_marca">Marca del vehículo</label>
              <i class="bi bi-tags"></i>
              <select name="id_marca" id="id_marca">
                <option value="">Seleccione una marca</option>
              </select>
            </div>
            <div class="formulario_error_marca" id="formulario_correcto_marca">
              <p class="validacion" id="validacion1">Seleccione una marca válida.</p>
            </div>
          </div>

          <!-- Placa -->
          <div>
            <div class="input_field_placa" id="grupo_placa">
              <label for="placa">Placa del vehículo</label>
              <i class="bi bi-car-front"></i>
              <input type="text" name="placa" id="placa" placeholder="Ej: ABC123" maxlength="6">
            </div>
            <div class="formulario_error_placa" id="formulario_correcto_placa">
              <p class="validacion" id="validacion2">Ingrese una placa válida (ej: ABC123).</p>
            </div>
          </div>

          <!-- Modelo -->
          <div>
            <div class="input_field_modelo" id="grupo_modelo">
              <label for="modelo">Modelo del vehículo</label>
              <i class="bi bi-calendar-range"></i>
              <input type="number" name="modelo" id="modelo" placeholder="Ej: 2023" min="1900" max="2030">
            </div>
            <div class="formulario_error_modelo" id="formulario_correcto_modelo">
              <p class="validacion" id="validacion3">Ingrese un año válido.</p>
            </div>
          </div>

          <!-- Kilometraje -->
          <div>
            <div class="input_field_km" id="grupo_km">
              <label for="kilometraje">Kilometraje del vehículo</label>
              <i class="bi bi-speedometer2"></i>
              <input type="number" name="kilometraje" id="kilometraje" placeholder="Ej: 50000" min="0">
            </div>
            <div class="formulario_error_km" id="formulario_correcto_km">
              <p class="validacion" id="validacion4">Ingrese un kilometraje válido.</p>
            </div>
          </div>

          <!-- Estado -->
          <div>
            <div class="input_field_estado" id="grupo_estado">
              <label for="estado">Estado del vehículo</label>
              <i class="bi bi-clipboard-check"></i>
              <select name="estado" id="estado">
                <option value="">Seleccione el estado</option>
                <?php if (isset($result_estados)): ?>
                  <?php foreach ($result_estados as $row): ?>
                    <option value="<?php echo htmlspecialchars($row['id_estado']); ?>">
                      <?php echo htmlspecialchars($row['estado']); ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="formulario_error_estado" id="formulario_correcto_estado">
              <p class="validacion" id="validacion5">Seleccione un estado válido.</p>
            </div>
          </div>

          <!-- Fecha -->
          <div>
            <div class="input_field_fecha" id="grupo_fecha">
              <label for="fecha">Fecha de registro</label>
              <i class="bi bi-calendar-event"></i>
              <input type="date" name="fecha" id="fecha" readonly>
            </div>
            <div class="formulario_error_fecha" id="formulario_correcto_fecha">
              <p class="validacion" id="validacion6">Seleccione una fecha válida.</p>
            </div>
          </div>

          <!-- Foto -->
          <div>
            <div class="input_field_foto" id="grupo_foto">
              <label for="foto_vehiculo">Foto del vehículo (Opcional)</label>
              <i class="bi bi-camera"></i>
              <input type="file" name="foto_vehiculo" id="foto_vehiculo" accept="image/*">
            </div>
            <div class="formulario_error_foto" id="formulario_correcto_foto">
              <p class="validacion" id="validacion7">Solo se permiten imágenes (JPG, PNG).</p>
            </div>
          </div>
        </div>

        <!-- Mensaje general de error -->
        <div>
          <p class="formulario_error" id="formulario_error">
            <i class="bi bi-exclamation-triangle"></i>
            <b>Error:</b> Por favor rellena el formulario correctamente.
          </p>
        </div>

        <!-- Botón -->
        <div class="btn-field">
          <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle"></i>
            Guardar Vehículo
          </button>
        </div>

        <!-- Mensaje de éxito -->
        <p class="formulario_exito" id="formulario_exito">
          <i class="bi bi-check-circle-fill"></i>
          Vehículo registrado correctamente.
        </p>
      </form>

    </div>
  </div>

  <!-- Modal para Agregar Vehículo -->
<div class="modal fade" id="modalAgregarVehiculo" tabindex="-1" aria-labelledby="modalAgregarVehiculoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarVehiculoLabel">
          <i class="bi bi-truck"></i> Registrar Nuevo Vehículo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalAgregarVehiculoBody">
        <!-- Contenido dinámico se cargará aquí -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle"></i> Cancelar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Mostrar Imagen -->
<div class="modal fade" id="modalImagen" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="imagenCompleta" src="" class="img-fluid" alt="Vehículo">
      </div>
    </div>
  </div>
</div>

  <script>
    // Establecer fecha actual
    document.getElementById('fecha').value = new Date().toISOString().split('T')[0];

    // AJAX para cargar marcas
    document.getElementById('tipo_vehiculo').addEventListener('change', function() {
      const id_tipo = this.value;
      const marcas = document.getElementById('id_marca');

      if (id_tipo) {
        marcas.innerHTML = '<option value="">Cargando marcas...</option>';
        marcas.disabled = true;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../usuario/AJAX/obtener_marcas.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
          marcas.disabled = false;
          if (this.status === 200) {
            marcas.innerHTML = '<option value="">Seleccione una marca</option>' + this.responseText;
          } else {
            marcas.innerHTML = '<option value="">Error al cargar marcas</option>';
          }
        };
        xhr.onerror = function() {
          marcas.disabled = false;
          marcas.innerHTML = '<option value="">Error al cargar marcas</option>';
        };
        xhr.send('id_tipo=' + encodeURIComponent(id_tipo));
      } else {
        marcas.innerHTML = '<option value="">Seleccione un tipo primero</option>';
        marcas.disabled = false;
      }
    });

    // Función para filtrar tabla (si existe)
    function filtrarTabla() {
      // Implementar lógica de filtrado si es necesario
    }
  </script>

  <script src="../usuario/js/vehiculos_registro.js"></script>
</body>
</html>
