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

// Función para obtener el icono según el tipo de evento
function getEventIcon($tipo) {
    switch (strtolower($tipo)) {
        case 'mantenimiento':
            return 'bi-tools';
        case 'multa':
            return 'bi-exclamation-triangle';
        case 'documento':
            return 'bi-file-earmark-text';
        case 'alerta':
            return 'bi-bell';
        case 'actividad':
            return 'bi-activity';
        case 'registro':
            return 'bi-plus-circle';
        case 'actualizacion':
            return 'bi-pencil-square';
        case 'eliminacion':
            return 'bi-trash';
        default:
            return 'bi-circle';
    }
}

// Función para formatear fechas
function formatearFecha($fecha) {
    return date('d/m/Y H:i', strtotime($fecha));
}

// Datos de ejemplo para el historial (reemplazar con consultas reales)
$historial = [
    [
        'id' => 1,
        'tipo' => 'mantenimiento',
        'fecha' => '2025-05-20 14:30:00',
        'vehiculo' => 'ABC123',
        'descripcion' => 'Cambio de aceite y filtro completado',
        'detalles' => 'Se realizó cambio de aceite 20W50 sintético y filtro de aceite. Kilometraje: 55,000 km.',
        'usuario' => 'Juan Pérez',
        'costo' => '120000',
        'taller' => 'Taller Saldaña',
        'estado' => 'completado'
    ],
    [
        'id' => 2,
        'tipo' => 'multa',
        'fecha' => '2025-05-18 09:15:00',
        'vehiculo' => 'XYZ789',
        'descripcion' => 'Nueva multa registrada por exceso de velocidad',
        'detalles' => 'Multa por exceso de velocidad en zona urbana. Valor: $180,000. Fecha límite de pago: 15/06/2025.',
        'usuario' => 'Sistema Automático',
        'valor' => '180000',
        'ubicacion' => 'Calle 26 con Carrera 15',
        'estado' => 'pendiente'
    ],
    [
        'id' => 3,
        'tipo' => 'documento',
        'fecha' => '2025-05-15 11:45:00',
        'vehiculo' => 'DEF456',
        'descripcion' => 'SOAT renovado exitosamente',
        'detalles' => 'Se renovó el SOAT del vehículo con vigencia hasta el 15/05/2026. Aseguradora: Seguros Bolívar.',
        'usuario' => 'María González',
        'vigencia' => '2026-05-15',
        'aseguradora' => 'Seguros Bolívar',
        'estado' => 'vigente'
    ],
    [
        'id' => 4,
        'tipo' => 'alerta',
        'fecha' => '2025-05-12 16:20:00',
        'vehiculo' => 'GHI789',
        'descripcion' => 'Alerta de mantenimiento programado resuelta',
        'detalles' => 'Se resolvió la alerta de mantenimiento programado para los 50,000 km. Mantenimiento realizado.',
        'usuario' => 'Carlos Rodríguez',
        'tipo_alerta' => 'Mantenimiento programado',
        'prioridad' => 'media',
        'estado' => 'resuelta'
    ],
    [
        'id' => 5,
        'tipo' => 'actividad',
        'fecha' => '2025-05-10 08:30:00',
        'vehiculo' => 'JKL012',
        'descripcion' => 'Nuevo vehículo registrado en el sistema',
        'detalles' => 'Se registró un nuevo vehículo Toyota Hilux 2023 en el sistema de gestión de flota.',
        'usuario' => 'Administrador',
        'marca' => 'Toyota',
        'modelo' => '2023',
        'estado' => 'activo'
    ],
    [
        'id' => 6,
        'tipo' => 'mantenimiento',
        'fecha' => '2025-05-08 13:15:00',
        'vehiculo' => 'MNO345',
        'descripcion' => 'Reparación de frenos completada',
        'detalles' => 'Se reemplazaron pastillas de freno delanteras y se rectificaron discos. Costo total: $350,000.',
        'usuario' => 'Roberto Gómez',
        'costo' => '350000',
        'taller' => 'AutoServicio Express',
        'estado' => 'completado'
    ],
    [
        'id' => 7,
        'tipo' => 'documento',
        'fecha' => '2025-05-05 10:00:00',
        'vehiculo' => 'PQR678',
        'descripcion' => 'Revisión técnico-mecánica vencida',
        'detalles' => 'La revisión técnico-mecánica del vehículo ha vencido. Es necesario renovarla urgentemente.',
        'usuario' => 'Sistema Automático',
        'fecha_vencimiento' => '2025-05-01',
        'centro' => 'CDA Bogotá',
        'estado' => 'vencido'
    ],
    [
        'id' => 8,
        'tipo' => 'actividad',
        'fecha' => '2025-05-03 15:45:00',
        'vehiculo' => 'STU901',
        'descripcion' => 'Actualización de información del conductor',
        'detalles' => 'Se actualizó la información del conductor principal del vehículo. Nueva licencia registrada.',
        'usuario' => 'Ana Martínez',
        'conductor' => 'Luis Fernández',
        'licencia' => 'C2345678901',
        'estado' => 'actualizado'
    ]
];

// Calcular estadísticas
$total_eventos = count($historial);
$mantenimientos = count(array_filter($historial, fn($h) => $h['tipo'] === 'mantenimiento'));
$multas = count(array_filter($historial, fn($h) => $h['tipo'] === 'multa'));
$documentos = count(array_filter($historial, fn($h) => $h['tipo'] === 'documento'));
$alertas = count(array_filter($historial, fn($h) => $h['tipo'] === 'alerta'));
$actividades = count(array_filter($historial, fn($h) => $h['tipo'] === 'actividad'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Historiales del Sistema - Flotax AGC</title>
  <link rel="shortcut icon" href="../../css/img/logo_sinfondo.png">
  <link rel="stylesheet" href="css/historial.css" />
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
          <i class="bi bi-clock-history"></i>
          Historiales del Sistema
        </h1>
        <p class="page-subtitle">Registro completo de actividades y eventos del sistema</p>
      </div>
      <div class="page-actions">
        <a href="#" onclick="exportarHistorial()" class="export-btn">
          <i class="bi bi-download"></i>
          Exportar Historial
        </a>
      </div>
    </div>

    <!-- Estadísticas del historial -->
    <div class="history-stats">
      <div class="stat-card mantenimientos" onclick="filtrarPorTipo('mantenimiento')">
        <div class="stat-number mantenimientos">
          <span><?= $mantenimientos ?></span>
          <i class="bi bi-tools stat-icon"></i>
        </div>
        <div class="stat-label">Mantenimientos</div>
      </div>
      <div class="stat-card multas" onclick="filtrarPorTipo('multa')">
        <div class="stat-number multas">
          <span><?= $multas ?></span>
          <i class="bi bi-exclamation-triangle stat-icon"></i>
        </div>
        <div class="stat-label">Multas</div>
      </div>
      <div class="stat-card documentos" onclick="filtrarPorTipo('documento')">
        <div class="stat-number documentos">
          <span><?= $documentos ?></span>
          <i class="bi bi-file-earmark-text stat-icon"></i>
        </div>
        <div class="stat-label">Documentos</div>
      </div>
      <div class="stat-card alertas" onclick="filtrarPorTipo('alerta')">
        <div class="stat-number alertas">
          <span><?= $alertas ?></span>
          <i class="bi bi-bell stat-icon"></i>
        </div>
        <div class="stat-label">Alertas</div>
      </div>
      <div class="stat-card actividades" onclick="filtrarPorTipo('actividad')">
        <div class="stat-number actividades">
          <span><?= $actividades ?></span>
          <i class="bi bi-activity stat-icon"></i>
        </div>
        <div class="stat-label">Actividades</div>
      </div>
    </div>

    <!-- Filtros avanzados -->
    <div class="filters-section">
      <div class="filters-header">
        <h3 class="filters-title">
          <i class="bi bi-funnel"></i>
          Filtros Avanzados
        </h3>
        <button class="filters-toggle" onclick="toggleFilters()">
          <i class="bi bi-chevron-down"></i>
          <span>Mostrar filtros</span>
        </button>
      </div>
      
      <div class="filters-grid" id="filtersGrid" style="display: none;">
        <div class="filter-group">
          <label class="filter-label">Tipo de Evento</label>
          <select class="filter-control" id="filtroTipo" onchange="aplicarFiltros()">
            <option value="">Todos los tipos</option>
            <option value="mantenimiento">Mantenimientos</option>
            <option value="multa">Multas</option>
            <option value="documento">Documentos</option>
            <option value="alerta">Alertas</option>
            <option value="actividad">Actividades</option>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label">Vehículo</label>
          <input type="text" class="filter-control" id="filtroVehiculo" placeholder="Placa del vehículo" onkeyup="aplicarFiltros()">
        </div>
        <div class="filter-group">
          <label class="filter-label">Fecha Desde</label>
          <input type="date" class="filter-control" id="filtroDesde" onchange="aplicarFiltros()">
        </div>
        <div class="filter-group">
          <label class="filter-label">Fecha Hasta</label>
          <input type="date" class="filter-control" id="filtroHasta" onchange="aplicarFiltros()">
        </div>
        <div class="filter-group">
          <label class="filter-label">Usuario</label>
          <input type="text" class="filter-control" id="filtroUsuario" placeholder="Nombre del usuario" onkeyup="aplicarFiltros()">
        </div>
        <div class="filter-group">
          <div class="filter-actions">
            <button class="filter-btn primary" onclick="aplicarFiltros()">
              <i class="bi bi-search"></i>
              Buscar
            </button>
            <button class="filter-btn secondary" onclick="limpiarFiltros()">
              <i class="bi bi-arrow-clockwise"></i>
              Limpiar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Vista de historial -->
    <div class="history-view">
      <div class="history-header">
        <h3 class="history-title">
          <i class="bi bi-list-ul"></i>
          Historial de Eventos
        </h3>
        <div class="view-toggle">
          <button class="view-btn active" onclick="cambiarVista('timeline')" id="timelineBtn">
            <i class="bi bi-clock"></i>
            Timeline
          </button>
          <button class="view-btn" onclick="cambiarVista('table')" id="tableBtn">
            <i class="bi bi-table"></i>
            Tabla
          </button>
        </div>
        <span class="history-count" id="historyCount"><?= $total_eventos ?> eventos</span>
      </div>

      <!-- Vista Timeline -->
      <div class="timeline-container" id="timelineView">
        <div class="timeline">
          <?php foreach ($historial as $evento): ?>
          <div class="timeline-item <?= $evento['tipo'] ?>" 
               data-tipo="<?= $evento['tipo'] ?>" 
               data-vehiculo="<?= strtolower($evento['vehiculo']) ?>"
               data-fecha="<?= date('Y-m-d', strtotime($evento['fecha'])) ?>"
               data-usuario="<?= strtolower($evento['usuario']) ?>">
            
            <div class="timeline-content">
              <div class="timeline-header">
                <div class="timeline-type <?= $evento['tipo'] ?>">
                  <i class="<?= getEventIcon($evento['tipo']) ?>"></i>
                  <?= ucfirst($evento['tipo']) ?>
                </div>
                <div class="timeline-date">
                  <i class="bi bi-calendar"></i>
                  <?= formatearFecha($evento['fecha']) ?>
                </div>
              </div>
              
              <div class="timeline-vehicle"><?= htmlspecialchars($evento['vehiculo']) ?></div>
              
              <div class="timeline-description">
                <?= htmlspecialchars($evento['descripcion']) ?>
              </div>
              
              <div class="timeline-details">
                <div class="timeline-detail">
                  <span class="timeline-detail-label">Usuario:</span>
                  <span class="timeline-detail-value"><?= htmlspecialchars($evento['usuario']) ?></span>
                </div>
                
                <?php if (isset($evento['costo'])): ?>
                <div class="timeline-detail">
                  <span class="timeline-detail-label">Costo:</span>
                  <span class="timeline-detail-value">$<?= number_format($evento['costo'], 0, ',', '.') ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($evento['taller'])): ?>
                <div class="timeline-detail">
                  <span class="timeline-detail-label">Taller:</span>
                  <span class="timeline-detail-value"><?= htmlspecialchars($evento['taller']) ?></span>
                </div>
                <?php endif; ?>
                
                <div class="timeline-detail">
                  <span class="timeline-detail-label">Estado:</span>
                  <span class="timeline-detail-value">
                    <span class="status-indicator <?= $evento['estado'] === 'completado' || $evento['estado'] === 'vigente' || $evento['estado'] === 'activo' ? 'success' : ($evento['estado'] === 'pendiente' ? 'warning' : 'danger') ?>"></span>
                    <?= ucfirst($evento['estado']) ?>
                  </span>
                </div>
              </div>
              
              <div class="timeline-actions">
                <a href="#" onclick="verDetalles(<?= $evento['id'] ?>)" class="timeline-action view">
                  <i class="bi bi-eye"></i>
                  Ver detalles
                </a>
                <?php if ($evento['tipo'] === 'documento' || $evento['tipo'] === 'mantenimiento'): ?>
                <a href="#" onclick="descargarDocumento(<?= $evento['id'] ?>)" class="timeline-action download">
                  <i class="bi bi-download"></i>
                  Descargar
                </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Vista Tabla -->
      <div class="table-container" id="tableView" style="display: none;">
        <div class="table-responsive">
          <table class="table" id="historyTable">
            <thead>
              <tr>
                <th><i class="bi bi-calendar"></i> Fecha</th>
                <th><i class="bi bi-tag"></i> Tipo</th>
                <th><i class="bi bi-car-front"></i> Vehículo</th>
                <th><i class="bi bi-card-text"></i> Descripción</th>
                <th><i class="bi bi-person"></i> Usuario</th>
                <th><i class="bi bi-info-circle"></i> Estado</th>
                <th><i class="bi bi-tools"></i> Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($historial as $evento): ?>
              <tr data-tipo="<?= $evento['tipo'] ?>" 
                  data-vehiculo="<?= strtolower($evento['vehiculo']) ?>"
                  data-fecha="<?= date('Y-m-d', strtotime($evento['fecha'])) ?>"
                  data-usuario="<?= strtolower($evento['usuario']) ?>">
                <td><?= formatearFecha($evento['fecha']) ?></td>
                <td>
                  <span class="event-type <?= $evento['tipo'] ?>">
                    <i class="<?= getEventIcon($evento['tipo']) ?>"></i>
                    <?= ucfirst($evento['tipo']) ?>
                  </span>
                </td>
                <td><strong><?= htmlspecialchars($evento['vehiculo']) ?></strong></td>
                <td class="tooltip-trigger" data-tooltip="<?= htmlspecialchars($evento['detalles']) ?>">
                  <?= htmlspecialchars(substr($evento['descripcion'], 0, 50)) ?>...
                </td>
                <td><?= htmlspecialchars($evento['usuario']) ?></td>
                <td>
                  <span class="status-indicator <?= $evento['estado'] === 'completado' || $evento['estado'] === 'vigente' || $evento['estado'] === 'activo' ? 'success' : ($evento['estado'] === 'pendiente' ? 'warning' : 'danger') ?>"></span>
                  <?= ucfirst($evento['estado']) ?>
                </td>
                <td>
                  <div class="timeline-actions">
                    <a href="#" onclick="verDetalles(<?= $evento['id'] ?>)" class="timeline-action view">
                      <i class="bi bi-eye"></i>
                    </a>
                    <?php if ($evento['tipo'] === 'documento' || $evento['tipo'] === 'mantenimiento'): ?>
                    <a href="#" onclick="descargarDocumento(<?= $evento['id'] ?>)" class="timeline-action download">
                      <i class="bi bi-download"></i>
                    </a>
                    <?php endif; ?>
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
        <ul class="pagination" id="pagination"></ul>
      </div>
    </div>

    <!-- Mensaje cuando no hay eventos -->
    <div class="no-history" id="noHistory" style="display: none;">
      <i class="bi bi-clock-history"></i>
      <h3>No se encontraron eventos</h3>
      <p>No hay eventos que coincidan con los filtros seleccionados.</p>
    </div>
  </div>

  <!-- Modal para detalles -->
  <div class="modal fade" id="modalDetalles" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalles del Evento</h5>
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
    let currentView = 'timeline';
    let filtersVisible = false;

    // Toggle filtros
    function toggleFilters() {
      const filtersGrid = document.getElementById('filtersGrid');
      const toggleBtn = document.querySelector('.filters-toggle');
      const toggleText = toggleBtn.querySelector('span');
      const toggleIcon = toggleBtn.querySelector('i');
      
      filtersVisible = !filtersVisible;
      
      if (filtersVisible) {
        filtersGrid.style.display = 'grid';
        toggleText.textContent = 'Ocultar filtros';
        toggleIcon.className = 'bi bi-chevron-up';
      } else {
        filtersGrid.style.display = 'none';
        toggleText.textContent = 'Mostrar filtros';
        toggleIcon.className = 'bi bi-chevron-down';
      }
    }

    // Cambiar vista
    function cambiarVista(vista) {
      const timelineView = document.getElementById('timelineView');
      const tableView = document.getElementById('tableView');
      const timelineBtn = document.getElementById('timelineBtn');
      const tableBtn = document.getElementById('tableBtn');
      
      currentView = vista;
      
      if (vista === 'timeline') {
        timelineView.style.display = 'block';
        tableView.style.display = 'none';
        timelineBtn.classList.add('active');
        tableBtn.classList.remove('active');
      } else {
        timelineView.style.display = 'none';
        tableView.style.display = 'block';
        timelineBtn.classList.remove('active');
        tableBtn.classList.add('active');
        configurarPaginacion();
      }
    }

    // Filtrar por tipo desde las tarjetas de estadísticas
    function filtrarPorTipo(tipo) {
      document.getElementById('filtroTipo').value = tipo;
      if (!filtersVisible) {
        toggleFilters();
      }
      aplicarFiltros();
    }

    // Aplicar filtros
    function aplicarFiltros() {
      const filtroTipo = document.getElementById('filtroTipo').value.toLowerCase();
      const filtroVehiculo = document.getElementById('filtroVehiculo').value.toLowerCase();
      const filtroDesde = document.getElementById('filtroDesde').value;
      const filtroHasta = document.getElementById('filtroHasta').value;
      const filtroUsuario = document.getElementById('filtroUsuario').value.toLowerCase();
      
      const timelineItems = document.querySelectorAll('.timeline-item');
      const tableRows = document.querySelectorAll('#historyTable tbody tr');
      let eventosVisibles = 0;
      
      // Filtrar timeline
      timelineItems.forEach(item => {
        const tipo = item.dataset.tipo || '';
        const vehiculo = item.dataset.vehiculo || '';
        const fecha = item.dataset.fecha || '';
        const usuario = item.dataset.usuario || '';
        
        let mostrar = true;
        
        if (filtroTipo && tipo !== filtroTipo) mostrar = false;
        if (filtroVehiculo && !vehiculo.includes(filtroVehiculo)) mostrar = false;
        if (filtroUsuario && !usuario.includes(filtroUsuario)) mostrar = false;
        if (filtroDesde && fecha < filtroDesde) mostrar = false;
        if (filtroHasta && fecha > filtroHasta) mostrar = false;
        
        item.style.display = mostrar ? 'block' : 'none';
        if (mostrar) eventosVisibles++;
      });
      
      // Filtrar tabla
      tableRows.forEach(row => {
        const tipo = row.dataset.tipo || '';
        const vehiculo = row.dataset.vehiculo || '';
        const fecha = row.dataset.fecha || '';
        const usuario = row.dataset.usuario || '';
        
        let mostrar = true;
        
        if (filtroTipo && tipo !== filtroTipo) mostrar = false;
        if (filtroVehiculo && !vehiculo.includes(filtroVehiculo)) mostrar = false;
        if (filtroUsuario && !usuario.includes(filtroUsuario)) mostrar = false;
        if (filtroDesde && fecha < filtroDesde) mostrar = false;
        if (filtroHasta && fecha > filtroHasta) mostrar = false;
        
        row.style.display = mostrar ? '' : 'none';
      });
      
      // Actualizar contador
      document.getElementById('historyCount').textContent = `${eventosVisibles} eventos`;
      
      // Mostrar mensaje si no hay eventos
      const noHistory = document.getElementById('noHistory');
      const historyView = document.querySelector('.history-view');
      
      if (eventosVisibles === 0) {
        noHistory.style.display = 'block';
        historyView.style.display = 'none';
      } else {
        noHistory.style.display = 'none';
        historyView.style.display = 'block';
      }
      
      if (currentView === 'table') {
        configurarPaginacion();
      }
    }

    // Limpiar filtros
    function limpiarFiltros() {
      document.getElementById('filtroTipo').value = '';
      document.getElementById('filtroVehiculo').value = '';
      document.getElementById('filtroDesde').value = '';
      document.getElementById('filtroHasta').value = '';
      document.getElementById('filtroUsuario').value = '';
      aplicarFiltros();
    }

    // Paginación para vista de tabla
    const filasPorPagina = 5;
    function configurarPaginacion() {
      const filas = Array.from(document.querySelectorAll('#historyTable tbody tr'))
                         .filter(row => row.style.display !== 'none');
      const totalPaginas = Math.ceil(filas.length / filasPorPagina);
      const paginacion = document.getElementById('pagination');

      function mostrarPagina(pagina) {
        document.querySelectorAll('#historyTable tbody tr').forEach(row => {
          row.style.display = 'none';
        });
        
        const inicio = (pagina - 1) * filasPorPagina;
        const fin = inicio + filasPorPagina;
        filas.slice(inicio, fin).forEach(row => {
          row.style.display = '';
        });
        
        document.querySelectorAll('#pagination .page-item').forEach(btn => {
          btn.classList.remove('active');
        });
        document.querySelector(`#pagination .page-item:nth-child(${pagina})`)?.classList.add('active');
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

    // Ver detalles de un evento
    function verDetalles(id) {
      const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
      
      // Simulación de carga de datos
      const detallesContenido = document.getElementById('detallesContenido');
      detallesContenido.innerHTML = `
<div class="p-3">
          <div class="mb-4">
            <h4 class="text-primary">Evento #${id}</h4>
            <p class="text-muted">Información detallada del evento</p>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <p><strong>Tipo:</strong> Mantenimiento</p>
              <p><strong>Vehículo:</strong> ABC123</p>
              <p><strong>Fecha:</strong> 20/05/2025 14:30</p>
              <p><strong>Usuario:</strong> Juan Pérez</p>
            </div>
            <div class="col-md-6">
              <p><strong>Estado:</strong> <span class="badge bg-success">Completado</span></p>
              <p><strong>Costo:</strong> $120.000</p>
              <p><strong>Taller:</strong> Taller Saldaña</p>
              <p><strong>Kilometraje:</strong> 55.000 km</p>
            </div>
          </div>
          
          <div class="mb-3">
            <h5>Descripción</h5>
            <p>Cambio de aceite y filtro completado</p>
          </div>
          
          <div class="mb-3">
            <h5>Detalles completos</h5>
            <p>Se realizó cambio de aceite 20W50 sintético y filtro de aceite. También se revisaron niveles de líquidos y presión de neumáticos. El vehículo se encuentra en óptimas condiciones.</p>
          </div>
          
          <div class="mb-3">
            <h5>Archivos adjuntos</h5>
            <div class="d-flex gap-2">
              <button class="btn btn-outline-primary btn-sm">
                <i class="bi bi-file-pdf"></i> Factura.pdf
              </button>
              <button class="btn btn-outline-primary btn-sm">
                <i class="bi bi-image"></i> Foto_antes.jpg
              </button>
              <button class="btn btn-outline-primary btn-sm">
                <i class="bi bi-image"></i> Foto_despues.jpg
              </button>
            </div>
          </div>
        </div>
      `;
      
      modal.show();
    }

    // Descargar documento
    function descargarDocumento(id) {
      // Implementar descarga de documento
      console.log('Descargar documento del evento:', id);
      // window.open(`descargar_documento.php?evento_id=${id}`, '_blank');
    }

    // Exportar historial
    function exportarHistorial() {
      if (confirm('¿Desea exportar el historial completo a Excel?')) {
        // Implementar exportación
        console.log('Exportar historial completo');
        // window.open('exportar_historial.php', '_blank');
      }
    }

    // Inicializar cuando el DOM esté listo
    window.addEventListener('DOMContentLoaded', () => {
      // Configurar fechas por defecto (último mes)
      const hoy = new Date();
      const hace30dias = new Date();
      hace30dias.setDate(hoy.getDate() - 30);
      
      document.getElementById('filtroDesde').value = hace30dias.toISOString().split('T')[0];
      document.getElementById('filtroHasta').value = hoy.toISOString().split('T')[0];
      
      // Aplicar filtros iniciales
      aplicarFiltros();
      
      // Agregar animación a los elementos del timeline
      const timelineItems = document.querySelectorAll('.timeline-item');
      timelineItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
      });
      
      // Agregar animación a las filas de la tabla
      const tableRows = document.querySelectorAll('#historyTable tbody tr');
      tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.1}s`;
      });
    });

    // Función para actualizar estadísticas en tiempo real
    function actualizarEstadisticas() {
      // Aquí implementarías la lógica para actualizar las estadísticas
      // via AJAX si es necesario
      console.log('Actualizando estadísticas...');
    }

    // Auto-refresh cada 5 minutos (opcional)
    // setInterval(actualizarEstadisticas, 300000);
  </script>
</body>
</html>
