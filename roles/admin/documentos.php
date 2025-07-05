<?php
session_start();
require_once('../../conecct/conex.php');
include '../../includes/validarsession.php';

$db = new Database();
$con = $db->conectar();
$code = $_SESSION['documento'];

// Consulta mejorada para obtener documentos


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
    $foto_perfil = $user['foto_perfil'] ?: 'roles/user/css/img/perfil.jpg';
    $_SESSION['nombre_completo'] = $nombre_completo;
    $_SESSION['foto_perfil'] = $foto_perfil;
}

// Función para determinar el estado de un documento
function getDocumentStatus($fecha_vencimiento) {
    if (!$fecha_vencimiento) return 'no-disponible';
    
    $fecha_actual = new DateTime();
    $fecha_venc = new DateTime($fecha_vencimiento);
    $diferencia = $fecha_actual->diff($fecha_venc);
    
    if ($fecha_venc < $fecha_actual) {
        return 'vencido';
    } elseif ($diferencia->days <= 30) {
        return 'proximo';
    } else {
        return 'vigente';
    }
}

// Datos de documentos desde la base de datos
$documentos = [];

$query = $con->prepare("
    SELECT 
        v.placa,
        s.fecha_vencimiento AS soat_vence,
        t.fecha_vencimiento AS tecnomecanica_vence,
        l.fecha_vencimiento AS licencia_vence,
        u.nombre_completo AS propietario
    FROM vehiculos v
    LEFT JOIN soat s ON v.placa = s.id_placa
    LEFT JOIN tecnomecanica t ON v.placa = t.id_placa
    LEFT JOIN licencias l ON v.documento = l.id_documento
    LEFT JOIN usuarios u ON v.documento = u.documento
");

$query->execute();
$resultados = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultados as $row) {
    $estado_licencia = getDocumentStatus($row['licencia_vence']);

    $documentos[] = [
        'placa' => $row['placa'],
        'soat_vence' => $row['soat_vence'],
        'tecnomecanica_vence' => $row['tecnomecanica_vence'],
        'licencia_estado' => $estado_licencia,
        'propietario' => $row['propietario'] ?? 'Desconocido'
    ];
}
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visualizacion de Documentos - Flotax AGC</title>
  <link rel="shortcut icon" href="../../css/img/logo_sinfondo.png">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* Estilos para el Control de Documentos */
:root {
  --primary-color: #667eea;
  --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  --secondary-color: #2c3e50;
  --success-color: #27ae60;
  --warning-color: #f39c12;
  --danger-color: #e74c3c;
  --info-color: #3498db;
  --text-color: #2d3748;
  --text-light: #718096;
  --bg-color: #f8fafc;
  --card-bg: #ffffff;
  --border-color: #e2e8f0;
  --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --sidebar-width: 80px;
  --sidebar-expanded-width: 280px;
  --border-radius: 12px;
}

body {
  font-family: "Poppins", sans-serif;
  margin: 0;
  padding: 0;
  background-color: var(--bg-color);
  color: var(--text-color);
  overflow-x: hidden;
}

/* Contenido principal */
.content {
  margin-left: calc(var(--sidebar-width) + 20px);
  padding: 30px;
  transition: var(--transition);
  min-height: 100vh;
}

.sidebar.expanded ~ .content {
  margin-left: calc(var(--sidebar-expanded-width) + 20px);
}

.container-fluid {
  max-width: 1400px;
  margin: 0 auto;
}

/* Header de la página */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding: 20px 0;
  border-bottom: 1px solid var(--border-color);
}

.page-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--secondary-color);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.page-title i {
  color: var(--primary-color);
  font-size: 32px;
}

.page-subtitle {
  color: var(--text-light);
  font-size: 16px;
  margin-top: 5px;
}

/* Controles superiores */
.controls-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  gap: 20px;
  flex-wrap: wrap;
}

/* Buscador mejorado */
.buscador {
  position: relative;
  flex: 1;
  max-width: 400px;
}

.buscador input {
  width: 100%;
  padding: 12px 20px 12px 45px;
  border: 2px solid var(--border-color);
  border-radius: 25px;
  font-size: 14px;
  transition: var(--transition);
  background-color: var(--card-bg);
  box-shadow: var(--shadow);
}

.buscador input:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.buscador::before {
  content: "\F52A";
  font-family: "Bootstrap Icons";
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-light);
  font-size: 16px;
  z-index: 2;
}

/* Botón agregar */
.boton-agregar .btn {
  background: var(--primary-gradient);
  border: none;
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  transition: var(--transition);
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: var(--shadow);
}

.boton-agregar .btn:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg);
}

.boton-agregar .btn i {
  font-size: 16px;
}

/* Contenedor de tabla */
.table-container {
  background: var(--card-bg);
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  overflow: hidden;
  margin-bottom: 25px;
  animation: fadeInUp 0.6s ease-out;
}

.table-responsive {
  border-radius: var(--border-radius);
  overflow: hidden;
}

/* Tabla mejorada */
.table {
  margin: 0;
  border-collapse: separate;
  border-spacing: 0;
}

.table thead {
  background: var(--primary-gradient);
  color: white;
}

.table thead th {
  padding: 18px 15px;
  font-weight: 600;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border: none;
  white-space: nowrap;
  position: relative;
}

.table thead th:first-child {
  border-top-left-radius: var(--border-radius);
}

.table thead th:last-child {
  border-top-right-radius: var(--border-radius);
}

.table tbody tr {
  transition: var(--transition);
  border-bottom: 1px solid var(--border-color);
}

.table tbody tr:hover {
  background-color: rgba(102, 126, 234, 0.05);
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.table tbody tr:last-child {
  border-bottom: none;
}

.table tbody td {
  padding: 16px 15px;
  vertical-align: middle;
  border: none;
  font-size: 14px;
}

/* Placa destacada */
.placa-badge {
  background: linear-gradient(135deg, var(--secondary-color), #34495e);
  color: white;
  padding: 6px 12px;
  border-radius: 6px;
  font-weight: 700;
  letter-spacing: 1px;
  font-size: 13px;
  display: inline-block;
}

/* Estados de documentos */
.status-vigente {
  color: var(--success-color);
  font-weight: 600;
  padding: 6px 12px;
  background-color: rgba(39, 174, 96, 0.1);
  border-radius: 20px;
  font-size: 12px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.status-vigente::before {
  content: "\F26A";
  font-family: "Bootstrap Icons";
  font-size: 14px;
}

.status-vencido {
  color: var(--danger-color);
  font-weight: 600;
  padding: 6px 12px;
  background-color: rgba(231, 76, 60, 0.1);
  border-radius: 20px;
  font-size: 12px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.status-vencido::before {
  content: "\F623";
  font-family: "Bootstrap Icons";
  font-size: 14px;
}

.status-proximo {
  color: var(--warning-color);
  font-weight: 600;
  padding: 6px 12px;
  background-color: rgba(243, 156, 18, 0.1);
  border-radius: 20px;
  font-size: 12px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.status-proximo::before {
  content: "\F4A2";
  font-family: "Bootstrap Icons";
  font-size: 14px;
}

/* Enlaces de documentos */
.documento-link {
  color: var(--info-color);
  text-decoration: none;
  font-weight: 600;
  padding: 6px 12px;
  background-color: rgba(52, 152, 219, 0.1);
  border-radius: 6px;
  transition: var(--transition);
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
}

.documento-link:hover {
  background-color: rgba(52, 152, 219, 0.2);
  transform: translateY(-1px);
}

.documento-link::before {
  content: "\F1C1";
  font-family: "Bootstrap Icons";
  font-size: 14px;
}

/* Acciones */
.action-buttons {
  display: flex;
  gap: 8px;
  justify-content: center;
}

.action-icon {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  transition: var(--transition);
  cursor: pointer;
  border: 1px solid transparent;
}

.action-icon.edit {
  color: var(--info-color);
  background-color: rgba(52, 152, 219, 0.1);
  border-color: rgba(52, 152, 219, 0.2);
}

.action-icon.edit:hover {
  background-color: var(--info-color);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.action-icon.delete {
  color: var(--danger-color);
  background-color: rgba(231, 76, 60, 0.1);
  border-color: rgba(231, 76, 60, 0.2);
}

.action-icon.delete:hover {
  background-color: var(--danger-color);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
}

/* Paginación mejorada */
.pagination-container {
  display: flex;
  justify-content: center;
  margin-top: 25px;
}

.pagination {
  display: flex;
  gap: 5px;
  list-style: none;
  padding: 0;
  margin: 0;
}

.page-item {
  border-radius: 8px;
  overflow: hidden;
}

.page-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background-color: var(--card-bg);
  color: var(--text-color);
  text-decoration: none;
  border: 1px solid var(--border-color);
  font-weight: 600;
  font-size: 14px;
  transition: var(--transition);
}

.page-link:hover {
  background-color: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
  transform: translateY(-1px);
}

.page-item.active .page-link {
  background: var(--primary-gradient);
  color: white;
  border-color: var(--primary-color);
  box-shadow: var(--shadow);
}

/* Estadísticas rápidas */
.stats-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: var(--card-bg);
  border-radius: var(--border-radius);
  padding: 20px;
  box-shadow: var(--shadow);
  transition: var(--transition);
  position: relative;
  overflow: hidden;
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-lg);
}

.stat-card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background: var(--primary-gradient);
}

.stat-card.success::before {
  background: linear-gradient(135deg, var(--success-color), #2ecc71);
}

.stat-card.warning::before {
  background: linear-gradient(135deg, var(--warning-color), #e67e22);
}

.stat-card.danger::before {
  background: linear-gradient(135deg, var(--danger-color), #c0392b);
}

.stat-number {
  font-size: 24px;
  font-weight: 700;
  color: var(--secondary-color);
  margin: 0;
}

.stat-label {
  font-size: 13px;
  color: var(--text-light);
  margin-top: 5px;
  font-weight: 500;
}

.stat-icon {
  position: absolute;
  top: 15px;
  right: 15px;
  font-size: 24px;
  opacity: 0.3;
  color: var(--primary-color);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .content {
    margin-left: 20px;
    padding: 20px;
  }

  .sidebar.expanded ~ .content {
    margin-left: 20px;
  }
}

@media (max-width: 768px) {
  .content {
    margin-left: 0;
    padding: 15px;
  }

  .page-title {
    font-size: 24px;
  }

  .controls-section {
    flex-direction: column;
    align-items: stretch;
  }

  .buscador {
    max-width: none;
  }

  .table-responsive {
    font-size: 12px;
  }

  .table thead th,
  .table tbody td {
    padding: 10px 8px;
  }

  .stats-cards {
    grid-template-columns: 1fr;
  }

  .action-buttons {
    flex-direction: column;
    gap: 5px;
  }

  .action-icon {
    width: 32px;
    height: 32px;
    font-size: 14px;
  }
}

/* Animaciones */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.table tbody tr {
  animation: slideIn 0.3s ease-out;
}

.table tbody tr:nth-child(1) {
  animation-delay: 0.1s;
}
.table tbody tr:nth-child(2) {
  animation-delay: 0.2s;
}
.table tbody tr:nth-child(3) {
  animation-delay: 0.3s;
}
.table tbody tr:nth-child(4) {
  animation-delay: 0.4s;
}
.table tbody tr:nth-child(5) {
  animation-delay: 0.5s;
}

  </style>
</head>
<body>

  <?php include 'menu.php'; ?>

  <div class="content">
    <div class="container-fluid">
      <!-- Header de la página -->
      <div class="page-header">
        <div>
          <h1 class="page-title">
            <i class="bi bi-folder-check"></i>
            Control de Documentos
          </h1>
          <p class="page-subtitle">Gestión y seguimiento de documentos vehiculares</p>
        </div>
      </div>

      <!-- Estadísticas rápidas -->
      <div class="stats-cards">
        <div class="stat-card success">
          <i class="bi bi-check-circle stat-icon"></i>
          <div class="stat-number">15</div>
          <div class="stat-label">Documentos al día</div>
        </div>
        <div class="stat-card warning">
          <i class="bi bi-exclamation-triangle stat-icon"></i>
          <div class="stat-number">3</div>
          <div class="stat-label">Por vencer (30 días)</div>
        </div>
        <div class="stat-card danger">
          <i class="bi bi-x-circle stat-icon"></i>
          <div class="stat-number">2</div>
          <div class="stat-label">Vencidos</div>
        </div>
        <div class="stat-card">
          <i class="bi bi-file-earmark stat-icon"></i>
          <div class="stat-number">20</div>
          <div class="stat-label">Total documentos</div>
        </div>
      </div>

      <!-- Controles superiores -->
      <div class="controls-section">
        <div class="buscador">
          <input type="text" id="buscar" placeholder="Buscar por placa, documento o estado..." onkeyup="filtrarTabla()">
        </div>
      </div>

      <!-- Tabla de documentos -->
      <div class="table-container">
        <div class="table-responsive">
          <table class="table" id="tablaDocumentos">
            <thead>
              <tr>
                <th><i class="bi bi-car-front"></i> Placa</th>
                <th><i class="bi bi-shield-check"></i> SOAT</th>
                <th><i class="bi bi-gear"></i> TecnoMecánica</th>
                <th><i class="bi bi-person-badge"></i> Licencia</th>
                <th><i class="bi bi-file-earmark-text"></i>Propietario</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($documentos as $doc): ?>
                <tr>
                  <td>
                    <span class="placa-badge"><?= htmlspecialchars($doc['placa']) ?></span>
                  </td>
                  <td>
                    <?php 
                    $soat_status = getDocumentStatus($doc['soat_vence']);
                    $soat_fecha = new DateTime($doc['soat_vence']);
                    ?>
                   <center> <span class="status-<?= $soat_status ?> fecha-tooltip" 
                          data-tooltip="Vence: <?= $soat_fecha->format('d/m/Y') ?>">
                      <?php if ($soat_status === 'vigente'): ?>
                        Vigente
                      <?php elseif ($soat_status === 'proximo'): ?>
                        Por vencer
                      <?php else: ?>
                        Vencido
                      <?php endif; ?>
                    </span></center>
                  </td>
                  <td>
                    <?php 
                    $tecno_status = getDocumentStatus($doc['tecnomecanica_vence']);
                    $tecno_fecha = new DateTime($doc['tecnomecanica_vence']);
                    ?>
                    <span class="status-<?= $tecno_status ?> fecha-tooltip" 
                          data-tooltip="Vence: <?= $tecno_fecha->format('d/m/Y') ?>">
                      <?php if ($tecno_status === 'vigente'): ?>
                        Vigente
                      <?php elseif ($tecno_status === 'proximo'): ?>
                        Por vencer
                      <?php else: ?>
                        Vencido
                      <?php endif; ?>
                    </span>
                  </td>
                  <td>
                    <span class="status-<?= $doc['licencia_estado'] ?>">
                      <?= ucfirst($doc['licencia_estado']) ?>
                    </span>
                  </td>
                     <td>
                    <?= htmlspecialchars($doc['propietario']) ?>
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
    </div>
  </div>
<script>
  
    // Función de filtrado mejorada
    function filtrarTabla() {
      const input = document.getElementById('buscar').value.toLowerCase();
      const rows = document.querySelectorAll("#tablaDocumentos tbody tr");
      let visibleRows = 0;
      
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const isVisible = text.includes(input);
        row.style.display = isVisible ? '' : 'none';
        if (isVisible) visibleRows++;
      });
      
      // Reconfigurar paginación después del filtrado
      configurarPaginacion();
    }

    // Paginación mejorada
    const filasPorPagina = 5;
    function configurarPaginacion() {
      const filas = Array.from(document.querySelectorAll('#tablaDocumentos tbody tr'))
                         .filter(row => row.style.display !== 'none');
      const totalPaginas = Math.ceil(filas.length / filasPorPagina);
      const paginacion = document.getElementById('paginacion');

      function mostrarPagina(pagina) {
        // Ocultar todas las filas
        document.querySelectorAll('#tablaDocumentos tbody tr').forEach(row => {
          row.style.display = 'none';
        });
        
        // Mostrar solo las filas de la página actual
        const inicio = (pagina - 1) * filasPorPagina;
        const fin = inicio + filasPorPagina;
        filas.slice(inicio, fin).forEach(row => {
          row.style.display = '';
        });
        
        // Actualizar botones de paginación
        document.querySelectorAll('#paginacion .page-item').forEach(btn => {
          btn.classList.remove('active');
        });
        document.querySelector(`#paginacion .page-item:nth-child(${pagina})`)?.classList.add('active');
      }

      // Crear botones de paginación
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

      if (totalPaginas > 0) {
        mostrarPagina(1);
      }
    }

  

    function editarDocumento(placa) {
      // Implementar edición de documento
      window.open(`editar_documento.php?placa=${placa}`, '', 'width=800, height=600, toolbar=NO');
    }

    function eliminarDocumento(placa) {
      if (confirm(`¿Está seguro de eliminar los documentos del vehículo ${placa}?`)) {
        // Implementar eliminación
        console.log('Eliminar documentos de:', placa);
      }
    }

    function verDocumento(tipo, placa) {
      // Implementar visualización de documento
      window.open(`ver_documento.php?tipo=${tipo}&placa=${placa}`, '_blank');
    }

    // Inicializar cuando el DOM esté listo
    window.addEventListener('DOMContentLoaded', () => {
      configurarPaginacion();
      
      // Agregar animación a las filas
      const rows = document.querySelectorAll('#tablaDocumentos tbody tr');
      rows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.1}s`;
      });
    });
  </script>
</body>
</html>
