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


// Check for documento in session
$documento = $_SESSION['documento'] ?? null;
if (!$documento) {
    header('Location: ../../login.php');
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
  <title>Panel de Administrador</title>
  <link rel="stylesheet" href="css/stylesvehiculos.css" />
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
</head>
<body>
  
 

  <div class="sidebar">
    <?php include 'menu.html'; ?> 
  </div>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Control de Documentos - Flota Vehicular</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .status-vigente { background-color: #d4edda; }
    .status-proximo { background-color: #fff3cd; }
    .status-vencido { background-color: #f8d7da; }
    .documento-link { text-decoration: underline; color: #0d6efd; cursor: pointer; }
  </style>
</head>
<body>
  <div class="container py-4">
    <h2 class="mb-4">🗂️ Control de Documentos</h2>

    <div class="mb-3">
      <button class="btn btn-success">➕ Nuevo Documento</button>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
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
          <!-- Ejemplo vehículo 1 -->
          <tr>
            <td>ABC123</td>
            <td class="status-vigente">Vigente (vence: 12/08/2025)</td>
            <td class="status-vencido">Vencido (vence: 01/04/2024)</td>
            <td class="status-vigente">Vigente</td>
            <td><span class="documento-link">Ver PDF</span></td>
            <td>
              <button class="btn btn-primary btn-sm">Editar</button>
              <button class="btn btn-danger btn-sm">Eliminar</button>
            </td>
          </tr>

          <!-- Ejemplo vehículo 2 -->
          <tr>
            <td>XYZ789</td>
            <td class="status-proximo">Por vencer (15/06/2025)</td>
            <td class="status-vigente">Vigente</td>
            <td class="status-vigente">Vigente</td>
            <td><span class="documento-link">Ver PDF</span></td>
            <td>
              <button class="btn btn-primary btn-sm">Editar</button>
              <button class="btn btn-danger btn-sm">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

<script>

        function filtrarTabla() {
        const input = document.getElementById('buscar');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('tablaUsuarios');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let match = false;

            for (let j = 0; j < cells.length; j++) {
                if (cells[j]) {
                    const text = cells[j].textContent || cells[j].innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
            }

            rows[i].style.display = match ? '' : 'none';
        }
    }


  const filasPorPagina = 5; // Cambia este valor si deseas más/menos filas por página

  function configurarPaginacion() {
    const tabla = document.getElementById('tablaUsuarios');
    const filas = tabla.querySelectorAll('tbody tr');
    const totalFilas = filas.length;
    const totalPaginas = Math.ceil(totalFilas / filasPorPagina);
    const paginacion = document.getElementById('paginacion');

    function mostrarPagina(pagina) {
      let inicio = (pagina - 1) * filasPorPagina;
      let fin = inicio + filasPorPagina;

      filas.forEach((fila, index) => {
        fila.style.display = (index >= inicio && index < fin) ? '' : 'none';
      });

      // actualizar botones activos
      const botones = paginacion.querySelectorAll('li');
      botones.forEach(btn => btn.classList.remove('active'));
      if (botones[pagina - 1]) botones[pagina - 1].classList.add('active');
    }

    function crearBotones() {
      paginacion.innerHTML = '';
      for (let i = 1; i <= totalPaginas; i++) {
        const li = document.createElement('li');
        li.className = 'page-item' + (i === 1 ? ' active' : '');
        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.textContent = i;
        a.addEventListener('click', function (e) {
          e.preventDefault();
          mostrarPagina(i);
        });
        li.appendChild(a);
        paginacion.appendChild(li);
      }
    }

    crearBotones();
    mostrarPagina(1);
  }

  window.addEventListener('DOMContentLoaded', configurarPaginacion);

  </script>
</body>
</html>
