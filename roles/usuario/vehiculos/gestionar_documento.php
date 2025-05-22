<?php
session_start();
require_once '../../../conecct/conex.php';
include '../../../includes/validarsession.php';

// Instantiate the Database class and get the PDO connection
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

// Fetch user's full name for the profile section
$nombre_completo = $_SESSION['nombre_completo'] ?? 'Usuario';

// Get the tipo from the URL (e.g., SOAT, Tecnomecanica, Licencia_Conduccion)
$tipo = $_GET['tipo'] ?? '';
if (empty($tipo) || !in_array($tipo, ['SOAT', 'Tecnomecanica', 'Licencia_Conduccion'])) {
    header('Location: ../index.php');
    exit;
}

// Fetch vehicles for the user
$query_vehiculos = "SELECT placa FROM vehiculos WHERE Documento = :documento";
$stmt_vehiculos = $con->prepare($query_vehiculos);
$stmt_vehiculos->bindParam(':documento', $documento);
$stmt_vehiculos->execute();
$vehiculos = $stmt_vehiculos->fetchAll(PDO::FETCH_ASSOC);

// Fetch empresas tramite
$query_empresas = "SELECT id, Empresa FROM empresa_tramite";
$stmt_empresas = $con->prepare($query_empresas);
$stmt_empresas->execute();
$empresas = $stmt_empresas->fetchAll(PDO::FETCH_ASSOC);

// Check if form was just submitted (for success message)
$success = false;
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $success = true;
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

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar <?php echo htmlspecialchars($tipo); ?> - Flotax AGC</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilos_formulario_carro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php
        include('../header.php')   
    ?>

    <?php if ($success): ?>
        <div class="success-message" style="text-align: center; color: green; margin: 20px 0;">
            ¡Datos guardados con éxito! Ya puedes recibir recordatorios.
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="guardar_documento.php">
            <a href="../index.php" class="btn-back">← Atrás</a>
            <h2>Gestionar <?php echo htmlspecialchars($tipo); ?></h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="placa">Vehículo (Placa):</label>
                    <select name="placa" id="placa" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($vehiculos as $vehiculo) { ?>
                            <option value="<?php echo htmlspecialchars($vehiculo['placa']); ?>">
                                <?php echo htmlspecialchars($vehiculo['placa']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="empresa_tramite">Empresa de Trámite:</label>
                    <select name="empresa_tramite" id="empresa_tramite" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($empresas as $empresa) { ?>
                            <option value="<?php echo htmlspecialchars($empresa['id']); ?>">
                                <?php echo htmlspecialchars($empresa['Empresa']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_inicio">Fecha de Inicio:</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" required>
                </div>

                <div class="form-group">
                    <label for="fecha_vencimiento">Fecha de Vencimiento:</label>
                    <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" required>
                </div>

                <input type="hidden" name="tipo_documento" value="<?php echo htmlspecialchars($tipo); ?>">

                <div class="btn-container">
                    <button type="submit">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</body>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Licencia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="estilos.css">
  <script defer src="validacion.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="card shadow-lg">
      <div class="card-header bg-primary text-white">
        <h4><i class="bi bi-person-vcard-fill"></i> Registro de Licencia de Conducción</h4>
      </div>
      <div class="card-body">
        <form id="formLicencia" method="post" action="">
          <div class="mb-3">
            <label class="form-label">Número de Licencia</label>
            <input type="text" class="form-control" id="numeroLicencia" name="numeroLicencia" required>
            <div class="error" id="errorNumero"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
            <div class="error" id="errorNombre"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Documento de Identidad</label>
            <input type="text" class="form-control" id="documento" name="documento" required>
            <div class="error" id="errorDocumento"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha de Expedición</label>
            <input type="date" class="form-control" id="fechaExpedicion" name="fechaExpedicion" required>
            <div class="error" id="errorExpedicion"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha de Vencimiento</label>
            <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento" required>
            <div class="error" id="errorVencimiento"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select class="form-select" id="categoria" name="categoria" required>
              <option value="">Seleccione...</option>
              <option value="A1">A1</option>
              <option value="A2">A2</option>
              <option value="B1">B1</option>
              <option value="B2">B2</option>
              <option value="C1">C1</option>
              <option value="C2">C2</option>
            </select>
            <div class="error" id="errorCategoria"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
              <option value="">Seleccione...</option>
              <option value="activo">Activo</option>
              <option value="vencido">Vencido</option>
              <option value="suspendido">Suspendido</option>
            </select>
            <div class="error" id="errorEstado"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">servicio</label>
            <select class="form-select" id="estado" name="estado" required>
              <option value="">Seleccione...</option>
              <option>particular</option>
              <option>publica</option>
            </select>
            <div class="error" id="errorEstado"></div>
          </div>

          <button type="submit" class="btn btn-success">
            <i class="bi bi-save-fill"></i> Registrar Licencia
          </button>
        </form>
      </div>
    </div>
  </div>
                            <!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de SOAT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="estilos.css">
  <script defer src="validar_soat.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="card shadow-lg">
      <div class="card-header bg-success text-white">
        <h4><i class="bi bi-shield-check"></i> Registro de SOAT</h4>
      </div>
      <div class="card-body">
        <form id="formSoat" method="post" action="">
          <div class="mb-3">
            <label class="form-label">Número de Póliza</label>
            <input type="text" class="form-control" id="numeroPoliza" name="numeroPoliza" required>
            <div class="error" id="errorPoliza"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Placa del Vehículo</label>
            <input type="text" class="form-control" id="placa" name="placa" required>
            <div class="error" id="errorPlaca"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha de Expedición</label>
            <input type="date" class="form-control" id="fechaExpedicion" name="fechaExpedicion" required>
            <div class="error" id="errorExpedicion"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha de Vencimiento</label>
            <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento" required>
            <div class="error" id="errorVencimiento"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Aseguradora</label>
            <input type="text" class="form-control" id="aseguradora" name="aseguradora" required>
            <div class="error" id="errorAseguradora"></div>
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save-fill"></i> Registrar SOAT
          </button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Técnico-Mecánica</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="estilos.css">
  <script defer src="validar_tecnico.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="card shadow-lg">
      <div class="card-header bg-warning text-dark">
        <h4><i class="bi bi-tools"></i> Registro de Revisión Técnico-Mecánica</h4>
      </div>
      <div class="card-body">
        <form id="formTecnico" method="post" action="">
          <div class="mb-3">
            <label class="form-label">Placa del Vehículo</label>
            <input type="text" class="form-control" id="placa" name="placa" required>
            <div class="error" id="errorPlaca"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Centro de Revisión</label>
            <input type="text" class="form-control" id="centro" name="centro" required>
            <div class="error" id="errorCentro"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha de Revisión</label>
            <input type="date" class="form-control" id="fechaRevision" name="fechaRevision" required>
            <div class="error" id="errorRevision"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha de Vencimiento</label>
            <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento" required>
            <div class="error" id="errorVencimiento"></div>
          </div>

          <div class="mb-3">
            <label class="form-label">Resultado</label>
            <select class="form-select" id="resultado" name="resultado" required>
              <option value="">Seleccione...</option>
              <option value="aprobado">Aprobado</option>
              <option value="rechazado">Rechazado</option>
            </select>
            <div class="error" id="errorResultado"></div>
          </div>

          <button type="submit" class="btn btn-dark">
            <i class="bi bi-save-fill"></i> Registrar Revisión
          </button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>

</body>
</html>

</html>