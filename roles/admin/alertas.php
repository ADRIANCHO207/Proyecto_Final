<?php
session_start();
require_once('../../conecct/conex.php');
include '../../includes/validarsession.php';

$db = new Database();
$con = $db->conectar();
$code = $_SESSION['documento'];
$sql = $con->prepare("SELECT * FROM vehiculos
    INNER JOIN usuarios ON vehiculos.Documento = usuarios.documento 
    INNER JOIN marca ON vehiculos.id_marca = marca.id_marca
    INNER JOIN estado_vehiculo ON vehiculos.id_estado = estado_vehiculo.id_estado
    WHERE placa= :code");
$sql->bindParam(':code', $code);
$sql->execute();
$fila = $sql->fetch();

$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    header('Location: ../../login.php');
    exit;
}

$nombre_completo = $_SESSION['nombre_completo'] ?? null;
$foto_perfil = $_SESSION['foto_perfil'] ?? null;
if (!$nombre_completo || !$foto_perfil) {
    $user_query = $con->prepare("SELECT nombre_completo, foto_perfil FROM usuarios WHERE documento = :documento");
    $user_query->bindParam(':documento', $documento, PDO::PARAM_STR);
    $user_query->execute();
    $user = $user_query->fetch(PDO::FETCH_ASSOC);
    $nombre_completo = $user['nombre_completo'] ?? 'Usuario';
    $foto_perfil = $user['foto_perfil'] ?: 'Proyecto_Final/roles/user/css/img/perfil.jpg';
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['foto_perfil'] = $foto_perfil;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Módulo de Alertas - Flotax AGC</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/stylesvehiculos.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    .estado-verde { background-color: #d4edda; color: #155724; }
    .estado-amarillo { background-color: #fff3cd; color: #856404; }
    .estado-rojo { background-color: #f8d7da; color: #721c24; }
    .badge-alerta { font-size: 0.9rem; padding: 0.5em; }
  </style>
</head>
<body>

<?php include 'menu.html'; ?> <!-- Sidebar fuera del contenido principal -->

<div class="content">
  <div class="container mt-5">
    <h2 class="mb-4">🔔 Alertas del Sistema</h2>

    <!-- Filtros -->
    <form class="row mb-4">
      <div class="col-md-3">
        <label>Tipo de Alerta</label>
        <select class="form-select">
          <option value="">Todas</option>
          <option>SOAT</option>
          <option>Revisión Técnico-Mecánica</option>
          <option>Mantenimiento</option>
          <option>Licencia</option>
        </select>
      </div>
      <div class="col-md-3">
        <label>Estado</label>
        <select class="form-select">
          <option value="">Todos</option>
          <option>🟢 Al día</option>
          <option>🟡 Pendiente</option>
          <option>🔴 Crítica</option>
        </select>
      </div>
      <div class="col-md-3">
        <label>Vehículo</label>
        <input type="text" class="form-control" placeholder="Placa (ej: ABC123)">
      </div>
      <div class="col-md-3 align-self-end">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
      </div>
    </form>

    <!-- Tabla de Alertas -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>Tipo de Alerta</th>
            <th>Vehículo</th>
            <th>Descripción</th>
            <th>Fecha de Alerta</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr class="estado-amarillo">
            <td>📆 SOAT</td>
            <td>ABC123</td>
            <td>El SOAT vence en 10 días</td>
            <td>2025-06-01</td>
            <td><span class="badge bg-warning text-dark">⚠️ Activa</span></td>
            <td><a href="#" class="btn btn-outline-primary btn-sm">Ver vehículo</a></td>
          </tr>
          <tr class="estado-rojo">
            <td>⛔ Técnico-Mecánica</td>
            <td>RST789</td>
            <td>Documento vencido el 2025-04-10</td>
            <td>2025-05-10</td>
            <td><span class="badge bg-danger">❌ Crítica</span></td>
            <td><a href="#" class="btn btn-outline-danger btn-sm">Actualizar</a></td>
          </tr>
          <tr class="estado-amarillo">
            <td>🔧 Mantenimiento</td>
            <td>XYZ456</td>
            <td>Cambio de aceite en 300 km</td>
            <td>2025-05-25</td>
            <td><span class="badge bg-warning text-dark">🟡 Pendiente</span></td>
            <td><a href="#" class="btn btn-outline-success btn-sm">Ver detalles</a></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Historial de alertas o alertas resueltas -->
    <div class="mt-5">
      <h4>✅ Alertas Resueltas</h4>
      <p class="text-muted">Aquí puedes consultar las alertas ya resueltas o finalizadas.</p>
      <!-- Similar tabla opcional -->
    </div>
  </div>
</div>

</body>
</html>


<script>
function filtrarTabla() {
  const input = document.getElementById('buscar')?.value.toLowerCase() || '';
  const rows = document.querySelectorAll("#tablaUsuarios tbody tr");
  rows.forEach(row => {
    const text = row.innerText.toLowerCase();
    row.style.display = text.includes(input) ? '' : 'none';
  });
}

const filasPorPagina = 5;
function configurarPaginacion() {
  const filas = document.querySelectorAll('#tablaUsuarios tbody tr');
  const totalPaginas = Math.ceil(filas.length / filasPorPagina);
  const paginacion = document.getElementById('paginacion');

  function mostrarPagina(pagina) {
    filas.forEach((fila, i) => {
      fila.style.display = (i >= (pagina - 1) * filasPorPagina && i < pagina * filasPorPagina) ? '' : 'none';
    });
    document.querySelectorAll('#paginacion .page-item').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`#paginacion .page-item:nth-child(${pagina})`)?.classList.add('active');
  }

  paginacion.innerHTML = '';
  for (let i = 1; i <= totalPaginas; i++) {
    const li = document.createElement('li');
    li.className = 'page-item' + (i === 1 ? ' active' : '');
    li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
    li.querySelector('a').addEventListener('click', e => {
      e.preventDefault();
      mostrarPagina(i);
    });
    paginacion.appendChild(li);
  }

  mostrarPagina(1);
}
window.addEventListener('DOMContentLoaded', configurarPaginacion);
</script>

</body>
</html>
