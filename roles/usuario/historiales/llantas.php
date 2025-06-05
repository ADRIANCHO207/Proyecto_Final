<?php
session_start();
require_once('../../../conecct/conex.php');
require_once('../../../includes/validarsession.php');
$db = new Database();
$con = $db->conectar();

$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    header('Location: ../../../login/login.php');
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
};

// Fetch revisiones de llantas
$llantas_query = $con->prepare("
    SELECT l.*, v.placa 
    FROM llantas l 
    JOIN vehiculos v ON l.placa = v.placa 
    WHERE l.documento_usuario = :documento
");
$llantas_query->bindParam(':documento', $documento, PDO::PARAM_STR);
$llantas_query->execute();
$llantas = $llantas_query->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $ultimo_cambio = $_POST['ultimo_cambio'] ?? '';
    $presion_llantas = $_POST['presion_llantas'] ?? '';
    $kilometraje_actual = $_POST['kilometraje_actual'] ?? '';
    $proximo_cambio_km = $_POST['proximo_cambio_km'] ?? '';
    $proximo_cambio_fecha = $_POST['proximo_cambio_fecha'] ?? '';
    $notas = $_POST['notas'] ?? '';

    $insert_query = $con->prepare("
        INSERT INTO llantas (placa, estado, ultimo_cambio, presion_llantas, kilometraje_actual, proximo_cambio_km, proximo_cambio_fecha, notas, documento_usuario)
        VALUES (:placa, :estado, :ultimo_cambio, :presion_llantas, :kilometraje_actual, :proximo_cambio_km, :proximo_cambio_fecha, :notas, :documento)
    ");
    $insert_query->execute([
        ':placa' => $placa,
        ':estado' => $estado,
        ':ultimo_cambio' => $ultimo_cambio ?: null,
        ':presion_llantas' => $presion_llantas ?: null,
        ':kilometraje_actual' => $kilometraje_actual ?: null,
        ':proximo_cambio_km' => $proximo_cambio_km ?: null,
        ':proximo_cambio_fecha' => $proximo_cambio_fecha ?: null,
        ':notas' => $notas,
        ':documento' => $documento
    ]);
    $_SESSION['success'] = 'Revisión de llantas registrada exitosamente.';
    header('Location: gestionar_llantas.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flotax AGC - Llantas</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    * {
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: #f4f6f9;
    }

    .container {
        margin-top: 40px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    table thead {
        background-color: #0d6efd;
        color: white;
    }

    table th, table td {
        padding: 12px;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }

    tr.alerta {
        background-color: #fff3cd; /* Amarillo claro para advertencias */
    }

    tr:hover {
        background-color: #f1f1f1;
    }
</style>

</head>
<body>
    <?php include('../header.php'); ?>


<div class="container">
    
    <h2>Historial de Revisiones de Llantas</h2>
        <table>
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Estado</th>
                    <th>Último Cambio</th>
                    <th>Presión (PSI)</th>
                    <th>Kilometraje Actual</th>
                    <th>Próximo Cambio (km)</th>
                    <th>Próximo Cambio (Fecha)</th>
                    <th>Notas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($llantas as $llanta): ?>
                    <tr <?php
                        $hoy = new DateTime();
                        $proximo = new DateTime($llanta['proximo_cambio_fecha']);
                        $diferencia_dias = $hoy->diff($proximo)->days;
                        if ($proximo >= $hoy && $diferencia_dias <= 30) {
                            echo 'class="alerta"';
                        }
                    ?>>
                        <td><?php echo htmlspecialchars($llanta['placa']); ?></td>
                        <td><?php echo htmlspecialchars($llanta['estado']); ?></td>
                        <td><?php echo htmlspecialchars($llanta['ultimo_cambio'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($llanta['presion_llantas'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($llanta['kilometraje_actual'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($llanta['proximo_cambio_km'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($llanta['proximo_cambio_fecha'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($llanta['notas'] ?: 'N/A'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

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