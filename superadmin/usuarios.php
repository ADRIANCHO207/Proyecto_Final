<?php
session_start();

// Verificar autenticación de superadmin
if (!isset($_SESSION['superadmin_logged']) || $_SESSION['superadmin_logged'] !== true) {
    header('Location: login.php');
    exit;
}

$nombre_superadmin = $_SESSION['superadmin_nombre'] ?? 'Superadmin';

require_once('../conecct/conex.php');
$db = new Database();
$con = $db->conectar();

// Obtener roles para el formulario
$roles_sql = $con->prepare("SELECT * FROM roles ORDER BY id_rol");
$roles_sql->execute();
$roles = $roles_sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Superadmin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --dark-color: #34495e;
            --light-color: #ecf0f1;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .user-info {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 15px;
            margin: 20px 10px;
            text-align: center;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 1.5rem;
            color: white;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-top: 20px;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .page-header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .filters-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h6 class="text-white mb-1"><?php echo htmlspecialchars($nombre_superadmin); ?></h6>
                    <small class="text-white-50">Superadministrador</small>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link active" href="usuarios.php">
                        <i class="fas fa-users me-2"></i> Gestión de Usuarios
                    </a>
                    <a class="nav-link" href="vehiculos.php">
                        <i class="fas fa-car me-2"></i> Gestión de Vehículos
                    </a>
                    <a class="nav-link" href="reportes.php">
                        <i class="fas fa-chart-bar me-2"></i> Reportes Avanzados
                    </a>
                    <a class="nav-link" href="configuracion.php">
                        <i class="fas fa-cog me-2"></i> Configuración
                    </a>
                    <a class="nav-link" href="logs.php">
                        <i class="fas fa-file-alt me-2"></i> Logs del Sistema
                    </a>
                    <a class="nav-link text-danger" href="logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </a>
                </nav>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-1"><i class="fas fa-users me-2"></i> Gestión de Usuarios</h1>
                            <p class="text-muted mb-0">Administra todos los usuarios del sistema</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario">
                            <i class="fas fa-plus me-2"></i> Nuevo Usuario
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="filters-card">
                    <h5 class="mb-3"><i class="fas fa-filter me-2"></i> Filtros</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="filtroRol" class="form-label">Filtrar por Rol:</label>
                            <select class="form-select" id="filtroRol">
                                <option value="">Todos los roles</option>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?php echo $rol['id_rol']; ?>"><?php echo htmlspecialchars($rol['tip_rol']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroEstado" class="form-label">Filtrar por Estado:</label>
                            <select class="form-select" id="filtroEstado">
                                <option value="">Todos los estados</option>
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="buscarUsuario" class="form-label">Buscar:</label>
                            <input type="text" class="form-control" id="buscarUsuario" placeholder="Buscar por nombre, documento o email...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-secondary" id="limpiarFiltros">
                                <i class="fas fa-eraser me-1"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla de usuarios -->
                <div class="table-container">
                    <h5 class="mb-3"><i class="fas fa-table me-2"></i> Lista de Usuarios</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tablaUsuarios">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Documento</th>
                                    <th>Nombre Completo</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal para crear/editar usuario -->
    <div class="modal fade" id="modalUsuario" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUsuarioTitle">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formUsuario">
                    <div class="modal-body">
                        <input type="hidden" id="usuarioId" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="documento" class="form-label">Documento *</label>
                                    <input type="text" class="form-control" id="documento" name="documento" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipoDocumento" class="form-label">Tipo Documento *</label>
                                    <select class="form-select" id="tipoDocumento" name="tipo_documento" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="CC">Cédula de Ciudadanía</option>
                                        <option value="CE">Cédula de Extranjería</option>
                                        <option value="TI">Tarjeta de Identidad</option>
                                        <option value="PP">Pasaporte</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombres" class="form-label">Nombres *</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apellidos" class="form-label">Apellidos *</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="fechaNacimiento" name="fecha_nacimiento">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rol" class="form-label">Rol *</label>
                                    <select class="form-select" id="rol" name="id_rol" required>
                                        <option value="">Seleccionar rol...</option>
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?php echo $rol['id_rol']; ?>"><?php echo htmlspecialchars($rol['tip_rol']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado *</label>
                                    <select class="form-select" id="estado" name="id_estado_usuario" required>
                                        <option value="1">Activo</option>
                                        <option value="2">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="passwordSection">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña *</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <div class="form-text">Mínimo 6 caracteres</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Confirmar Contraseña *</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirm_password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnGuardarUsuario">
                            <i class="fas fa-save"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea desactivar este usuario?</p>
                    <p class="text-muted">Esta acción cambiará el estado del usuario a inactivo.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
                        <i class="fas fa-trash"></i> Desactivar Usuario
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        let tabla;
        let usuarioIdEliminar = null;

        // Inicializar DataTable
        function inicializarTabla() {
            tabla = $('#tablaUsuarios').DataTable({
                ajax: {
                    url: 'usuarios_backend.php',
                    type: 'POST',
                    data: { accion: 'listar' },
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id_usuario' },
                    { data: 'documento' },
                    { data: 'nombre_completo' },
                    { data: 'email' },
                    { data: 'telefono' },
                    { data: 'tip_rol' },
                    { 
                        data: 'id_estado_usuario',
                        render: function(data) {
                            return data == 1 ? 
                                '<span class="badge bg-success">Activo</span>' : 
                                '<span class="badge bg-danger">Inactivo</span>';
                        }
                    },
                    { 
                        data: 'fecha_registro',
                        render: function(data) {
                            return data ? new Date(data).toLocaleDateString('es-ES') : '';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarUsuario(${row.id_usuario})" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarUsuario(${row.id_usuario})" title="Desactivar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']]
            });
        }

        // Inicializar tabla
        inicializarTabla();

        // Filtros
        $('#filtroRol, #filtroEstado').change(function() {
            aplicarFiltros();
        });

        $('#buscarUsuario').on('keyup', function() {
            tabla.search(this.value).draw();
        });

        $('#limpiarFiltros').click(function() {
            $('#filtroRol, #filtroEstado, #buscarUsuario').val('');
            tabla.search('').columns().search('').draw();
        });

        function aplicarFiltros() {
            let rol = $('#filtroRol').val();
            let estado = $('#filtroEstado').val();
            
            tabla.column(5).search(rol).draw();
            tabla.column(6).search(estado).draw();
        }

        // Formulario de usuario
        $('#formUsuario').submit(function(e) {
            e.preventDefault();
            
            // Validar contraseñas
            let password = $('#password').val();
            let confirmPassword = $('#confirmPassword').val();
            let isEdit = $('#usuarioId').val() !== '';
            
            if (!isEdit && password !== confirmPassword) {
                Swal.fire('Error', 'Las contraseñas no coinciden', 'error');
                return;
            }
            
            if (!isEdit && password.length < 6) {
                Swal.fire('Error', 'La contraseña debe tener al menos 6 caracteres', 'error');
                return;
            }
            
            let formData = new FormData(this);
            formData.append('accion', isEdit ? 'actualizar' : 'crear');
            
            $.ajax({
                url: 'usuarios_backend.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#modalUsuario').modal('hide');
                        tabla.ajax.reload();
                        Swal.fire('Éxito', response.message, 'success');
                        $('#formUsuario')[0].reset();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Error de conexión', 'error');
                }
            });
        });

        // Limpiar formulario al cerrar modal
        $('#modalUsuario').on('hidden.bs.modal', function() {
            $('#formUsuario')[0].reset();
            $('#usuarioId').val('');
            $('#modalUsuarioTitle').text('Nuevo Usuario');
            $('#passwordSection').show();
            $('#password, #confirmPassword').prop('required', true);
        });

        // Confirmar eliminación
        $('#btnConfirmarEliminar').click(function() {
            if (usuarioIdEliminar) {
                $.ajax({
                    url: 'usuarios_backend.php',
                    type: 'POST',
                    data: {
                        accion: 'eliminar',
                        id: usuarioIdEliminar
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#modalEliminar').modal('hide');
                            tabla.ajax.reload();
                            Swal.fire('Éxito', response.message, 'success');
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Error de conexión', 'error');
                    }
                });
            }
        });
    });

    // Funciones globales
    function editarUsuario(id) {
        $.ajax({
            url: 'usuarios_backend.php',
            type: 'POST',
            data: {
                accion: 'obtener',
                id: id
            },
            success: function(response) {
                if (response.status === 'success') {
                    let usuario = response.data;
                    
                    $('#usuarioId').val(usuario.id_usuario);
                    $('#documento').val(usuario.documento);
                    $('#tipoDocumento').val(usuario.tipo_documento);
                    $('#nombres').val(usuario.nombres);
                    $('#apellidos').val(usuario.apellidos);
                    $('#email').val(usuario.email);
                    $('#telefono').val(usuario.telefono);
                    $('#fechaNacimiento').val(usuario.fecha_nacimiento);
                    $('#direccion').val(usuario.direccion);
                    $('#rol').val(usuario.id_rol);
                    $('#estado').val(usuario.id_estado_usuario);
                    
                    $('#modalUsuarioTitle').text('Editar Usuario');
                    $('#passwordSection').hide();
                    $('#password, #confirmPassword').prop('required', false);
                    
                    $('#modalUsuario').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Error de conexión', 'error');
            }
        });
    }

    function eliminarUsuario(id) {
        usuarioIdEliminar = id;
        $('#modalEliminar').modal('show');
    }
    </script>
</body>
</html>