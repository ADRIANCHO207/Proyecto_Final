<!-- Modal para Editar Vehículo -->
<div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVehicleModalLabel">Editar Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editVehicleForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editPlaca" class="form-label">Placa</label>
                        <input type="text" class="form-control" id="editPlaca" name="placa" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editDocumento" class="form-label">Documento Propietario</label>
                        <input type="text" class="form-control" id="editDocumento" name="documento" required>
                    </div>
                    <div class="mb-3">
                        <label for="editMarca" class="form-label">Marca</label>
                        <select class="form-control" id="editMarca" name="marca" required>
                            <?php
                            require_once('../../conecct/conex.php');
                            $db = new Database();
                            $con = $db->conectar();
                            $marcas_query = $con->prepare("SELECT * FROM marca ORDER BY nombre_marca");
                            $marcas_query->execute();
                            $marcas = $marcas_query->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($marcas as $marca):
                            ?>
                            <option value="<?= htmlspecialchars($marca['id_marca']) ?>">
                                <?= htmlspecialchars($marca['nombre_marca']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editModelo" class="form-label">Modelo (Año)</label>
                        <input type="number" class="form-control" id="editModelo" name="modelo" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEstado" class="form-label">Estado</label>
                        <select class="form-control" id="editEstado" name="estado" required>
                            <?php
                            $estados_query = $con->prepare("SELECT * FROM estado_vehiculo ORDER BY estado");
                            $estados_query->execute();
                            $estados = $estados_query->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($estados as $estado):
                            ?>
                            <option value="<?= htmlspecialchars($estado['id_estado']) ?>">
                                <?= htmlspecialchars($estado['estado']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editKilometraje" class="form-label">Kilometraje Actual</label>
                        <input type="number" class="form-control" id="editKilometraje" name="kilometraje" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Eliminar Vehículo -->
<div class="modal fade" id="deleteVehicleModal" tabindex="-1" aria-labelledby="deleteVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVehicleModalLabel">Eliminar Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteVehicleForm">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este vehículo?</p>
                    <input type="hidden" id="deletePlaca" name="placa">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>