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
  <title>Historial de Mantenimientos</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/stylesvehiculos.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'menu.html'; ?> <!-- Sidebar fuera del contenido principal -->

<div class="content">
  <div class="container py-4">
    <h2 class="mb-4">🛠️ Historial de Mantenimientos</h2>

    <div class="table-responsive">
      <table class="table table-striped" id="tablaUsuarios">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Placa</th>
            <th>Tipo</th>
            <th>Kilometraje</th>
            <th>Descripción</th>
            <th>Taller</th>
            <th>Costo</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>2025-05-20</td>
            <td>ABC123</td>
            <td>Preventivo</td>
            <td>55.000 km</td>
            <td>Cambio aceite y filtro</td>
            <td>Taller Saldaña</td>
            <td>$120.000</td>
            <td><span class="text-success fw-bold">✅ Listo</span></td>
            <td>
              <div class="d-flex gap-2">
                <a href="#" onclick="window.open('actualizar.php?id=ABC123', '', 'width=600, height=500, toolbar=NO')" class="text-primary">
                  <i class="bi bi-pencil-square action-icon" title="Editar"></i>
                </a>
                <a href="#" onclick="confirmarEliminacion('ABC123')" class="text-danger">
                  <i class="bi bi-trash action-icon" title="Eliminar"></i>
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <td>2025-04-12</td>
            <td>XYZ456</td>
            <td>Correctivo</td>
            <td>87.200 km</td>
            <td>Pastillas de freno</td>
            <td>Mecánica Juan</td>
            <td>$240.000</td>
            <td><span class="text-success fw-bold">✅ Listo</span></td>
            <td>
              <div class="d-flex gap-2">
                <a href="#" onclick="window.open('actualizar.php?id=XYZ456', '', 'width=600, height=500, toolbar=NO')" class="text-primary">
                  <i class="bi bi-pencil-square action-icon" title="Editar"></i>
                </a>
                <a href="#" onclick="confirmarEliminacion('XYZ456')" class="text-danger">
                  <i class="bi bi-trash action-icon" title="Eliminar"></i>
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <td>2025-03-05</td>
            <td>RST789</td>
            <td>Correctivo</td>
            <td>62.100 km</td>
            <td>Sistema eléctrico</td>
            <td>AutoTec</td>
            <td>$350.000</td>
            <td><span class="text-danger fw-bold">❌ Pendiente</span></td>
            <td>
              <div class="d-flex gap-2">
                <a href="#" onclick="window.open('actualizar.php?id=RST789', '', 'width=600, height=500, toolbar=NO')" class="text-primary">
                  <i class="bi bi-pencil-square action-icon" title="Editar"></i>
                </a>
                <a href="#" onclick="confirmarEliminacion('RST789')" class="text-danger">
                  <i class="bi bi-trash action-icon" title="Eliminar"></i>
                </a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <ul class="pagination mt-4" id="paginacion"></ul>
  </div>
  
</div>

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
