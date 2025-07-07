<?php
// Archivo de modales para gestión de vehículos
// Incluir archivo de conexión a la base de datos
require_once('../../conecct/conex.php');
$db = new Database();
$con = $db->conectar();

// Obtener marcas para el dropdown del modal de edición
$marcas_query = $con->prepare("SELECT DISTINCT id_marca, nombre_marca FROM marca ORDER BY nombre_marca");
$marcas_query->execute();
$marcas = $marcas_query->fetchAll(PDO::FETCH_ASSOC);

// Obtener estados para el dropdown del modal de edición
$estados_query = $con->prepare("SELECT id_estado, estado FROM estado_vehiculo ORDER BY estado");
$estados_query->execute();
$estados = $estados_query->fetchAll(PDO::FETCH_ASSOC);

// Obtener usuarios activos para el dropdown del modal de edición
$usuarios_query = $con->prepare("SELECT documento, nombre_completo FROM usuarios WHERE id_estado_usuario = 1 ORDER BY nombre_completo");
$usuarios_query->execute();
$usuarios = $usuarios_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Modal para Editar Vehículo -->
<div class="modal fade" id="editarVehiculoModal" tabindex="-1" aria-labelledby="editarVehiculoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarVehiculoModalLabel">
                    <i class="bi bi-pencil-square"></i> Editar Vehículo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Formulario de edición con soporte para archivos (multipart/form-data) -->
            <form id="editarVehiculoForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Campo oculto para almacenar la placa del vehículo -->
                    <input type="hidden" id="editPlaca" name="placa">
                    
                    <div class="row">
                        <!-- Selector de propietario del vehículo -->
                        <div class="col-md-6 mb-3">
                            <label for="editDocumento" class="form-label">Propietario</label>
                            <select class="form-select" id="editDocumento" name="documento" required>
                                <option value="">Seleccione un propietario</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?= htmlspecialchars($usuario['documento']) ?>">
                                        <?= htmlspecialchars($usuario['nombre_completo']) . ' (' . $usuario['documento'] . ')' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Selector de marca del vehículo -->
                        <div class="col-md-6 mb-3">
                            <label for="editMarca" class="form-label">Marca</label>
                            <select class="form-select" id="editMarca" name="id_marca" required>
                                <option value="">Seleccione una marca</option>
                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?= htmlspecialchars($marca['id_marca']) ?>">
                                        <?= htmlspecialchars($marca['nombre_marca']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Campo para el año/modelo del vehículo -->
                        <div class="col-md-6 mb-3">
                            <label for="editModelo" class="form-label">Modelo (Año)</label>
                            <input type="number" class="form-control" id="editModelo" name="modelo" min="1900" max="2099" required>
                        </div>
                        
                        <!-- Campo para el kilometraje actual -->
                        <div class="col-md-6 mb-3">
                            <label for="editKilometraje" class="form-label">Kilometraje Actual</label>
                            <input type="number" class="form-control" id="editKilometraje" name="kilometraje_actual" min="0" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Selector de estado del vehículo -->
                        <div class="col-md-6 mb-3">
                            <label for="editEstado" class="form-label">Estado</label>
                            <select class="form-select" id="editEstado" name="id_estado" required>
                                <option value="">Seleccione un estado</option>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= htmlspecialchars($estado['id_estado']) ?>">
                                        <?= htmlspecialchars($estado['estado']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Campo para subir foto del vehículo -->
                        <div class="col-md-6 mb-3">
                            <label for="editFoto" class="form-label">Foto del Vehículo</label>
                            <input type="file" class="form-control" id="editFoto" name="foto_vehiculo" accept="image/*">
                            <small class="form-text text-muted">Deje en blanco para mantener la imagen actual.</small>
                        </div>
                    </div>
                    
                    <!-- Área de vista previa de la imagen -->
                    <div class="mb-3">
                        <img id="editFotoPreview" src="" alt="Vista previa" class="img-fluid vehicle-image-preview" style="display: none;">
                    </div>
                </div>
                
                <!-- Botones de acción del modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Eliminar Vehículo -->
<div class="modal fade" id="eliminarVehiculoModal" tabindex="-1" aria-labelledby="eliminarVehiculoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarVehiculoModalLabel">
                    <i class="bi bi-trash"></i> Eliminar Vehículo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Cuerpo del modal con mensaje de confirmación -->
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar el vehículo con placa <strong id="deletePlaca"></strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            
            <!-- Botones de confirmación y cancelación -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarEliminar">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>