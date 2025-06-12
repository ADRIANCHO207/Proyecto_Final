<!-- Modal para Agregar Vehículo -->
<div class="modal fade" id="modalAgregarVehiculo" tabindex="-1" aria-labelledby="modalAgregarVehiculoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarVehiculoLabel">
          <i class="bi bi-plus-circle"></i> Agregar Nuevo Vehículo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formAgregarVehiculo" action="procesar_vehiculo.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="accion" value="agregar">
          
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="placa" class="form-label">Placa del Vehículo *</label>
                <input type="text" class="form-control" id="placa" name="placa" required 
                       placeholder="Ej: ABC123" maxlength="7" pattern="[A-Za-z0-9]{6,7}">
                <div class="form-text">Formato: 3 letras y 3 números (sin guiones)</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="documento" class="form-label">Documento del Propietario *</label>
                <select class="form-select" id="documento" name="documento" required>
                  <option value="">Seleccione un propietario</option>
                  <?php
                  $usuarios = $con->prepare("SELECT documento, nombre_completo FROM usuarios ORDER BY nombre_completo");
                  $usuarios->execute();
                  while ($usuario = $usuarios->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($usuario['documento']) . '">' . 
                         htmlspecialchars($usuario['documento'] . ' - ' . $usuario['nombre_completo']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="marca" class="form-label">Marca *</label>
                <select class="form-select" id="marca" name="id_marca" required>
                  <option value="">Seleccione una marca</option>
                  <?php
                  $marcas = $con->prepare("SELECT id_marca, nombre_marca FROM marca ORDER BY nombre_marca");
                  $marcas->execute();
                  while ($marca = $marcas->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $marca['id_marca'] . '">' . htmlspecialchars($marca['nombre_marca']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="modelo" class="form-label">Modelo (Año) *</label>
                <select class="form-select" id="modelo" name="modelo" required>
                  <option value="">Seleccione un año</option>
                  <?php
                  $anio_actual = date('Y');
                  for ($i = $anio_actual; $i >= $anio_actual - 30; $i--) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="color" class="form-label">Color *</label>
                <input type="text" class="form-control" id="color" name="color" required placeholder="Ej: Blanco">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="kilometraje" class="form-label">Kilometraje Actual *</label>
                <input type="number" class="form-control" id="kilometraje" name="kilometraje_actual" 
                       required min="0" step="1" placeholder="Ej: 15000">
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="estado" class="form-label">Estado *</label>
                <select class="form-select" id="estado" name="id_estado" required>
                  <option value="">Seleccione un estado</option>
                  <?php
                  $estados = $con->prepare("SELECT id_estado, estado FROM estado_vehiculo ORDER BY estado");
                  $estados->execute();
                  while ($estado = $estados->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $estado['id_estado'] . '">' . htmlspecialchars($estado['estado']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="tipo_vehiculo" class="form-label">Tipo de Vehículo *</label>
                <select class="form-select" id="tipo_vehiculo" name="tipo_vehiculo" required>
                  <option value="">Seleccione un tipo</option>
                  <option value="Automóvil">Automóvil</option>
                  <option value="Camioneta">Camioneta</option>
                  <option value="Camión">Camión</option>
                  <option value="Motocicleta">Motocicleta</option>
                  <option value="Otro">Otro</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-12">
              <div class="form-group">
                <label for="foto_vehiculo" class="form-label">Foto del Vehículo</label>
                <input type="file" class="form-control" id="foto_vehiculo" name="foto_vehiculo" 
                       accept="image/jpeg, image/png, image/jpg">
                <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 2MB</div>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-12">
              <div class="form-group">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" 
                          rows="3" placeholder="Detalles adicionales sobre el vehículo..."></textarea>
              </div>
            </div>
          </div>

          <div class="form-group mt-4">
            <div class="d-flex justify-content-between">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Cancelar
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Vehículo
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Editar Vehículo -->
<div class="modal fade" id="modalEditarVehiculo" tabindex="-1" aria-labelledby="modalEditarVehiculoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarVehiculoLabel">
          <i class="bi bi-pencil-square"></i> Editar Vehículo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarVehiculo" action="procesar_vehiculo.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="accion" value="editar">
          <input type="hidden" name="id_vehiculo" id="edit_id_vehiculo">
          
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_placa" class="form-label">Placa del Vehículo *</label>
                <input type="text" class="form-control" id="edit_placa" name="placa" required 
                       placeholder="Ej: ABC123" maxlength="7" pattern="[A-Za-z0-9]{6,7}" readonly>
                <div class="form-text">La placa no se puede modificar</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_documento" class="form-label">Documento del Propietario *</label>
                <select class="form-select" id="edit_documento" name="documento" required>
                  <option value="">Seleccione un propietario</option>
                  <?php
                  $usuarios = $con->prepare("SELECT documento, nombre_completo FROM usuarios ORDER BY nombre_completo");
                  $usuarios->execute();
                  while ($usuario = $usuarios->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($usuario['documento']) . '">' . 
                         htmlspecialchars($usuario['documento'] . ' - ' . $usuario['nombre_completo']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_marca" class="form-label">Marca *</label>
                <select class="form-select" id="edit_marca" name="id_marca" required>
                  <option value="">Seleccione una marca</option>
                  <?php
                  $marcas = $con->prepare("SELECT id_marca, nombre_marca FROM marca ORDER BY nombre_marca");
                  $marcas->execute();
                  while ($marca = $marcas->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $marca['id_marca'] . '">' . htmlspecialchars($marca['nombre_marca']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_modelo" class="form-label">Modelo (Año) *</label>
                <select class="form-select" id="edit_modelo" name="modelo" required>
                  <option value="">Seleccione un año</option>
                  <?php
                  $anio_actual = date('Y');
                  for ($i = $anio_actual; $i >= $anio_actual - 30; $i--) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_color" class="form-label">Color *</label>
                <input type="text" class="form-control" id="edit_color" name="color" required placeholder="Ej: Blanco">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_kilometraje" class="form-label">Kilometraje Actual *</label>
                <input type="number" class="form-control" id="edit_kilometraje" name="kilometraje_actual" 
                       required min="0" step="1" placeholder="Ej: 15000">
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_estado" class="form-label">Estado *</label>
                <select class="form-select" id="edit_estado" name="id_estado" required>
                  <option value="">Seleccione un estado</option>
                  <?php
                  $estados = $con->prepare("SELECT id_estado, estado FROM estado_vehiculo ORDER BY estado");
                  $estados->execute();
                  while ($estado = $estados->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $estado['id_estado'] . '">' . htmlspecialchars($estado['estado']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_tipo_vehiculo" class="form-label">Tipo de Vehículo *</label>
                <select class="form-select" id="edit_tipo_vehiculo" name="tipo_vehiculo" required>
                  <option value="">Seleccione un tipo</option>
                  <option value="Automóvil">Automóvil</option>
                  <option value="Camioneta">Camioneta</option>
                  <option value="Camión">Camión</option>
                  <option value="Motocicleta">Motocicleta</option>
                  <option value="Otro">Otro</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-12">
              <div class="form-group">
                <label for="edit_foto_vehiculo" class="form-label">Foto del Vehículo</label>
                <div class="d-flex align-items-center gap-3 mb-2">
                  <div id="preview_foto_actual" class="vehicle-image-preview">
                    <img src="/placeholder.svg" alt="Vista previa" id="img_preview" class="img-thumbnail" style="max-height: 100px;">
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="mantener_foto" name="mantener_foto" value="1" checked>
                    <label class="form-check-label" for="mantener_foto">
                      Mantener foto actual
                    </label>
                  </div>
                </div>
                <input type="file" class="form-control" id="edit_foto_vehiculo" name="foto_vehiculo" 
                       accept="image/jpeg, image/png, image/jpg">
                <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 2MB</div>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-12">
              <div class="form-group">
                <label for="edit_observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="edit_observaciones" name="observaciones" 
                          rows="3" placeholder="Detalles adicionales sobre el vehículo..."></textarea>
              </div>
            </div>
          </div>

          <div class="form-group mt-4">
            <div class="d-flex justify-content-between">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Cancelar
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Cambios
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
