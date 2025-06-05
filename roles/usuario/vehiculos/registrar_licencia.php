<?php
session_start();
require_once '../../../conecct/conex.php';
include '../../../includes/validarsession.php';
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar - Flotax AGC</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilos_formulario_carro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php
        include('../header.php')   
    ?>



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
</body>