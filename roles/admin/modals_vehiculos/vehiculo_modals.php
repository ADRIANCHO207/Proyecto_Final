<?php
require_once('../../../conecct/conex.php');
$db = new Database();
$con = $db->conectar();

// Consulta marcas
$marcas = $con->query("SELECT id_marca, nombre_marca FROM marca ORDER BY nombre_marca ASC")->fetchAll(PDO::FETCH_ASSOC);

// Consulta estados
$estados = $con->query("SELECT id_estado, estado FROM estado_vehiculo ORDER BY estado ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Modal para editar vehículo -->
<div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVehicleModalLabel">Editar Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editVehicleForm">
                    <div class="mb-3">
                        <label for="editPlaca" class="form-label">Placa</label>
                        <input type="text" class="form-control" id="editPlaca" name="placa" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editDocumento" class="form-label">Documento</label>
                        <input type="text" class="form-control" id="editDocumento" name="documento" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editMarca" class="form-label">Marca</label>
                        <select class="form-control" id="editMarca" name="id_marca">
                            <option value="">Seleccione una marca</option>
                            <?php foreach ($marcas as $marca): ?>
                                <option value="<?= htmlspecialchars($marca['id_marca']) ?>">
                                    <?= htmlspecialchars($marca['nombre_marca']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editModelo" class="form-label">Modelo</label>
                        <input type="text" class="form-control" id="editModelo" name="modelo">
                    </div>
                    <div class="mb-3">
                        <label for="editEstado" class="form-label">Estado</label>
                        <select class="form-control" id="editEstado" name="id_estado">
                            <option value="">Seleccione un estado</option>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?= htmlspecialchars($estado['id_estado']) ?>">
                                    <?= htmlspecialchars($estado['estado']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editKilometraje" class="form-label">Kilometraje</label>
                        <input type="number" class="form-control" id="editKilometraje" name="kilometraje_actual">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para eliminar vehículo -->
<div class="modal fade" id="deleteVehicleModal" tabindex="-1" aria-labelledby="deleteVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVehicleModalLabel">Eliminar Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas marcar como inactivo el vehículo con placa <strong id="deletePlacaDisplay"></strong>?</p>
                <form id="deleteVehicleForm">
                    <input type="hidden" id="deletePlaca" name="placa">
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para notificación -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <p id="notificationMessage"></p>
            </div>
        </div>
    </div>
</div>