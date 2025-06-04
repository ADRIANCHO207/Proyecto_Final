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
  <title>Control de Documentos - Flota Vehicular</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/stylesvehiculos.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

</head>
<body>

  <?php include 'menu.html'; ?> 
  <div class="content">
    <div class="navbar">
      <h1>Flota Vehicular</h1>
      <div class="notification">
        <i class="bi bi-bell"></i>
        <span class="badge">3</span>
      </div>
    </div>

    <div class="container py-4">
      <h2 class="mb-4">Control de Documentos</h2>

      <div class="buscador">
        <input type="text" id="buscar" placeholder="Buscar por placa u otro dato..." onkeyup="filtrarTabla()">
      </div>

      <div class="boton-agregar text-end">
        <button class="btn btn-success">➕ Nuevo Documento</button>
      </div>

      <div class="table-responsive">
        <table class="table table-striped" id="tablaUsuarios">
          <thead>
            <tr>
              <th>Placa</th>
              <th>SOAT</th>
              <th>TecnoMecánica</th>
              <th>Licencia Conductor</th>
              <th>Tarjeta de Propiedad</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>ABC123</td>
              <td class="status-vigente">Vigente (vence: 12/08/2025)</td>
              <td class="status-vencido">Vencido (vence: 01/04/2024)</td>
              <td class="status-vigente">Vigente</td>
              <td><span class="documento-link">Ver PDF</span></td>
              <td>

                <div class="d-flex justify-content-center">
                            <a href="#" onclick="window.open('actualizar.php?id=<?php echo $resu['documento']; ?>', '', 'width=600, height=500, toolbar=NO')" class="text-primary me-2">
                                <i class="bi bi-pencil-square action-icon" title="Editar"></i>
                            </a>
                            <a href="#" onclick="confirmarEliminacion('<?php echo $resu['documento']; ?>')" class="text-danger">
                                <i class="bi bi-trash action-icon" title="Eliminar"></i>
                            </a>
              </td>
            </tr>
            <tr>
              <td>XYZ789</td>
              <td class="status-proximo">Por vencer (15/06/2025)</td>
              <td class="status-vigente">Vigente</td>
              <td class="status-vigente">Vigente</td>
              <td><span class="documento-link">Ver PDF</span></td>
              <td>
           <div class="d-flex justify-content-center">
                            <a href="#" onclick="window.open('actualizar.php?id=<?php echo $resu['documento']; ?>', '', 'width=600, height=500, toolbar=NO')" class="text-primary me-2">
                                <i class="bi bi-pencil-square action-icon" title="Editar"></i>
                            </a>
                            <a href="#" onclick="confirmarEliminacion('<?php echo $resu['documento']; ?>')" class="text-danger">
                                <i class="bi bi-trash action-icon" title="Eliminar"></i>
                            </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <ul class="pagination" id="paginacion"></ul>
    </div>
  </div>

<script>
function filtrarTabla() {
  const input = document.getElementById('buscar').value.toLowerCase();
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
