<?php
session_start();
require_once '../../../conecct/conex.php';
include '../../../includes/validarsession.php';
include('../../../includes/auto_logout_modal.php');
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

// Fetch user's full name and foto_perfil for the profile section
$nombre_completo = $_SESSION['nombre_completo'] ?? null;
$foto_perfil = $_SESSION['foto_perfil'] ?? null;
if (!$nombre_completo || !$foto_perfil) {
    $user_query = $con->prepare("SELECT nombre_completo, foto_perfil FROM usuarios WHERE documento = :documento");
    $user_query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $user_query->execute();
    $user = $user_query->fetch(PDO::FETCH_ASSOC);
    $nombre_completo = $user['nombre_completo'] ?? 'Usuario';
    $foto_perfil = $user['foto_perfil'] ?? '/proyecto/roles/usuario/css/img/perfil.jpg';
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['foto_perfil'] = $foto_perfil;
}

// Fetch vehicle types from the tipo_vehiculo table using PDO
$query_tipos = "SELECT id_tipo_vehiculo, vehiculo FROM tipo_vehiculo";
$stmt_tipos = $con->prepare($query_tipos);
$stmt_tipos->execute();
$result_tipos = $stmt_tipos->fetchAll(PDO::FETCH_ASSOC);

// Fetch states from the estado_vehiculo table using PDO
$query_estados = "SELECT id_estado, estado FROM estado_vehiculo";
$stmt_estados = $con->prepare($query_estados);
$stmt_estados->execute();
$result_estados = $stmt_estados->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Vehiculos - Flotax AGC</title>
    <link rel="shortcut icon" href="../../../css/img/logo_sinfondo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/styles_registro_general.css">
</head>
<body onload="form_vehiculo.tipo_vehiculo.focus()">
    <?php
        include('../header.php')
    ?>

    <div class="contenido">
        <div class="form-container">
            <form method="POST" action="" enctype="multipart/form-data" class="form" id="form_vehiculo" autocomplete="off">
                <h2>Registrar Vehículo</h2>
                <div class="input-group">

                    <!-- Tipo de Vehículo -->
                    <div>
                        <div class="input_field_tipo" id="grupo_tipo">
                            <label for="tipo_vehiculo">Tipo de vehiculo:*</label>
                            <i class="bi bi-truck"></i>
                            <select id="tipo_vehiculo" name="tipo_vehiculo" >
                                <option value="">Tipo de Vehículo</option>
                                <?php foreach ($result_tipos as $row) { ?>
                                    <option value="<?php echo htmlspecialchars($row['id_tipo_vehiculo']); ?>">
                                        <?php echo htmlspecialchars($row['vehiculo']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="formulario_error_tipo" id="formulario_correcto_tipo">
                            <p class="validacion" id="validacion">Seleccione un tipo de vehículo válido.</p>
                        </div>
                    </div>

                    <!-- Marca -->
                    <div>
                        <div class="input_field_marca" id="grupo_marca">
                            <label for="id_marca">Marca del vehiculo:*</label>
                            <i class="bi bi-tags"></i>
                            <select name="id_marca" id="id_marca" >
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
                            <label for="placa">Placa del vehiculo:*</label>
                            <i class="bi bi-car-front"></i>
                            <input type="text" name="placa" id="placa" placeholder="Placa del vehículo" >
                        </div>
                        <div class="formulario_error_placa" id="formulario_correcto_placa">
                            <p class="validacion" id="validacion2">Ingrese una placa válida (ej: ABC123).</p>
                        </div>
                    </div>

                    <!-- Modelo -->
                    <div>
                        <div class="input_field_modelo" id="grupo_modelo">
                            <label for="modelo">Modelo del vehiculo:*</label>
                            <i class="bi bi-calendar-range"></i>
                            <input type="number" name="modelo" id="modelo" placeholder="Modelo" >
                        </div>
                        <div class="formulario_error_modelo" id="formulario_correcto_modelo">
                            <p class="validacion" id="validacion3">Ingrese un año valido.</p>
                        </div>
                    </div>

                    <!-- Kilometraje -->
                    <div>
                        <div class="input_field_km" id="grupo_km">
                            <label for="kilometraje">Kilometraje del vehiculo:*</label>
                            <i class="bi bi-speedometer2"></i>
                            <input type="number" name="kilometraje" id="kilometraje" placeholder="Kilometraje actual" >
                        </div>
                        <div class="formulario_error_km" id="formulario_correcto_km">
                            <p class="validacion" id="validacion4">Ingrese un kilometraje válido.</p>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <div class="input_field_estado" id="grupo_estado">
                            <label for="estado">Estado del vehiculo:*</label>
                            <i class="bi bi-clipboard-check"></i>
                            <select name="estado" id="estado">
                                <option value="">Seleccione estado</option>
                                <?php foreach ($result_estados as $row) { ?>
                                    <option value="<?php echo htmlspecialchars($row['id_estado']); ?>">
                                        <?php echo htmlspecialchars($row['estado']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="formulario_error_estado" id="formulario_correcto_estado">
                            <p class="validacion" id="validacion5">Seleccione un estado válido.</p>
                        </div>
                    </div>

                    <!-- Fecha -->
                    <div>
                        <div class="input_field_fecha" id="grupo_fecha">
                            <label for="fecha">Fecha registro:*</label>
                            <i class="bi bi-calendar-event"></i>
                            <input type="date" name="fecha" id="fecha" placeholder="Fecha registro" readonly>
                        </div>
                        <div class="formulario_error_fecha" id="formulario_correcto_fecha">
                            <p class="validacion" id="validacion6">Seleccione una fecha válida.</p>
                        </div>
                    </div>

                    <!-- Foto -->
                    <div>
                        <div class="input_field_foto" id="grupo_foto">
                            <label for="foto_vehiculo">Foto del Vehiculo:(Opcional)</label>
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
                    <p class="formulario_error" id="formulario_error"><b>Error:</b> Por favor rellena el formulario correctamente.</p>
                </div>

                <!-- Botón -->
                <div class="btn-field">
                    <button type="submit" class="btn btn-success">Guardar Vehículo</button>
                </div>

                <!-- Mensaje de éxito -->
                <p class="formulario_exito" id="formulario_exito">Vehículo registrado correctamente.</p>
            </form>
        </div>
    </div>

    

    <script>
            const nombreUsuario = "<?php echo $_SESSION['nombre_usuario'] ?? 'desconocido'; ?>";

        document.getElementById('tipo_vehiculo').addEventListener('change', function() {
            const id_tipo = this.value;
            const marcas = document.getElementById('id_marca');

            if (id_tipo) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../AJAX/obtener_marcas.php', true);
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

    <script src="../js/vehiculos_registro.js"></script>
    
</body>
</html>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $tipo_vehiculo = $_POST['tipo_vehiculo'] ?? '';
    $id_marca = $_POST['id_marca'] ?? '';
    $placa = $_POST['placa'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $kilometraje = $_POST['kilometraje'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $foto_vehiculo = $_FILES['foto_vehiculo'] ?? null;

    // Validate required fields
    $errors = [];
    if (empty($tipo_vehiculo)) {
        $errors['tipo_vehiculo'] = 'El tipo de vehículo es obligatorio.';
    }
    if (empty($id_marca)) {
        $errors['id_marca'] = 'La marca del vehículo es obligatoria.';
    }
    if (empty($placa)) {
        $errors['placa'] = 'La placa del vehículo es obligatoria.';
    }
    if (empty($modelo)) {
        $errors['modelo'] = 'El modelo del vehículo es obligatorio.';
    }
    if (empty($kilometraje)) {
        $errors['kilometraje'] = 'El kilometraje del vehículo es obligatorio.';
    }
    if (empty($estado)) {
        $errors['estado'] = 'El estado del vehículo es obligatorio.';
    }
    if (empty($fecha)) {
        $errors['fecha'] = 'La fecha de registro es obligatoria.';
    }

    // If there are no errors, proceed with database insertion
    if (empty($errors)) {
        // Prepare SQL query to insert vehicle data
        $query = "INSERT INTO vehiculos (tipo_vehiculo, id_marca, placa, modelo, kilometraje, estado, fecha_registro, foto_vehiculo, registrado_por) VALUES (:tipo_vehiculo, :id_marca, :placa, :modelo, :kilometraje, :estado, :fecha_registro, :foto_vehiculo, :registrado_por)";
        $stmt = $con->prepare($query);

        // Bind parameters
        $stmt->bindParam(':tipo_vehiculo', $tipo_vehiculo);
        $stmt->bindParam(':id_marca', $id_marca);
        $stmt->bindParam(':placa', $placa);
        $stmt->bindParam(':modelo', $modelo);
        $stmt->bindParam(':kilometraje', $kilometraje);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':fecha_registro', $fecha);
        $stmt->bindParam(':registrado_por', $documento);

        // Handle file upload for foto_vehiculo
        if ($foto_vehiculo && $foto_vehiculo['error'] == 0) {
            $foto_nombre = uniqid('foto_', true) . '.' . pathinfo($foto_vehiculo['name'], PATHINFO_EXTENSION);
            $foto_temp = $foto_vehiculo['tmp_name'];
            $foto_destino = $_SERVER['DOCUMENT_ROOT'] . '/proyecto/roles/usuario/fotos_vehiculos/' . $foto_nombre;

            // Move the uploaded file to the destination folder
            if (move_uploaded_file($foto_temp, $foto_destino)) {
                $stmt->bindParam(':foto_vehiculo', $foto_nombre);
            } else {
                $stmt->bindParam(':foto_vehiculo', null, PDO::PARAM_NULL);
            }
        } else {
            $stmt->bindParam(':foto_vehiculo', null, PDO::PARAM_NULL);
        }

        // Execute the query
        if ($stmt->execute()) {
            // Success: Redirect or show success message
            echo '<script>alert("Vehículo registrado correctamente."); window.location.href = "registro_vehiculos.php";</script>';
        } else {
            // Error: Show error message
            echo '<script>alert("Error al registrar el vehículo. Por favor, inténtelo de nuevo.");</script>';
        }
    } else {
        // Handle validation errors (optional)
        foreach ($errors as $campo => $error) {
            echo '<script>document.getElementById("validacion' . substr($campo, -1) . '").innerText = "' . $error . '";</script>';
        }
    }
}
?>