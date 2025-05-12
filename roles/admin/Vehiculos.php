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
  <!-- Navbar con notificaciones -->
  <div class="navbar">
    <h1>Panel de Administrador</h1>
    <div class="notification" onclick="toggleDropdown()"><i class="bi bi-bell"></i> <span class="badge">3</span>
      <div class="dropdown" id="dropdown">
        <p><i class="bi bi-record-fill" style="color:#d32f2f ;"></i> SOAT de vehículo JSK13 vence en 3 días</p>
        <p><i class="bi bi-record-fill" style="color:#d32f2f ;"></i> Multa sin pagar del vehículo ASDJ</p>
        <p><i class="bi bi-record-fill" style="color:#d32f2f ;"></i> Revisión Tecnomecánica vencida</p>
      </div>
    </div>
  </div>

  <div class="sidebar">
    <?php include 'menu.html'; ?> 
  </div>

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

    </div>
    
    
    <div class="boton-agregar">
        <a href="agregar_usuario.php" class="boton">
            <i class="bi bi-plus-circle"></i> <i class="bi bi-search"></i>Agregar Usuario
        </a>
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
