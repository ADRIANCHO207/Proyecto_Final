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
<<<<<<< HEAD

<div class="header">
    <div class="logo">

        <img src="../../css/img/logo_sinfondo.png" alt="Loo">
        <span class="empresa">Flotax AGC</span>
=======
  <!-- Navbar con notificaciones -->
  <div class="navbar">
    <h1>Panel de Administrador</h1>
    <div class="notification" onclick="toggleDropdown()"><i class="bi bi-bell"></i> <span class="badge">3</span>
      <div class="dropdown" id="dropdown">
        <p><i class="bi bi-record-fill" style="c:#d32f2f ;"></i> SOAT de vehículo JSK13 vence en 3 días</p>
        <p><i class="bi bi-record-fill" style="color:#d32f2f ;"></i> Multa sin pagar del vehículo ASDJ</p>
        <p><i class="bi bi-record-fill" style="color:#d32f2f ;"></i> Revisión Tecnomecánica vencida</p>
      </div>
>>>>>>> a7018a5 (apis y admin)
    </div>
  </div>

  <div class="sidebar">
    <?php include 'menu.html'; ?> 
  </div>

  <div class="content">
    <!-- Tarjetas resumen -->
    <div class="cards">
      <div class="card"><h3>Total Vehículos Registrados</h3><p>35</p></div>
      <div class="card"><h3>Vehículos al Día</h3><p>28</p></div>
      <div class="card"><h3>SOAT Vencido o por Vencer</h3><p>7</p></div>
      <div class="card"><h3>Multas Activas</h3><p>12</p></div>
      <div class="card"><h3>Próximos Mantenimientos</h3><p>4</p></div>
    </div>
<<<<<<< HEAD
    <div class="perfil">
        <div class="info-usuario">
            <span> <?php echo""?></span>
            <br>
        </div>
=======

    <!-- Gráfico: Distribución de Vehículos -->
    <div class="chart">
      <h3>Distribución por Estado</h3>
      <canvas id="estadoChart"></canvas>
>>>>>>> a7018a5 (apis y admin)
    </div>

    <!-- Gráfico: Historial de Gastos -->
    <div class="chart">
      <h3>Historial de Gastos por Mes</h3>
      <canvas id="gastosChart"></canvas>
    </div>

    <!-- Calendario Pequeño -->
    <div class="calendar">
      <h3>Próximos Vencimientos</h3>
      <ul>
        <li>10 mayo - SOAT JSK13</li>
        <li>12 mayo - Revisión Técnica ABC123</li>
        <li>20 mayo - Cambio de aceite DEF456</li>
      </ul>
    </div>
  </div>

  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById('dropdown');
      dropdown.classList.toggle('show');
    }

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
