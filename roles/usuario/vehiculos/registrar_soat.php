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
  <title>Registro de Licencia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="estilos.css">
  <script defer src="validacion.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
  <?php
    include('../header.php')   
  ?>

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
