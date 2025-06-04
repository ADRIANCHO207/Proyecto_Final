<?php
session_start();
require_once('../../conecct/conex.php');
include '../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();



?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel de Administrador</title>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  
    <?php include 'menu.html'; ?> 

  <div class="content">
  
    <div class="cards">
      <div class="card"><h3>Total Vehículos Registrados</h3><p>35</p></div>
      <div class="card"><h3>Vehículos al Día</h3><p>28</p></div>
      <div class="card"><h3>SOAT Vencido o por Vencer</h3><p>7</p></div>
      <div class="card"><h3>Multas Activas</h3><p>12</p></div>
      <div class="card"><h3>Próximos Mantenimientos</h3><p>4</p></div>
    </div>

    <div class="charts-container" style="display:flex; gap:20px; flex-wrap:wrap;">
      <div class="chart">
        <h3>Distribución por Estado</h3>
        <canvas id="estadoChart"></canvas>
      </div>
      <div class="chart">
        <h3>Historial de Gastos por Mes</h3>
        <canvas id="gastosChart"></canvas>
      </div>
    </div>

    <!-- Calendario Pequeño -->
    <div class="calendar" style="margin-top:30px;">
      <h3>Próximos Vencimientos</h3>
      <ul>
        <li>10 mayo - SOAT JSK13</li>
        <li>12 mayo - Revisión Técnica ABC123</li>
        <li>20 mayo - Cambio de aceite DEF456</li>
      </ul>
    </div>
  </div>

  <script>
    // Gráfico de estado de vehículos
    new Chart(document.getElementById('estadoChart'), {
      type: 'doughnut',
      data: {
        labels: ['Activo', 'En mantenimiento', 'Fuera de servicio'],
        datasets: [{
          label: 'Vehículos',
          data: [20, 10, 5],
          backgroundColor: ['#2ecc71', '#f1c40f', '#e74c3c']
        }]
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
            backgroundColor: '#3498db'
          },
          {
            label: 'Multas',
            data: [500000, 300000, 1000000, 700000],
            backgroundColor: '#e67e22'
          },
          {
            label: 'Mantenimiento',
            data: [1500000, 1800000, 1300000, 2000000],
            backgroundColor: '#9b59b6'
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>
</body>
</html>
