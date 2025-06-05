<?php
session_start();
require_once('../../conecct/conex.php');
include '../../includes/validarsession.php';

$db = new Database();
$con = $db->conectar();

// Validación de sesión
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

// // Obtener estadísticas de mantenimientos
// $stats_query = $con->prepare("SELECT 
//         COUNT(*) as total_mantenimientos,
//         SUM(CASE WHEN estado = 'Completado' THEN 1 ELSE 0 END) as mantenimientos_completados,
//         SUM(CASE WHEN estado = 'Pendiente' THEN 1 ELSE 0 END) as mantenimientos_pendientes,
//         SUM(costo) as costo_total
//     FROM mantenimiento
// ");
// $stats_query->execute();
// $stats = $stats_query->fetch(PDO::FETCH_ASSOC);

// // Función para determinar la clase CSS del estado
// function getEstadoClass($estado) {
//     switch (strtolower($estado)) {
//         case 'completado':
//         case 'listo':
//             return 'estado-completado';
//         case 'pendiente':
//             return 'estado-pendiente';
//         case 'en proceso':
//             return 'estado-proceso';
//         case 'cancelado':
//             return 'estado-cancelado';
//         default:
//             return 'estado-pendiente';
//     }
// }

// Función para determinar la clase CSS del tipo de mantenimiento
function getTipoClass($tipo) {
    switch (strtolower($tipo)) {
        case 'preventivo':
            return 'tipo-preventivo';
        case 'correctivo':
            return 'tipo-correctivo';
        case 'emergencia':
            return 'tipo-emergencia';
        default:
            return '';
    }
}

// Datos de ejemplo para mantenimientos (reemplazar con consulta real)
$mantenimientos = [
    [
        'id' => 1,
        'fecha' => '2025-05-20',
        'placa' => 'ABC123',
        'tipo' => 'Preventivo',
        'kilometraje' => '55000',
        'descripcion' => 'Cambio aceite y filtro',
        'taller' => 'Taller Saldaña',
        'costo' => '120000',
        'estado' => 'Completado',
        'detalles' => 'Se realizó cambio de aceite 20W50 sintético y filtro de aceite. También se revisaron niveles de líquidos y presión de neumáticos.',
        'tecnico' => 'Juan Pérez',
        'fecha_programada' => '2025-05-18',
        'fecha_completado' => '2025-05-20'
    ],
    [
        'id' => 2,
        'fecha' => '2025-05-15',
        'placa' => 'XYZ789',
        'tipo' => 'Correctivo',
        'kilometraje' => '78500',
        'descripcion' => 'Cambio de frenos delanteros',
        'taller' => 'AutoServicio Express',
        'costo' => '350000',
        'estado' => 'Completado',
        'detalles' => 'Se reemplazaron pastillas de freno delanteras y se rectificaron discos. Se verificó el sistema hidráulico.',
        'tecnico' => 'Carlos Rodríguez',
        'fecha_programada' => '2025-05-15',
        'fecha_completado' => '2025-05-15'
    ],
    [
        'id' => 3,
        'fecha' => '2025-06-05',
        'placa' => 'DEF456',
        'tipo' => 'Preventivo',
        'kilometraje' => '32000',
        'descripcion' => 'Revisión general',
        'taller' => 'Taller Oficial',
        'costo' => '180000',
        'estado' => 'Pendiente',
        'detalles' => 'Revisión de 30.000 km según manual del fabricante. Incluye cambio de aceite, filtros y revisión de 21 puntos.',
        'tecnico' => 'Por asignar',
        'fecha_programada' => '2025-06-05',
        'fecha_completado' => null
    ],
    [
        'id' => 4,
        'fecha' => '2025-05-28',
        'placa' => 'GHI789',
        'tipo' => 'Emergencia',
        'kilometraje' => '45200',
        'descripcion' => 'Reparación sistema eléctrico',
        'taller' => 'ElectriAutos',
        'costo' => '280000',
        'estado' => 'En proceso',
        'detalles' => 'Falla en el sistema eléctrico. Se está revisando alternador y batería. Posible reemplazo de regulador de voltaje.',
        'tecnico' => 'Roberto Gómez',
        'fecha_programada' => '2025-05-28',
        'fecha_completado' => null
    ],
    [
        'id' => 5,
        'fecha' => '2025-05-10',
        'placa' => 'JKL012',
        'tipo' => 'Correctivo',
        'kilometraje' => '62300',
        'descripcion' => 'Cambio de amortiguadores',
        'taller' => 'Taller Saldaña',
        'costo' => '420000',
        'estado' => 'Completado',
        'detalles' => 'Reemplazo de amortiguadores traseros y delanteros. Se alineó y balanceó el vehículo.',
        'tecnico' => 'Juan Pérez',
        'fecha_programada' => '2025-05-08',
        'fecha_completado' => '2025-05-10'
    ]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Historial de Mantenimientos - Flotax AGC</title>
  <link rel="shortcut icon" href="../../css/img/logo_sinfondo.png">
  <link rel="stylesheet" href="css/mantenimiento.css" />
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <?php include 'menu.html'; ?>

  <div class="content">
    <!-- Header de la página -->
    <div class="page-header">
      <div>
        <h1 class="page-title">
          <i class="bi bi-tools"></i>
          Historial de Mantenimientos
        </h1>
        <p class="page-subtitle">Registro y seguimiento de mantenimientos vehiculares</p>
      </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="stats-overview">
      <div class="stat-card total">
        <i class="bi bi-clipboard-check stat-icon"></i>
        <div class="stat-number"><?= $stats['total_mantenimientos'] ?? 5 ?></div>
        <div class="stat-label">Total Mantenimientos</div>
      </div>
      <div class="stat-card completados">
        <i class="bi bi-check-circle stat-icon"></i>
        <div class="stat-number"><?= $stats['mantenimientos_completados'] ?? 3 ?></div>
        <div class="stat-label">Completados</div>
      </div>
      <div class="stat-card pendientes">
        <i class="bi bi-hourglass-split stat-icon"></i>
        <div class="stat-number"><?= $stats['mantenimientos_pendientes'] ?? 2 ?></div>
        <div class="stat-label">Pendientes</div>
      </div>
      <div class="stat-card costos">
        <i class="bi bi-cash-stack stat-icon"></i>
        <div class="stat-number">$<?= number_format($stats['costo_total'] ?? 1350000, 0, ',', '.') ?></div>
        <div class="stat-label">Costo Total</div>
      </div>
    </div>

    <!-- Filtros -->
    <div class="filters-section">
      <div class="filter-group">
        <label class="filter-label">Estado</label>
        <select class="filter-select" id="filtroEstado" onchange="aplicarFiltros()">
          <option value="">Todos los estados</option>
          <option value="completado">Completado</option>
          <option value="pendiente">Pendiente</option>
          <option value="proceso">En proceso</option>
          <option value="cancelado">Cancelado</option>
        </select>
      </div>
      <div class="filter-group">
        <label class="filter-label">Tipo</label>
        <select class="filter-select" id="filtroTipo" onchange="aplicarFiltros()">
          <option value="">Todos los tipos</option>
          <option value="preventivo">Preventivo</option>
          <option value="correctivo">Correctivo</option>
          <option value="emergencia">Emergencia</option>
        </select>
      </div>
      <div class="filter-group">
        <label class="filter-label">Desde</label>
        <input type="date" class="filter-date" id="filtroDesde" onchange="aplicarFiltros()">
      </div>
      <div class="filter-group">
        <label class="filter-label">Hasta</label>
        <input type="date" class="filter-date" id="filtroHasta" onchange="aplicarFiltros()">
      </div>
      <div class="filter-group">
        <label class="filter-label">Vehículo</label>
        <select class="filter-select" id="filtroVehiculo" onchange="aplicarFiltros()">
          <option value="">Todos los vehículos</option>
          <option value="ABC123">ABC123</option>
          <option value="XYZ789">XYZ789</option>
          <option value="DEF456">DEF456</option>
          <option value="GHI789">GHI789</option>
          <option value="JKL012">JKL012</option>
        </select>
      </div>
    </div>

    <!-- Controles superiores -->
    <div class="controls-section">
      <div class="buscador">
        <input type="text" id="buscar" class="form-control" placeholder="Buscar por placa, taller, descripción..." onkeyup="filtrarTabla()">
      </div>
    </div>

    <!-- Tabla de mantenimientos -->
    <div class="table-container">
      <div class="table-responsive">
        <table class="table" id="tablaUsuarios">
          <thead>
            <tr>
              <th><i class="bi bi-calendar"></i> Fecha</th>
              <th><i class="bi bi-car-front"></i> Placa</th>
              <th><i class="bi bi-tag"></i> Tipo</th>
              <th><i class="bi bi-speedometer2"></i> Kilometraje</th>
              <th><i class="bi bi-card-text"></i> Descripción</th>
              <th><i class="bi bi-building"></i> Taller</th>
              <th><i class="bi bi-cash"></i> Costo</th>
              <th><i class="bi bi-info-circle"></i> Estado</th>
              <th><i class="bi bi-tools"></i> Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($mantenimientos as $mant): ?>
            <tr>
              <td><span class="fecha-cell"><?= date('d/m/Y', strtotime($mant['fecha'])) ?></span></td>
              <td><span class="placa-cell"><?= htmlspecialchars($mant['placa']) ?></span></td>
              <td><span class="tipo-cell <?= getTipoClass($mant['tipo']) ?>"><?= htmlspecialchars($mant['tipo']) ?></span></td>
              <td><span class="kilometraje-cell"><?= number_format($mant['kilometraje'], 0, ',', '.') ?> km</span></td>
              <td class="expandable-cell">
                <span class="descripcion-cell"><?= htmlspecialchars($mant['descripcion']) ?></span>
                <div class="expanded-details">
                  <h5>Detalles del mantenimiento</h5>
                  <p><?= htmlspecialchars($mant['detalles']) ?></p>
                  <p><span class="label">Técnico:</span> <span class="value"><?= htmlspecialchars($mant['tecnico']) ?></span></p>
                  <p><span class="label">Programado:</span> <span class="value"><?= date('d/m/Y', strtotime($mant['fecha_programada'])) ?></span></p>
                  <?php if ($mant['fecha_completado']): ?>
                  <p><span class="label">Completado:</span> <span class="value"><?= date('d/m/Y', strtotime($mant['fecha_completado'])) ?></span></p>
                  <?php endif; ?>
                </div>
              </td>
              <td><span class="taller-cell"><?= htmlspecialchars($mant['taller']) ?></span></td>
              <td><span class="costo-cell">$<?= number_format($mant['costo'], 0, ',', '.') ?></span></td>
              <td>
                <span class="estado-cell <?= getEstadoClass($mant['estado']) ?>">
                  <?php if ($mant['estado'] == 'Completado'): ?>
                    <i class="bi bi-check-circle-fill"></i>
                  <?php elseif ($mant['estado'] == 'Pendiente'): ?>
                    <i class="bi bi-clock"></i>
                  <?php elseif ($mant['estado'] == 'En proceso'): ?>
                    <i class="bi bi-gear"></i>
                  <?php else: ?>
                    <i class="bi bi-x-circle"></i>
                  <?php endif; ?>
                  <?= htmlspecialchars($mant['estado']) ?>
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="#" onclick="verDetalles(<?= $mant['id'] ?>)" class="action-icon view" title="Ver detalles">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="#" onclick="editarMantenimiento(<?= $mant['id'] ?>)" class="action-icon edit" title="Editar">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <a href="#" onclick="eliminarMantenimiento(<?= $mant['id'] ?>, '<?= $mant['placa'] ?>')" class="action-icon delete" title="Eliminar">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Paginación -->
    <div class="pagination-container">
      <ul class="pagination" id="paginacion"></ul>
    </div>

    <!-- Botón agregar -->
    <div class="boton-agregar">
      <a href="agregar_mantenimiento.php" class="boton">
        <i class="bi bi-plus-circle"></i>
        <i class="bi bi-tools"></i>
        Registrar Mantenimiento
      </a>
    </div>
  </div>

  <!-- Modal para detalles -->
  <div class="modal fade" id="modalDetalles" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalles del Mantenimiento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="detallesContenido">
          <!-- Contenido dinámico -->
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
      const filtroTipo = document.getElementById('filtroTipo').value.toLowerCase();
      const filtroVehiculo = document.getElementById('filtroVehiculo').value.toLowerCase();
      const filtroDesde = document.getElementById('filtroDesde').value;
      const filtroHasta = document.getElementById('filtroHasta').value;
      
      const rows = document.querySelectorAll("#tablaUsuarios tbody tr");
      
      rows.forEach(row => {
        const estado = row.querySelector('.estado-cell')?.textContent.toLowerCase() || '';
        const tipo = row.querySelector('.tipo-cell')?.textContent.toLowerCase() || '';
        const placa = row.querySelector('.placa-cell')?.textContent.toLowerCase() || '';
        const fechaText = row.querySelector('.fecha-cell')?.textContent || '';
        
        // Convertir fecha de dd/mm/yyyy a yyyy-mm-dd para comparación
        const fechaParts = fechaText.split('/');
        const fecha = fechaParts.length === 3 ? 
          `${fechaParts[2]}-${fechaParts[1]}-${fechaParts[0]}` : '';
        
        let mostrar = true;
        
        if (filtroEstado && !estado.includes(filtroEstado)) mostrar = false;
        if (filtroTipo && !tipo.includes(filtroTipo)) mostrar = false;
        if (filtroVehiculo && !placa.includes(filtroVehiculo)) mostrar = false;
        if (filtroDesde && fecha < filtroDesde) mostrar = false;
        if (filtroHasta && fecha > filtroHasta) mostrar = false;
        
        row.style.display = mostrar ? '' : 'none';
      });
      
      configurarPaginacion();
    }

    // Paginación mejorada
    const filasPorPagina = 5;
    function configurarPaginacion() {
      const filas = Array.from(document.querySelectorAll('#tablaUsuarios tbody tr'))
                         .filter(row => row.style.display !== 'none');
      const totalPaginas = Math.ceil(filas.length / filasPorPagina);
      const paginacion = document.getElementById('paginacion');

      function mostrarPagina(pagina) {
        document.querySelectorAll('#tablaUsuarios tbody tr').forEach(row => {
          row.style.display = 'none';
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

      if (totalPaginas > 0) {
        mostrarPagina(1);
      }
    }

    // Funciones de interacción
    function verDetalles(id) {
      // Aquí implementarías la lógica para cargar los detalles del mantenimiento
      // y mostrarlos en el modal
      const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
      
      // Simulación de carga de datos
      const detallesContenido = document.getElementById('detallesContenido');
      detallesContenido.innerHTML = `
        <div class="p-3">
          <div class="mb-4">
            <h4 class="text-primary">Mantenimiento #${id}</h4>
            <p class="text-muted">Información detallada del mantenimiento</p>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <p><strong>Vehículo:</strong> ABC123</p>
              <p><strong>Tipo:</strong> Preventivo</p>
              <p><strong>Fecha:</strong> 20/05/2025</p>
              <p><strong>Kilometraje:</strong> 55.000 km</p>
            </div>
            <div class="col-md-6">
              <p><strong>Taller:</strong> Taller Saldaña</p>
              <p><strong>Técnico:</strong> Juan Pérez</p>
              <p><strong>Costo:</strong> $120.000</p>
              <p><strong>Estado:</strong> <span class="badge bg-success">Completado</span></p>
            </div>
          </div>
          
          <div class="mb-3">
            <h5>Descripción</h5>
            <p>Cambio aceite y filtro</p>
          </div>
          
          <div class="mb-3">
            <h5>Detalles del trabajo</h5>
            <p>Se realizó cambio de aceite 20W50 sintético y filtro de aceite. También se revisaron niveles de líquidos y presión de neumáticos.</p>
          </div>
          
          <div class="mb-3">
            <h5>Observaciones</h5>
            <p>Se recomienda revisar frenos en el próximo mantenimiento.</p>
          </div>
        </div>
      `;
      
      modal.show();
    }

    function editarMantenimiento(id) {
      window.open(`editar_mantenimiento.php?id=${id}`, '', 'width=800, height=600, toolbar=NO');
    }

    function eliminarMantenimiento(id, placa) {
      if (confirm(`¿Está seguro de eliminar el mantenimiento #${id} del vehículo ${placa}?`)) {
        // Implementar eliminación
        console.log('Eliminar mantenimiento:', id);
      }
    }

    // Inicializar cuando el DOM esté listo
    window.addEventListener('DOMContentLoaded', () => {
      configurarPaginacion();
      
      // Agregar animación a las filas
      const rows = document.querySelectorAll('#tablaUsuarios tbody tr');
      rows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.1}s`;
      });
    });
  </script>
</body>
</html>
