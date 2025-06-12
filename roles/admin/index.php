<?php
session_start();
require_once('../../conecct/conex.php');
include '../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();

// Consulta para contar el total de vehículos registrados
$stmt = $con->prepare("SELECT COUNT(*) AS total FROM vehiculos");
$stmt->execute();
$total_vehiculos = $stmt->fetchColumn();

$stmt1= $con->prepare("SELECT COUNT(*) AS total FROM usuarios ");
$stmt1->execute();
$total_usuarios = $stmt1->fetchColumn();

$stmt2 = $con->prepare("SELECT COUNT(*) AS total FROM vehiculos WHERE id_estado = 10 ");
$stmt2->execute();
$veh_dia = $stmt2->fetchColumn();

// Fecha actual para mostrar en el dashboard
$fecha_actual = date("d M Y");
$dia_semana = date("l");
$dias_es = [
    'Monday' => 'Lunes',
    'Tuesday' => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday' => 'Jueves',
    'Friday' => 'Viernes',
    'Saturday' => 'Sábado',
    'Sunday' => 'Domingo'
];
$meses_es = [
    'Jan' => 'Ene',
    'Feb' => 'Feb',
    'Mar' => 'Mar',
    'Apr' => 'Abr',
    'May' => 'May',
    'Jun' => 'Jun',
    'Jul' => 'Jul',
    'Aug' => 'Ago',
    'Sep' => 'Sep',
    'Oct' => 'Oct',
    'Nov' => 'Nov',
    'Dec' => 'Dic'
];
$dia_semana_es = $dias_es[$dia_semana];
$fecha_es = date("d") . " " . $meses_es[date("M")] . " " . date("Y");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel de Administrador - Flotax AGC</title>
  <link rel="shortcut icon" href="../../css/img/logo_sinfondo.png">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/dashboard.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
  
  <?php include 'menu.html'; ?> 

  <div class="content">
    <!-- Header del Dashboard -->
    <div class="dashboard-header">
      <div>
        <h1 class="dashboard-title">Panel de Control</h1>
        <p class="dashboard-subtitle"><?php echo $dia_semana_es . ', ' . $fecha_es; ?></p>
      </div>
      <div class="dashboard-actions">
        <button class="dashboard-btn"><a href="generar_reporte.php">Exportar Reporte
</a>
          <i class="bi bi-file-earmark-pdf"></i>
        
        </button>

        
        <button class="dashboard-btn">
          <i class="bi bi-plus-circle"></i>
          Nuevo Vehículo
        </button>
      </div>
    </div>
  
    <!-- Tarjetas de estadísticas -->
    <div class="cards">
      <div class="card">
        <i class="bi bi-truck card-icon"></i>
        <h3>Vehículos Registrados</h3>
        <p><?php echo $total_vehiculos; ?></p>
        <div class="trend up">
          <i class="bi bi-arrow-up-right"></i>
          <span>5% vs mes anterior</span>
        </div>
      </div>
      
      <div class="card">
        <i class="bi bi-people card-icon"></i>
        <h3>Usuarios</h3>
        <p><?php echo $total_usuarios; ?></p>
        <div class="trend up">
          <i class="bi bi-arrow-up-right"></i>
          <span>2% vs mes anterior</span>
        </div>
      </div>
      
      <div class="card">
        <i class="bi bi-check-circle card-icon"></i>
        <h3>Vehículos al Día</h3>
        <p><?php echo $veh_dia; ?></p>
        <div class="trend up">
          <i class="bi bi-arrow-up-right"></i>
          <span>8% vs mes anterior</span>
        </div>
      </div>
      
      <div class="card">
        <i class="bi bi-exclamation-triangle card-icon"></i>
        <h3>SOAT Vencido o por Vencer</h3>
        <p>7</p>
        <div class="trend down">
          <i class="bi bi-arrow-down-right"></i>
          <span>3% vs mes anterior</span>
        </div>
      </div>
      
      <div class="card">
        <i class="bi bi-file-earmark-text card-icon"></i>
        <h3>Multas Activas</h3>
        <p>12</p>
        <div class="trend down">
          <i class="bi bi-arrow-down-right"></i>
          <span>2% vs mes anterior</span>
        </div>
      </div>
      
      <div class="card">
        <i class="bi bi-tools card-icon"></i>
        <h3>Próximos Mantenimientos</h3>
        <p>4</p>
        <div class="trend up">
          <i class="bi bi-arrow-up-right"></i>
          <span>1% vs mes anterior</span>
        </div>
      </div>
    </div>

    <!-- Gráficos -->
    <div class="charts-container">
      <div class="chart">
        <h3><i class="bi bi-pie-chart"></i> Distribución por Estado</h3>
        <canvas id="estadoChart"></canvas>
      </div>
      <div class="chart">
        <h3><i class="bi bi-bar-chart"></i> Historial de Gastos por Mes</h3>
        <canvas id="gastosChart"></canvas>
      </div>
    </div>

    <!-- Calendario de Vencimientos -->
    <div class="calendar">
      <h3><i class="bi bi-calendar-event"></i> Próximos Vencimientos</h3>
      <div class="calendar-events">
        <div class="calendar-event">
          <div class="event-date">
            <span class="event-day">10</span>
            <span class="event-month">May</span>
          </div>
          <div class="event-content">
            <div class="event-title">Vencimiento SOAT</div>
            <div class="event-vehicle"><i class="bi bi-car-front"></i> Placa: JSK13</div>
          </div>
        </div>
        
        <div class="calendar-event">
          <div class="event-date">
            <span class="event-day">12</span>
            <span class="event-month">May</span>
          </div>
          <div class="event-content">
            <div class="event-title">Revisión Técnica</div>
            <div class="event-vehicle"><i class="bi bi-car-front"></i> Placa: ABC123</div>
          </div>
        </div>
        
        <div class="calendar-event">
          <div class="event-date">
            <span class="event-day">20</span>
            <span class="event-month">May</span>
          </div>
          <div class="event-content">
            <div class="event-title">Cambio de aceite</div>
            <div class="event-vehicle"><i class="bi bi-car-front"></i> Placa: DEF456</div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Actividad Reciente -->
    <div class="recent-activity">
      <h3><i class="bi bi-activity"></i> Actividad Reciente</h3>
      <div class="activity-list">
        <div class="activity-item">
          <div class="activity-icon">
            <i class="bi bi-truck"></i>
          </div>
          <div class="activity-content">
            <div class="activity-title">Nuevo vehículo registrado</div>
            <div class="activity-subtitle">Camioneta Toyota Hilux - Placa ABC123</div>
          </div>
          <div class="activity-time">Hace 2 horas</div>
        </div>
        
        <div class="activity-item">
          <div class="activity-icon">
            <i class="bi bi-tools"></i>
          </div>
          <div class="activity-content">
            <div class="activity-title">Mantenimiento completado</div>
            <div class="activity-subtitle">Cambio de aceite y filtros - Placa XYZ789</div>
          </div>
          <div class="activity-time">Hace 5 horas</div>
        </div>
        
        <div class="activity-item">
          <div class="activity-icon">
            <i class="bi bi-exclamation-triangle"></i>
          </div>
          <div class="activity-content">
            <div class="activity-title">Alerta de vencimiento</div>
            <div class="activity-subtitle">SOAT próximo a vencer - Placa JSK13</div>
          </div>
          <div class="activity-time">Hace 1 día</div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Configuración de colores para gráficos
    const chartColors = {
      primary: '#667eea',
      secondary: '#764ba2',
      success: '#2ecc71',
      warning: '#f39c12',
      danger: '#e74c3c',
      info: '#3498db',
      purple: '#9b59b6',
      teal: '#1abc9c',
      orange: '#e67e22'
    };
    
    // Gráfico de estado de vehículos
    new Chart(document.getElementById('estadoChart'), {
      type: 'doughnut',
      data: {
        labels: ['Activo', 'En mantenimiento', 'Fuera de servicio'],
        datasets: [{
          label: 'Vehículos',
          data: [20, 10, 5],
          backgroundColor: [chartColors.success, chartColors.warning, chartColors.danger],
          borderColor: 'white',
          borderWidth: 2,
          hoverOffset: 10
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 20,
              font: {
                family: 'Poppins',
                size: 12
              }
            }
          }
        }
      }
    });

    // Gráfico de gastos por mes
    new Chart(document.getElementById('gastosChart'), {
      type: 'bar',
      data: {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril'],
        datasets: [
          {
            label: 'Combustibles',
            data: [3000000, 3200000, 2800000, 3100000],
            backgroundColor: chartColors.info,
            borderRadius: 4
          },
          {
            label: 'Multas',
            data: [500000, 300000, 1000000, 700000],
            backgroundColor: chartColors.orange,
            borderRadius: 4
          },
          {
            label: 'Mantenimiento',
            data: [1500000, 1800000, 1300000, 2000000],
            backgroundColor: chartColors.purple,
            borderRadius: 4
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: { 
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        },
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 20,
              font: {
                family: 'Poppins',
                size: 12
              }
            }
          }
        }
      }
    });
  </script>
</body>
</html>
