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
    $foto_perfil = $user['foto_perfil'] ?: 'proyecto/roles/usuario/css/img/perfil.jpg';
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
</html>