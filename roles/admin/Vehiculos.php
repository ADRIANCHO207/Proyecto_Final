<?php
session_start();
require_once('../../conecct/conex.php');
include '../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();
<<<<<<< HEAD
=======
$code = $_SESSION['documento'];
$sql = $con->prepare("SELECT * FROM vehiculos
    INNER JOIN usuarios ON vehiculos.Documento = usuarios.documento 
    INNER JOIN marca ON vehiculos.id_marca = marca.id_marca
    INNER JOIN estado_vehiculo ON vehiculos.id_estado = estado_vehiculo.id_estado
    WHERE placa= :code");
$sql->bindParam(':code', $code);
$sql->execute();
$fila = $sql->fetch();

>>>>>>> 3919379551deb4b47f9332d7afefd7d09b4a114f

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
<<<<<<< HEAD

}


$estado = $con->prepare("SELECT DISTINCT estado FROM estado_vehiculo ORDER BY estado DESC");
$estado->execute();
$estado = $estado->fetchAll(PDO::FETCH_COLUMN);

// Obtener estadísticas
$stats_query = $con->prepare(" SELECT COUNT(*) AS total_vehiculos,
        SUM(CASE WHEN vehiculos.id_estado = 1 THEN 1 ELSE 0 END) AS vehiculos_activos,
        SUM(CASE WHEN vehiculos.id_estado = 2 THEN 1 ELSE 0 END) AS vehiculos_inactivos,
        SUM(CASE WHEN vehiculos.id_estado = 3 THEN 1 ELSE 0 END) AS vehiculos_mantenimiento
    FROM vehiculos 
    INNER JOIN estado_vehiculo  ON vehiculos.id_estado = estado_vehiculo.id_estado
");

$stats_query->execute();
$stats = $stats_query->fetch(PDO::FETCH_ASSOC);

// Función para determinar la clase CSS del estado
function getEstadoClass($estado) {
    switch (strtolower($estado)) {
        case 'activo':
            return 'estado-activo';
        case 'en mantenimiento':
            return 'estado-mantenimiento';
        case 'inactivo':
            return 'estado-inactivo';
        default:
            return 'estado-activo';
    }
}
?>

=======
}



?>


>>>>>>> 3919379551deb4b47f9332d7afefd7d09b4a114f
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<<<<<<< HEAD
  <title>Gestión de Vehículos - Flotax AGC</title>
  <link rel="shortcut icon" href="../../css/img/logo_sinfondo.png">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/vehiculos.css">
</head>
<body>
  <?php include 'menu.html'; ?>

  <div class="content">
    <!-- Header de la página -->
    <div class="page-header">
      <div>
        <h1 class="page-title">
          <i class="bi bi-truck"></i>
          Gestión de Vehículos
        </h1>
        <p class="page-subtitle">Administración y control de la flota vehicular</p>
      </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="stats-overview">
      <div class="stat-card vehicles">
        <i class="bi bi-truck stat-icon"></i>
        <div class="stat-number"><?= $stats['total_vehiculos'] ?></div>
        <div class="stat-label">Total Vehículos</div>
      </div>
      <div class="stat-card active">
        <i class="bi bi-check-circle stat-icon"></i>
        <div class="stat-number"><?= $stats['vehiculos_activos'] ?></div>
        <div class="stat-label">Vehículos en Uso</div>
      </div>
      <div class="stat-card maintenance">
        <i class="bi bi-tools stat-icon"></i>
        <div class="stat-number"><?= $stats['vehiculos_mantenimiento'] ?></div>
        <div class="stat-label">En Mantenimiento</div>
      </div>
      <div class="stat-card inactive">
        <i class="bi bi-x-circle stat-icon"></i>
        <div class="stat-number"><?= $stats['vehiculos_inactivos'] ?></div>
        <div class="stat-label">Inactivos</div>
      </div>
    </div>

    <!-- Filtros -->
    <div class="filters-section">
      <div class="filter-group">
        <label class="filter-label">Estado</label>
        <select class="filter-select" id="filtroEstado" onchange="aplicarFiltros()">
          <option value="">Todos los Estados</option>
           <?php foreach ($estado as $estado): ?>
            <option value="<?= htmlspecialchars($estado) ?>">
              <?= htmlspecialchars($estado) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-group">
        <label class="filter-label">Marca</label>
        <select class="filter-select" id="filtroMarca" onchange="aplicarFiltros()">
          <option value="">Todas las marcas</option>
          <!-- Opciones dinámicas desde la base de datos -->
        </select>
      </div>
      <div class="filter-group">
        <label class="filter-label">Año</label>
        <select class="filter-select" id="filtroAno" onchange="aplicarFiltros()">
          <option value="">Todos los años</option>
          <!-- Opciones dinámicas -->
        </select>
      </div>
    </div>

    <!-- Controles superiores -->
    <div class="controls-section">
      <div class="buscador">
        <input type="text" id="buscar" class="form-control" placeholder="Buscar por placa, propietario, documento..." onkeyup="filtrarTabla()">
      </div>
    </div>

    <!-- Tabla de vehículos -->
    <div class="table-container">
      <div class="table-responsive">
        <table class="table" id="tablaUsuarios">
          <thead>
            <tr>
              <th><i class=""></i>#</th>
              <th><i class="bi bi-car-front"></i> Placa</th>
              <th><i class="bi bi-person-badge"></i> Documento</th>
              <th><i class="bi bi-person"></i> Propietario</th>
              <th><i class="bi bi-tags"></i> Marca</th>
              <th><i class="bi bi-calendar"></i> Modelo</th>
              <th><i class="bi bi-info-circle"></i> Estado</th>
              <th><i class="bi bi-speedometer2"></i> Kilometraje</th>
              <th><i class="bi bi-calendar-date"></i> Registro</th>
              <th><i class="bi bi-image"></i> Imagen</th>
              <th><i class="bi bi-tools"></i> Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = $con->prepare("SELECT *
                                 FROM vehiculos
                                 INNER JOIN usuarios ON vehiculos.documento = usuarios.documento 
                                 INNER JOIN marca ON vehiculos.id_marca = marca.id_marca
                                 INNER JOIN estado_vehiculo ON vehiculos.id_estado = estado_vehiculo.id_estado
                                 ORDER BY vehiculos.fecha_registro DESC");
            $sql->execute();
            $vehiculos = $sql->fetchAll(PDO::FETCH_ASSOC);
            $count = 1;
            
            if (count($vehiculos) > 0):
              foreach ($vehiculos as $resu):
            ?>
            <tr>    
              <td><span class="numero-fila"><?php echo $count++; ?></span></td>
              <td><span class="placa-cell"><?php echo htmlspecialchars($resu['placa']); ?></span></td>
              <td><span class="documento-cell"><?php echo htmlspecialchars($resu['Documento']); ?></span></td>
              <td><span class="propietario-cell"><?php echo htmlspecialchars($resu['nombre_completo']); ?></span></td>
              <td><span class="marca-cell"><?php echo htmlspecialchars($resu['nombre_marca']); ?></span></td>
              <td><span class="modelo-cell"><?php echo htmlspecialchars($resu['modelo']); ?></span></td>
              <td>
                <span class="estado-cell <?php echo getEstadoClass($resu['estado']); ?>">
                  <?php echo htmlspecialchars($resu['estado']); ?>
                </span>
              </td>
              <td><span class="kilometraje-cell"><?php echo number_format($resu['kilometraje_actual']); ?></span></td>
              <td><span class="fecha-cell"><?php echo date('d/m/Y', strtotime($resu['fecha_registro'])); ?></span></td>
              <td>
                <?php if (!empty($resu['foto_vehiculo'])): ?>
                  <img src="../usuario/<?php echo htmlspecialchars($resu['foto_vehiculo']); ?>" 
                       alt="Vehículo <?php echo htmlspecialchars($resu['placa']); ?>" 
                       class="vehicle-image"
                       onclick="mostrarImagenCompleta(this.src)">
                <?php else: ?>
                  <div class="no-image">
                    <i class="bi bi-image"></i>
                    <span>Sin imagen</span>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="actualizar-vehiculo.php" onclick="editarVehiculo('<?php echo $resu['placa']; ?>')" 
                     class="action-icon edit" title="Editar vehículo">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a href="#" onclick="eliminarVehiculo('<?php echo $resu['placa']; ?>', '<?php echo htmlspecialchars($resu['nombre_completo']); ?>')" 
                     class="action-icon delete" title="Eliminar vehículo">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php 
              endforeach;
            else:
            ?>
            <tr>
              <td colspan="11" class="no-data">
                <i class="bi bi-truck"></i>
                <h3>No hay vehículos registrados</h3>
                <p>Comienza agregando tu primer vehículo a la flota</p>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Paginación -->
    <div class="pagination-container">
      <nav>
        <ul class="pagination" id="paginacion"></ul>
      </nav>
    </div>

    <!-- Botón agregar -->
    <div class="boton-agregar">
      <a href="agregar_vehiculo.php" class="boton">
        <i class="bi bi-plus-circle"></i>
        <i class="bi bi-truck"></i>
        Agregar Vehículo
      </a>
    </div>
  </div>

  <!-- Modal para editar vehículo -->
<div class="modal fade" id="modalEditarVehiculo" tabindex="-1" aria-labelledby="modalEditarVehiculoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarVehiculoLabel">Editar Vehículo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="modalEditarVehiculoBody">
        <!-- El formulario de edición se cargará aquí vía AJAX -->
        <div class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Función de filtrado mejorada
    function filtrarTabla() {
      const input = document.getElementById('buscar').value.toLowerCase();
      const rows = document.querySelectorAll("#tablaUsuarios tbody tr");
      let visibleRows = 0;
      
      rows.forEach(row => {
        if (row.querySelector('.no-data')) return; // Skip no-data row
        
        const text = row.innerText.toLowerCase();
        const isVisible = text.includes(input);
        row.style.display = isVisible ? '' : 'none';
        if (isVisible) visibleRows++;
      });
      
      configurarPaginacion();
    }

    // Aplicar filtros combinados
    function aplicarFiltros() {
      const filtroEstado = document.getElementById('filtroEstado').value.toLowerCase();
      const filtroMarca = document.getElementById('filtroMarca').value.toLowerCase();
      const filtroAno = document.getElementById('filtroAno').value;
      const rows = document.querySelectorAll("#tablaUsuarios tbody tr");
      
      rows.forEach(row => {
        if (row.querySelector('.no-data')) return;
        
        const estado = row.querySelector('.estado-cell')?.textContent.toLowerCase() || '';
        const marca = row.querySelector('.marca-cell')?.textContent.toLowerCase() || '';
        const modelo = row.querySelector('.modelo-cell')?.textContent || '';
        
        let mostrar = true;
        
        if (filtroEstado && !estado.includes(filtroEstado)) mostrar = false;
        if (filtroMarca && !marca.includes(filtroMarca)) mostrar = false;
        if (filtroAno && modelo !== filtroAno) mostrar = false;
        
        row.style.display = mostrar ? '' : 'none';
      });
      
      configurarPaginacion();
    }

    // Paginación mejorada
    const filasPorPagina = 5;
    function configurarPaginacion() {
      const filas = Array.from(document.querySelectorAll('#tablaUsuarios tbody tr'))
                         .filter(row => row.style.display !== 'none' && !row.querySelector('.no-data'));
      const totalPaginas = Math.ceil(filas.length / filasPorPagina);
      const paginacion = document.getElementById('paginacion');

      function mostrarPagina(pagina) {
        document.querySelectorAll('#tablaUsuarios tbody tr').forEach(row => {
          if (!row.querySelector('.no-data')) {
            row.style.display = 'none';
          }
        });
        
        const inicio = (pagina - 1) * filasPorPagina;
        const fin = inicio + filasPorPagina;
        filas.slice(inicio, fin).forEach(row => {
          row.style.display = '';
        });
        
        document.querySelectorAll('#paginacion .page-item').forEach(btn => {
          btn.classList.remove('active');
        });
        document.querySelector(`#paginacion .page-item:nth-child(${pagina})`)?.classList.add('active');
      }

=======
  <title>Panel de Administrador</title>
  <link rel="stylesheet" href="css/stylesvehiculos.css" />
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
</head>
<body>
  <?php include 'menu.html'; ?> <!-- Sidebar fuera del contenido principal -->

  <div class="content">
    <div class="buscador mb-3">
      <input type="text" id="buscar" class="form-control" placeholder="Buscar por nombre, documento o correo" onkeyup="filtrarTabla()">
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-bordered" id="tablaUsuarios">
        <thead class="text-center">
    
                <tr>
                    <th>#</th>
                    <th>Placa</th>
                    <th>Documento</th>
                    <th>Propietario</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Estado</th>
                    <th>Kilometraje</th>
                    <th>Registro</th>
                    <th>Imagen</th>
                    <th>Accciones</th>

                    <!-- <th>SOAT(vencimiento)</th>
                    <th>SOAT</th>
                    <th>Revisión Tec.</th> -->

                </tr>
            </thead>
            <tbody>
                <?php
                $sql = $con->prepare("SELECT * FROM vehiculos
                                            INNER JOIN usuarios ON vehiculos.Documento = usuarios.documento 
                                            INNER JOIN marca ON vehiculos.id_marca = marca.id_marca
                                            INNER JOIN estado_vehiculo ON vehiculos.id_estado = estado_vehiculo.id_estado");
                $sql->execute();
                $fila = $sql->fetchAll(PDO::FETCH_ASSOC);
                $count = 1;
                foreach ($fila as $resu) {
                ?>
                <tr class="text-center">    
                    <td><?php echo $count++; ?></td>
                    <td><?php echo htmlspecialchars($resu['placa']); ?></td>
                    <td><?php echo htmlspecialchars($resu['Documento']); ?></td>
                    <td><?php echo htmlspecialchars($resu['nombre_completo']); ?></td>
                    <td><?php echo htmlspecialchars($resu['nombre_marca']); ?></td>
                    <td><?php echo htmlspecialchars($resu['modelo']); ?></td>
                    <td><?php echo htmlspecialchars($resu['estado']); ?></td>
                    <td><?php echo htmlspecialchars($resu['kilometraje_actual']); ?></td>
                    <td><?php echo htmlspecialchars(string: $resu['fecha_registro']); ?></td>
                    <td> <img src="../usuario/<?php echo htmlspecialchars($resu['foto_vehiculo']); ?>" alt="Imagen del vehículo" width="70px"></td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a href="#" onclick="window.open('actualizar.php?id=<?php echo $resu['documento']; ?>', '', 'width=600, height=500, toolbar=NO')" class="text-primary me-2">
                                <i class="bi bi-pencil-square action-icon" title="Editar"></i>
                            </a>
                            <a href="#" onclick="confirmarEliminacion('<?php echo $resu['documento']; ?>')" class="text-danger">
                                <i class="bi bi-trash action-icon" title="Eliminar"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <nav>
      <ul class="pagination justify-content-center" id="paginacion"></ul>
    </nav>
  <div class="boton-agregar">
        <a href="agregar_usuario.php" class="boton">
            <i class="bi bi-plus-circle"></i> <i class="bi bi-search"></i>Agregar Usuario
        </a>
    </div>
    </div>
  </div>
  
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
>>>>>>> 3919379551deb4b47f9332d7afefd7d09b4a114f
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
<<<<<<< HEAD

      if (totalPaginas > 0) {
        mostrarPagina(1);
      }
    }

    // Funciones de interacción
    function editarVehiculo(placa) {
      // Mostrar modal y spinner
      const modalBody = document.getElementById('modalEditarVehiculoBody');
      modalBody.innerHTML = `<div class="text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
      </div>`;
      var modal = new bootstrap.Modal(document.getElementById('modalEditarVehiculo'));
      modal.show();

      // Cargar el formulario de edición vía AJAX
      fetch(`actualizar.php?placa=${encodeURIComponent(placa)}`)
        .then(response => response.text())
        .then(html => {
          modalBody.innerHTML = html;
        })
        .catch(() => {
          modalBody.innerHTML = '<div class="alert alert-danger">No se pudo cargar el formulario.</div>';
        });
    }

    function eliminarVehiculo(placa, propietario) {
      if (confirm(`¿Está seguro de eliminar el vehículo ${placa} de ${propietario}?`)) {
        // Implementar eliminación
        window.location.href = `eliminar_vehiculo.php?placa=${placa}`;
      }
    }

    function mostrarImagenCompleta(src) {
      document.getElementById('imagenCompleta').src = src;
      new bootstrap.Modal(document.getElementById('modalImagen')).show();
    }

    // Inicializar cuando el DOM esté listo
    window.addEventListener('DOMContentLoaded', () => {
      configurarPaginacion();
      
      // Cargar opciones de filtros dinámicamente
      cargarOpcionesFiltros();
      
      // Agregar animación a las filas
      const rows = document.querySelectorAll('#tablaUsuarios tbody tr');
      rows.forEach((row, index) => {
        if (!row.querySelector('.no-data')) {
          row.style.animationDelay = `${index * 0.1}s`;
        }
      });
    });

    function cargarOpcionesFiltros() {
      // Cargar marcas únicas
      const marcas = [...new Set(Array.from(document.querySelectorAll('.marca-cell')).map(el => el.textContent))];
      const selectMarca = document.getElementById('filtroMarca');
      marcas.forEach(marca => {
        const option = document.createElement('option');
        option.value = marca.toLowerCase();
        option.textContent = marca;
        selectMarca.appendChild(option);
      });

      // Cargar años únicos
      const anos = [...new Set(Array.from(document.querySelectorAll('.modelo-cell')).map(el => el.textContent))];
      const selectAno = document.getElementById('filtroAno');
      anos.sort((a, b) => b - a).forEach(ano => {
        const option = document.createElement('option');
        option.value = ano;
        option.textContent = ano;
        selectAno.appendChild(option);
      });
    }
=======
    }

    crearBotones();
    mostrarPagina(1);
  }

  window.addEventListener('DOMContentLoaded', configurarPaginacion);

>>>>>>> 3919379551deb4b47f9332d7afefd7d09b4a114f
  </script>
</body>
</html>
