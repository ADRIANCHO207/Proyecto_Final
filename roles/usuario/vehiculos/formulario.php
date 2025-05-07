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

// Fetch user's full name and foto_perfil for the profile section
$nombre_completo = $_SESSION['nombre_completo'] ?? null;
$foto_perfil = $_SESSION['foto_perfil'] ?? null;
if (!$nombre_completo || !$foto_perfil) {
    $user_query = $conn->prepare("SELECT nombre_completo, foto_perfil FROM usuarios WHERE documento = :documento");
    $user_query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $user_query->execute();
    $user = $user_query->fetch(PDO::FETCH_ASSOC);
    $nombre_completo = $user['nombre_completo'] ?? 'Usuario';
    $foto_perfil = $user['foto_perfil'] ?? '../css/img/perfil.jpg';
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['foto_perfil'] = $foto_perfil;
}

// Fetch vehicle types from the tipo_vehiculo table using PDO
$query_tipos = "SELECT id_tipo_vehiculo, vehiculo FROM tipo_vehiculo";
$stmt_tipos = $conn->prepare($query_tipos);
$stmt_tipos->execute();
$result_tipos = $stmt_tipos->fetchAll(PDO::FETCH_ASSOC);

// Fetch states from the estado_vehiculo table using PDO
$query_estados = "SELECT id_estado, estado FROM estado_vehiculo";
$stmt_estados = $conn->prepare($query_estados);
$stmt_estados->execute();
$result_estados = $stmt_estados->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnico-Mecánica - Flotax AGC</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilos_formulario_carro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../css/img/logo.jpeg" alt="Logo">
            <span class="empresa">Flotax AGC</span>
        </div>
        <div class="menu">
            <a href="../index.php">Volver al Panel</a>
        </div>
        <div class="perfil">
            <img src="../<?php echo htmlspecialchars($foto_perfil); ?>" alt="Usuario" class="imagen-usuario">
            <div class="info-usuario">
                <span><?php echo htmlspecialchars($nombre_completo); ?></span>
                <br>
                <span>Usuario</span>
            </div>
        </div>
    </div>

    <div class="form-container">
        <form method="POST" action="guardar_vehiculo.php" enctype="multipart/form-data">
            <h2>Registrar Vehículo</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="tipo_vehiculo">Tipo de Vehículo:</label>
                    <select id="tipo_vehiculo" name="tipo_vehiculo">
                        <option value="">Seleccione...</option>
                        <?php foreach ($result_tipos as $row) { ?>
                            <option value="<?php echo htmlspecialchars($row['id_tipo_vehiculo']); ?>">
                                <?php echo htmlspecialchars($row['vehiculo']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_marca">Marca:</label>
                    <select name="id_marca" id="id_marca">
                        <option value="">Seleccione un tipo primero</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="placa">Placa:</label>
                    <input type="text" name="placa" required>
                </div>

                <input type="hidden" name="documento" value="<?php echo htmlspecialchars($documento); ?>">

                <div class="form-group">
                    <label for="modelo">Modelo:</label>
                    <input type="text" name="modelo" required>
                </div>

                <div class="form-group">
                    <label for="kilometraje">Kilometraje actual:</label>
                    <input type="number" name="kilometraje" required>
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select name="estado" id="estado" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($result_estados as $row) { ?>
                            <option value="<?php echo htmlspecialchars($row['id_estado']); ?>">
                                <?php echo htmlspecialchars($row['estado']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha">Fecha de registro:</label>
                    <input type="date" name="fecha" required>
                </div>

                <div class="form-group">
                    <label for="foto_vehiculo">Foto del Vehículo (Opcional):</label>
                    <input type="file" name="foto_vehiculo" id="foto_vehiculo" accept="image/*">
                </div>

                <div class="btn-container">
                    <button type="submit">Guardar Vehículo</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('tipo_vehiculo').addEventListener('change', function() {
            const id_tipo = this.value;
            const marcas = document.getElementById('id_marca');

            if (id_tipo) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'obtener_marcas.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        marcas.innerHTML = this.responseText;
                    } else {
                        marcas.innerHTML = '<option value="">Error al cargar marcas</option>';
                    }
                };
                xhr.onerror = function() {
                    marcas.innerHTML = '<option value="">Error al cargar marcas</option>';
                };
                xhr.send('id_tipo=' + encodeURIComponent(id_tipo));
            } else {
                marcas.innerHTML = '<option value="">Seleccione un tipo primero</option>';
            }
        });
    </script>
</body>
</html>