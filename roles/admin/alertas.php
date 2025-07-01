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
    $foto_perfil = $user['foto_perfil'] ?: 'roles/user/css/img/perfil.jpg';
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['foto_perfil'] = $foto_perfil;
}

// Función para determinar el estado de una alerta
function getAlertStatus($tipo, $fecha_vencimiento, $dias_aviso = 30) {
    if (!$fecha_vencimiento) return ['estado' => 'informativa', 'dias' => 0];
    
    $fecha_actual = new DateTime();
    $fecha_venc = new DateTime($fecha_vencimiento);
    $diferencia = $fecha_actual->diff($fecha_venc);
    $dias_restantes = $diferencia->invert ? -$diferencia->days : $diferencia->days;
    
    if ($dias_restantes < 0) {
        return ['estado' => 'critica', 'dias' => $dias_restantes];
    } elseif ($dias_restantes <= $dias_aviso) {
        return ['estado' => 'pendiente', 'dias' => $dias_restantes];
    } else {
        return ['estado' => 'informativa', 'dias' => $dias_restantes];
    }
}

// Función para obtener el icono según el tipo de alerta
function getAlertIcon($tipo) {
    switch (strtolower($tipo)) {
        case 'soat':
            return 'bi-shield-check';
        case 'tecnico-mecanica':
        case 'revision':
            return 'bi-gear';
        case 'mantenimiento':
            return 'bi-tools';
        case 'licencia':
            return 'bi-person-badge';
        case 'multa':
            return 'bi-exclamation-triangle';
        default:
            return 'bi-bell';
    }
}

// Datos de ejemplo para alertas (reemplazar con consultas reales)
$alertas = [
    [
        'id' => 1,
        'tipo' => 'SOAT',
        'vehiculo' => 'ABC123',
        'descripcion' => 'El SOAT vence en 10 días',
        'fecha_alerta' => '2025-06-01',
        'fecha_vencimiento' => '2025-06-11',
        'prioridad' => 'alta',
        'estado' => 'pendiente',
        'detalles' => 'Renovar SOAT antes del vencimiento para evitar multas y poder circular legalmente.'
    ],
    [
        'id' => 2,
        'tipo' => 'Tecnico-Mecanica',
        'vehiculo' => 'RST789',
        'descripcion' => 'Revisión técnico-mecánica vencida',
        'fecha_alerta' => '2025-05-10',
        'fecha_vencimiento' => '2025-04-10',
        'prioridad' => 'alta',
        'estado' => 'critica',
        'detalles' => 'La revisión técnico-mecánica está vencida desde hace 30 días. Es urgente renovarla.'
    ],
    [
        'id' => 3,
        'tipo' => 'Mantenimiento',
        'vehiculo' => 'XYZ456',
        'descripcion' => 'Cambio de aceite programado',
        'fecha_alerta' => '2025-05-25',
        'fecha_vencimiento' => '2025-06-15',
        'prioridad' => 'media',
        'estado' => 'pendiente',
        'detalles' => 'El vehículo necesita cambio de aceite en los próximos 300 km o antes del 15 de junio.'
    ],
    [
        'id' => 4,
        'tipo' => 'Licencia',
        'vehiculo' => 'DEF456',
        'descripcion' => 'Licencia de conducción próxima a vencer',
        'fecha_alerta' => '2025-05-20',
        'fecha_vencimiento' => '2025-07-20',
        'prioridad' => 'baja',
        'estado' => 'informativa',
        'detalles' => 'La licencia de conducción del conductor principal vence en 2 meses.'
    ],
    [
        'id' => 5,
        'tipo' => 'Multa',
        'vehiculo' => 'GHI789',
        'descripcion' => 'Multa pendiente de pago',
        'fecha_alerta' => '2025-05-15',
        'fecha_vencimiento' => '2025-06-15',
        'prioridad' => 'alta',
        'estado' => 'pendiente',
        'detalles' => 'Multa por exceso de velocidad pendiente de pago. Valor: $180.000'
    ]
];

// Calcular estadísticas
$total_alertas = count($alertas);
$alertas_criticas = count(array_filter($alertas, fn($a) => $a['estado'] === 'critica'));
$alertas_pendientes = count(array_filter($alertas, fn($a) => $a['estado'] === 'pendiente'));
$alertas_al_dia = count(array_filter($alertas, fn($a) => $a['estado'] === 'informativa'));

// Alertas resueltas (ejemplo)
$alertas_resueltas_mes = 12;
$alertas_resueltas_total = 45;
$tiempo_promedio_resolucion = 3; // días
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Módulo de Alertas - Flotax AGC</title>
  <link rel="shortcut icon" href="../../css/img/logo_sinfondo.png">
  <link rel="stylesheet" href="css/alertas.css" />
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <?php include 'menu.php'; ?>

  <div class="content">
    <!-- Header de la página -->
    <div class="page-header">
      <div>
        <h1 class="page-title">
          <i class="bi bi-bell"></i>
          Módulo de Alertas
        </h1>
        <p class="page-subtitle">Sistema de notificaciones y alertas del sistema</p>
      </div>
    </div>

    <!-- Resumen de alertas -->
    <div class="alerts-summary">
      <div class="summary-card criticas" onclick="filtrarPorEstado('critica')">
        <div class="summary-number criticas">
          <span><?= $alertas_criticas ?></span>
          <i class="bi bi-exclamation-triangle summary-icon"></i>
        </div>
        <div class="summary-label">Alertas Críticas</div>
      </div>
      <div class="summary-card pendientes" onclick="filtrarPorEstado('pendiente')">
        <div class="summary-number pendientes">
          <span><?= $alertas_pendientes ?></span>
          <i class="bi bi-clock summary-icon"></i>
        </div>
        <div class="summary-label">Alertas Pendientes</div>
      </div>
      <div class="summary-card al-dia" onclick="filtrarPorEstado('informativa')">
        <div class="summary-number al-dia">
          <span><?= $alertas_al_dia ?></span>
          <i class="bi bi-check-circle summary-icon"></i>
        </div>
        <div class="summary-label">Al Día</div>
      </div>
      <div class="summary-card total" onclick="mostrarTodas()">
        <div class="summary-number total">
          <span><?= $total_alertas ?></span>
          <i class="bi bi-list summary-icon"></i>
        </div>
        <div class="summary-label">Total Alertas</div>
      </div>
    </div>

    <!-- Filtros -->
    <div class="filters-section">
      <h3 class="filters-title">
        <i class="bi bi-funnel"></i>
        Filtros de Búsqueda
      </h3>
      <form class="filters-grid" id="filtrosForm">
        <div class="filter-group">
          <label class="filter-label">Tipo de Alerta</label>
          <select class="filter-control" id="filtroTipo" onchange="aplicarFiltros()">
            <option value="">Todas las alertas</option>
            <option value="soat">SOAT</option>
            <option value="tecnico-mecanica">Revisión Técnico-Mecánica</option>
            <option value="mantenimiento">Mantenimiento</option>
            <option value="licencia">Licencia</option>
            <option value="multa">Multas</option>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label">Estado</label>
          <select class="filter-control" id="filtroEstado" onchange="aplicarFiltros()">
            <option value="">Todos los estados</option>
            <option value="critica">🔴 Crítica</option>
            <option value="pendiente">🟡 Pendiente</option>
            <option value="informativa">🔵 Informativa</option>
          </select>
        </div>
        <div class="filter-group">
          <label class="filter-label">Vehículo</label>
          <input type="text" class="filter-control" id="filtroVehiculo" placeholder="Placa (ej: ABC123)" onkeyup="aplicarFiltros()">
        </div>
        <div class="filter-group">
          <label class="filter-label">Prioridad</label>
          <select class="filter-control" id="filtroPrioridad" onchange="aplicarFiltros()">
            <option value="">Todas las prioridades</option>
            <option value="alta">Alta</option>
            <option value="media">Media</option>
            <option value="baja">Baja</option>
          </select>
        </div>
        <div class="filter-group">
          <button type="button" class="filter-btn" onclick="limpiarFiltros()">
            <i class="bi bi-arrow-clockwise"></i>
            Limpiar Filtros
          </button>
        </div>
      </form>
    </div>

    <!-- Contenedor de alertas activas -->
    <div class="alerts-container">
      <div class="alerts-header">
        <h3 class="alerts-title">
          <i class="bi bi-bell"></i>
          Alertas Activas
        </h3>
        <span class="alerts-count" id="alertasCount"><?= $total_alertas ?> alertas</span>
      </div>
      
      <ul class="alerts-list" id="alertasList">
        <?php foreach ($alertas as $alerta): ?>
        <li class="alert-item <?= $alerta['estado'] ?>" 
            data-tipo="<?= strtolower($alerta['tipo']) ?>" 
            data-estado="<?= $alerta['estado'] ?>" 
            data-vehiculo="<?= strtolower($alerta['vehiculo']) ?>"
            data-prioridad="<?= $alerta['prioridad'] ?>">
          
          <div class="alert-priority <?= $alerta['prioridad'] ?>"></div>
          
          <div class="alert-icon <?= $alerta['estado'] ?>">
            <i class="<?= getAlertIcon($alerta['tipo']) ?>"></i>
          </div>
          
          <div class="alert-content">
            <div class="alert-type">
              <i class="<?= getAlertIcon($alerta['tipo']) ?>"></i>
              <?= htmlspecialchars($alerta['tipo']) ?>
              <span class="alert-vehicle"><?= htmlspecialchars($alerta['vehiculo']) ?></span>
            </div>
            <div class="alert-description"><?= htmlspecialchars($alerta['descripcion']) ?></div>
            <div class="alert-date">
              <i class="bi bi-calendar"></i>
              <?= date('d/m/Y', strtotime($alerta['fecha_alerta'])) ?>
            </div>
          </div>
          
          <div class="alert-status">
            <span class="status-badge <?= $alerta['estado'] ?>">
              <?php if ($alerta['estado'] === 'critica'): ?>
                <i class="bi bi-exclamation-triangle-fill"></i> Crítica
              <?php elseif ($alerta['estado'] === 'pendiente'): ?>
                <i class="bi bi-clock-fill"></i> Pendiente
              <?php else: ?>
                <i class="bi bi-info-circle-fill"></i> Informativa
              <?php endif; ?>
            </span>
          </div>
          
          <div class="alert-actions">
            <a href="#" onclick="verDetalles(<?= $alerta['id'] ?>)" class="action-btn primary">
              <i class="bi bi-eye"></i> Ver
            </a>
            <?php if ($alerta['estado'] !== 'critica'): ?>
            <a href="#" onclick="resolverAlerta(<?= $alerta['id'] ?>)" class="action-btn success">
              <i class="bi bi-check"></i> Resolver
            </a>
            <?php else: ?>
            <a href="#" onclick="accionUrgente(<?= $alerta['id'] ?>)" class="action-btn danger">
              <i class="bi bi-lightning"></i> Urgente
            </a>
            <?php endif; ?>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Mensaje cuando no hay alertas -->
    <div class="no-alerts" id="noAlertas" style="display: none;">
      <i class="bi bi-check-circle"></i>
      <h3>¡Excelente!</h3>
      <p>No hay alertas que requieran tu atención en este momento.</p>
    </div>

    <!-- Sección de alertas resueltas -->
    <div class="resolved-alerts">
      <h3 class="resolved-title">
        <i class="bi bi-check-circle-fill"></i>
        Alertas Resueltas
      </h3>
      <p class="resolved-description">
        Estadísticas de alertas que han sido resueltas exitosamente en el sistema.
      </p>
      
      <div class="resolved-stats">
        <div class="resolved-stat">
          <div class="resolved-stat-number"><?= $alertas_resueltas_mes ?></div>
          <div class="resolved-stat-label">Este mes</div>
        </div>
        <div class="resolved-stat">
          <div class="resolved-stat-number"><?= $alertas_resueltas_total ?></div>
          <div class="resolved-stat-label">Total resueltas</div>
        </div>
        <div class="resolved-stat">
          <div class="resolved-stat-number"><?= $tiempo_promedio_resolucion ?></div>
          <div class="resolved-stat-label">Días promedio</div>
        </div>
        <div class="resolved-stat">
          <div class="resolved-stat-number">95%</div>
          <div class="resolved-stat-label">Tasa de éxito</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para detalles -->
  <div class="modal fade" id="modalDetalles" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalles de la Alerta</h5>
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
    // Aplicar filtros combinados
    function aplicarFiltros() {
      const filtroTipo = document.getElementById('filtroTipo').value.toLowerCase();
      const filtroEstado = document.getElementById('filtroEstado').value.toLowerCase();
      const filtroVehiculo = document.getElementById('filtroVehiculo').value.toLowerCase();
      const filtroPrioridad = document.getElementById('filtroPrioridad').value.toLowerCase();
      
      const alertas = document.querySelectorAll('.alert-item');
      let alertasVisibles = 0;
      
      alertas.forEach(alerta => {
        const tipo = alerta.dataset.tipo || '';
        const estado = alerta.dataset.estado || '';
        const vehiculo = alerta.dataset.vehiculo || '';
        const prioridad = alerta.dataset.prioridad || '';
        
        let mostrar = true;
        
        if (filtroTipo && !tipo.includes(filtroTipo)) mostrar = false;
        if (filtroEstado && estado !== filtroEstado) mostrar = false;
        if (filtroVehiculo && !vehiculo.includes(filtroVehiculo)) mostrar = false;
        if (filtroPrioridad && prioridad !== filtroPrioridad) mostrar = false;
        
        alerta.style.display = mostrar ? 'flex' : 'none';
        if (mostrar) alertasVisibles++;
      });
      
      // Actualizar contador
      document.getElementById('alertasCount').textContent = `${alertasVisibles} alertas`;
      
      // Mostrar mensaje si no hay alertas
      const noAlertas = document.getElementById('noAlertas');
      const alertasList = document.getElementById('alertasList');
      
      if (alertasVisibles === 0) {
        noAlertas.style.display = 'block';
        alertasList.style.display = 'none';
      } else {
        noAlertas.style.display = 'none';
        alertasList.style.display = 'block';
      }
    }

    // Filtrar por estado desde las tarjetas de resumen
    function filtrarPorEstado(estado) {
      document.getElementById('filtroEstado').value = estado;
      aplicarFiltros();
    }

    // Mostrar todas las alertas
    function mostrarTodas() {
      limpiarFiltros();
    }

    // Limpiar todos los filtros
    function limpiarFiltros() {
      document.getElementById('filtroTipo').value = '';
      document.getElementById('filtroEstado').value = '';
      document.getElementById('filtroVehiculo').value = '';
      document.getElementById('filtroPrioridad').value = '';
      aplicarFiltros();
    }

    // Ver detalles de una alerta
    function verDetalles(id) {
      const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
      
      // Simulación de carga de datos
      const detallesContenido = document.getElementById('detallesContenido');
      detallesContenido.innerHTML = `<div class="p-3">
          <div class="mb-4">
            <h4 class="text-primary">Alerta #${id}</h4>
            <p class="text-muted">Información detallada de la alerta</p>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <p><strong>Tipo:</strong> SOAT</p>
              <p><strong>Vehículo:</strong> ABC123</p>
              <p><strong>Estado:</strong> <span class="badge bg-warning">Pendiente</span></p>
              <p><strong>Prioridad:</strong> <span class="badge bg-danger">Alta</span></p>
            </div>
            <div class="col-md-6">
              <p><strong>Fecha de alerta:</strong> 01/06/2025</p>
              <p><strong>Fecha de vencimiento:</strong> 11/06/2025</p>
              <p><strong>Días restantes:</strong> 10 días</p>
              <p><strong>Responsable:</strong> Administrador</p>
            </div>
          </div>
          
          <div class="mb-3">
            <h5>Descripción</h5>
            <p>El SOAT del vehículo ABC123 vence en 10 días. Es necesario renovarlo antes del vencimiento.</p>
          </div>
          
          <div class="mb-3">
            <h5>Acciones recomendadas</h5>
            <ul>
              <li>Contactar con la aseguradora para renovar el SOAT</li>
              <li>Verificar que todos los documentos estén al día</li>
              <li>Programar la renovación con anticipación</li>
            </ul>
          </div>
          
          <div class="mb-3">
            <h5>Historial</h5>
            <p><small class="text-muted">01/06/2025 - Alerta generada automáticamente</small></p>
          </div>
        </div>
      `;
      
      modal.show();
    }

    // Resolver una alerta
    function resolverAlerta(id) {
      if (confirm('¿Está seguro de marcar esta alerta como resuelta?')) {
        // Aquí implementarías la lógica para resolver la alerta
        console.log('Resolver alerta:', id);
        
        // Simular resolución
        const alertaElement = document.querySelector(`[onclick="resolverAlerta(${id})"]`).closest('.alert-item');
        alertaElement.style.opacity = '0.5';
        alertaElement.style.transform = 'translateX(100%)';
        
        setTimeout(() => {
          alertaElement.remove();
          aplicarFiltros(); // Actualizar contador
        }, 300);
      }
    }

    // Acción urgente para alertas críticas
    function accionUrgente(id) {
      if (confirm('¿Desea tomar acción inmediata sobre esta alerta crítica?')) {
        // Implementar acción urgente
        console.log('Acción urgente para alerta:', id);
        window.open(`accion_urgente.php?id=${id}`, '_blank', 'width=800,height=600');
      }
    }

    // Inicializar cuando el DOM esté listo
    window.addEventListener('DOMContentLoaded', () => {
      // Agregar animación a las alertas
      const alertas = document.querySelectorAll('.alert-item');
      alertas.forEach((alerta, index) => {
        alerta.style.animationDelay = `${index * 0.1}s`;
      });

      // Simular notificaciones en tiempo real (opcional)
      // setInterval(verificarNuevasAlertas, 30000); // Cada 30 segundos
    });

    // Función para verificar nuevas alertas (simulación)
    function verificarNuevasAlertas() {
      // Aquí implementarías la lógica para verificar nuevas alertas via AJAX
      console.log('Verificando nuevas alertas...');
    }
  </script>
</body>
</html>
