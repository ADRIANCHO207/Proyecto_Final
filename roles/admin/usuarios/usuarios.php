<?php
session_start();
require_once('../../../conecct/conex.php');
include '../../../includes/validarsession.php';
$db = new Database();
$con = $db->conectar();
$code = $_SESSION['documento'];
$sql = $con->prepare("SELECT * FROM usuarios INNER JOIN roles ON usuarios.id_rol = roles.id_rol INNER JOIN estado_usuario ON usuarios.id_estado_usuario = estado_usuario.id_estado WHERE documento");
$fila = $sql->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            display: flex;
            align-items: center;
            margin-left: 20px;
        }
        .logo-redondo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }
        .empresa {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .menu {
            display: flex;
            gap: 20px;
        }
        .menu a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        .menu a:hover {
            color: #17a2b8;
        }
        .perfil {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        .imagen-usuario {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        .info-usuario {
            font-size: 0.9rem;
        }
        .boton {
            background-color: #17a2b8;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
        }
        .boton:hover {
            background-color: #138496;
            color: white;
        }
        .boton-inicio {
            margin-right: auto;
            margin-left: 20px;
        }
        #servicios {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        #servicios h2 {
            color: #343a40;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }
        .table {
            margin-top: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #d32f2f;
            color: white;
            vertical-align: middle;
        }
        .table td {
            vertical-align: middle;
        }
        .boton-agregar {
            margin-top: 20px;
            text-align: right;
        }
        .table input[readonly] {
            border: none;
            background: transparent;
            width: 100%;
        }
        .action-icon {
            margin: 0 5px;
            transition: transform 0.2s;
        }
        .action-icon:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<div class="header">
    <div class="logo">

        <img src="../../../css/img/logo_sinfondo.png" alt="Logo" class="logo-redondo">
        <span class="empresa">Flotax AGC</span>
    </div>
    <div class="boton-inicio">
        <a href="../index.php" class="boton">Atrás</a>
    </div>
  
    <div class="perfil">
        <img src="../perfil.jpg" alt="Usuario" class="imagen-usuario">
        <div class="info-usuario">
            <span>Nombres, Apellidos</span>
            <br>
            <span>Perfil Administrador</span>
        </div>
    </div>
</div>

<section id="servicios">
    <h2>Administración de Usuarios</h2>
    
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <!-- <th>Vehiculo</th> -->
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = $con->prepare("SELECT * FROM usuarios INNER JOIN roles ON usuarios.id_rol = roles.id_rol INNER JOIN estado_usuario ON usuarios.id_estado_usuario = estado_usuario.id_estado WHERE documento");
                $sql->execute();
                $fila = $sql->fetchAll(PDO::FETCH_ASSOC);
                $count = 1;
                foreach ($fila as $resu) {
                ?>
                <tr class="text-center">    
                    <td><?php echo $count++; ?></td>
                    <td><?php echo htmlspecialchars($resu['nombre_completo']); ?></td>
                    <td><?php echo htmlspecialchars($resu['documento']); ?></td>
                    <td><?php echo htmlspecialchars($resu['email']); ?></td>
                    <td><?php echo htmlspecialchars($resu['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($resu['tipo_stade']); ?></td>
                    <td><?php echo htmlspecialchars($resu['tip_rol']); ?></td>
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
    </div>
    
    <div class="boton-agregar">
        <a href="agregar_usuario.php" class="boton">
            <i class="bi bi-plus-circle"></i> Agregar Usuario
        </a>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmarEliminacion(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
            window.location.href = `delete.php?id=${id}`;
        }
    }
</script>
</body>
</html>