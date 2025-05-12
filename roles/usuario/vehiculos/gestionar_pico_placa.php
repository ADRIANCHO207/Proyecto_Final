<?php
session_start();
require_once '../../../conecct/conex.php';
include '../../../includes/validarsession.php';

// Instantiate the Database class and get the PDO connection
$database = new Database();
$conn = $database->conectar();

// Check if the connection is successful
if (!$conn) {
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
$stmt_vehiculos = $conn->prepare($query_vehiculos);
$stmt_vehiculos->bindParam(':documento', $documento);
$stmt_vehiculos->execute();
$vehiculos = $stmt_vehiculos->fetchAll(PDO::FETCH_ASSOC);

// Check if form was just submitted (for success message)
$success = false;
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Pico y Placa - Flotax AGC</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilos_formulario_carro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../../../css/img/logo.png" alt="Logo">
            <span class="empresa">Flotax AGC</span>
        </div>
        <div class="menu">
            <a href="../index.php">Volver al Panel</a>
        </div>
        <div class="perfil">
            <img src="../css/img/perfil.jpg" alt="Usuario" class="imagen-usuario">
            <div class="info-usuario">
                <span><?php echo htmlspecialchars($nombre_completo); ?></span>
                <br>
                <span>Usuario</span>
            </div>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="success-message" style="text-align: center; color: green; margin: 20px 0;">
            ¡Datos guardados con éxito! Ya puedes recibir recordatorios.
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="guardar_pico_placa.php">
            <a href="../index.php" class="btn-back">← Atrás</a>
            <h2>Gestionar Pico y Placa</h2>
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
                    <label for="dia">Día de Pico y Placa:</label>
                    <select name="dia" id="dia" required>
                        <option value="">Seleccione...</option>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                        <option value="No aplica">No aplica</option>
                    </select>
                </div>

                <div class="btn-container">
                    <button type="submit">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>