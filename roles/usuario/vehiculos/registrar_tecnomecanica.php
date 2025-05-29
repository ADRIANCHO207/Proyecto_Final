<?php
session_start();
require_once '../../../conecct/conex.php';
include '../../../includes/validarsession.php';
$database = new Database();
$con = $database->conectar();


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
  <title>Registro de Técnico-Mecánica</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="estilos.css">
  <script defer src="validar_tecnico.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <?php
        include('../header.php')   
    ?>

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