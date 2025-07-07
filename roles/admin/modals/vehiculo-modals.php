<?php
/**
 * MODALES PARA GESTIÓN DE VEHÍCULOS
 * 
 * Este archivo contiene los modales de Bootstrap para:
 * 1. Agregar nuevos vehículos al sistema
 * 2. Editar vehículos existentes
 * 
 * Características principales:
 * - Formularios responsivos con validación HTML5
 * - Integración con base de datos para cargar opciones dinámicas
 * - Manejo de archivos de imagen para fotos de vehículos
 * - Interfaz de usuario moderna con Bootstrap 5 e iconos
 * - Validación de campos obligatorios y formatos específicos
 */
?>

<!-- 
    MODAL PARA AGREGAR VEHÍCULO
    
    Modal principal para el registro de nuevos vehículos en el sistema.
    Incluye todos los campos necesarios para la información completa del vehículo.
-->
<div class="modal fade" id="modalAgregarVehiculo" tabindex="-1" aria-labelledby="modalAgregarVehiculoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Modal grande para acomodar todos los campos -->
    <div class="modal-content">
      <!-- Encabezado del modal con título e icono descriptivo -->
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarVehiculoLabel">
          <i class="bi bi-plus-circle"></i> Agregar Nuevo Vehículo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <!-- 
            FORMULARIO DE REGISTRO DE VEHÍCULO
            
            Configuración:
            - Método POST para envío seguro de datos
            - enctype="multipart/form-data" para manejo de archivos de imagen
            - Acción dirigida a procesar_vehiculo.php para el procesamiento backend
        -->
        <form id="formAgregarVehiculo" action="procesar_vehiculo.php" method="POST" enctype="multipart/form-data">
          <!-- Campo oculto para identificar la acción en el backend -->
          <input type="hidden" name="accion" value="agregar">
          
          <!-- FILA 1: Información básica del vehículo -->
          <div class="row mb-3">
            <!-- Campo de placa con validación estricta -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="placa" class="form-label">Placa del Vehículo *</label>
                <input type="text" class="form-control" id="placa" name="placa" required 
                       placeholder="Ej: ABC123" maxlength="7" pattern="[A-Za-z0-9]{6,7}">
                <div class="form-text">Formato: 3 letras y 3 números (sin guiones)</div>
              </div>
            </div>
            
            <!-- Selector dinámico de propietarios desde la base de datos -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="documento" class="form-label">Documento del Propietario *</label>
                <select class="form-select" id="documento" name="documento" required>
                  <option value="">Seleccione un propietario</option>
                  <?php
                  // Consulta para obtener todos los usuarios registrados
                  $usuarios = $con->prepare("SELECT documento, nombre_completo FROM usuarios ORDER BY nombre_completo");
                  $usuarios->execute();
                  // Generación dinámica de opciones con escape de caracteres para seguridad
                  while ($usuario = $usuarios->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($usuario['documento']) . '">' . 
                         htmlspecialchars($usuario['documento'] . ' - ' . $usuario['nombre_completo']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <!-- FILA 2: Especificaciones del vehículo -->
          <div class="row mb-3">
            <!-- Selector de marca desde catálogo de la base de datos -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="marca" class="form-label">Marca *</label>
                <select class="form-select" id="marca" name="id_marca" required>
                  <option value="">Seleccione una marca</option>
                  <?php
                  // Consulta para cargar marcas disponibles
                  $marcas = $con->prepare("SELECT id_marca, nombre_marca FROM marca ORDER BY nombre_marca");
                  $marcas->execute();
                  while ($marca = $marcas->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $marca['id_marca'] . '">' . htmlspecialchars($marca['nombre_marca']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            
            <!-- Generación automática de años para modelo -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="modelo" class="form-label">Modelo (Año) *</label>
                <select class="form-select" id="modelo" name="modelo" required>
                  <option value="">Seleccione un año</option>
                  <?php
                  // Generación dinámica de años (actual hasta 30 años atrás)
                  $anio_actual = date('Y');
                  for ($i = $anio_actual; $i >= $anio_actual - 30; $i--) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <!-- FILA 3: Características físicas y estado -->
          <div class="row mb-3">
            <!-- Campo de texto libre para color -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="color" class="form-label">Color *</label>
                <input type="text" class="form-control" id="color" name="color" required placeholder="Ej: Blanco">
              </div>
            </div>
            
            <!-- Campo numérico para kilometraje con validación -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="kilometraje" class="form-label">Kilometraje Actual *</label>
                <input type="number" class="form-control" id="kilometraje" name="kilometraje_actual" 
                       required min="0" step="1" placeholder="Ej: 15000">
              </div>
            </div>
          </div>

          <!-- FILA 4: Estado y clasificación del vehículo -->
          <div class="row mb-3">
            <!-- Selector de estado desde catálogo de la base de datos -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="estado" class="form-label">Estado *</label>
                <select class="form-select" id="estado" name="id_estado" required>
                  <option value="">Seleccione un estado</option>
                  <?php
                  // Consulta para estados de vehículo disponibles
                  $estados = $con->prepare("SELECT id_estado, estado FROM estado_vehiculo ORDER BY estado");
                  $estados->execute();
                  while ($estado = $estados->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $estado['id_estado'] . '">' . htmlspecialchars($estado['estado']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            
            <!-- Selector de tipo con opciones predefinidas -->
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

          <!-- FILA 5: Manejo de archivos de imagen -->
          <div class="row mb-3">
            <div class="col-md-12">
              <div class="form-group">
                <label for="foto_vehiculo" class="form-label">Foto del Vehículo</label>
                <!-- 
                    Campo de archivo con restricciones de tipo y tamaño
                    - Solo acepta formatos de imagen comunes
                    - Validación adicional en el backend para seguridad
                -->
                <input type="file" class="form-control" id="foto_vehiculo" name="foto_vehiculo" 
                       accept="image/jpeg, image/png, image/jpg">
                <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 2MB</div>
              </div>
            </div>
          </div>

          <!-- FILA 6: Campo de observaciones adicionales -->
          <div class="row mb-3">
            <div class="col-md-12">
              <div class="form-group">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" 
                          rows="3" placeholder="Detalles adicionales sobre el vehículo..."></textarea>
              </div>
            </div>
          </div>

          <!-- Botones de acción del formulario -->
          <div class="form-group mt-4">
            <div class="d-flex justify-content-between">
              <!-- Botón para cancelar y cerrar modal -->
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Cancelar
              </button>
              <!-- Botón de envío del formulario -->
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

<!-- 
    MODAL PARA EDITAR VEHÍCULO
    
    Modal para modificar información de vehículos existentes.
    Incluye funcionalidades adicionales como:
    - Previsualización de imagen actual
    - Opción para mantener foto existente
    - Campo de placa de solo lectura (no modificable)
-->
<div class="modal fade" id="modalEditarVehiculo" tabindex="-1" aria-labelledby="modalEditarVehiculoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Encabezado del modal de edición -->
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarVehiculoLabel">
          <i class="bi bi-pencil-square"></i> Editar Vehículo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <!-- 
            FORMULARIO DE EDICIÓN DE VEHÍCULO
            
            Diferencias con el formulario de agregar:
            - Incluye campo oculto para ID del vehículo
            - Campo de placa en modo solo lectura
            - Gestión avanzada de imágenes con previsualización
        -->
        <form id="formEditarVehiculo" action="procesar_vehiculo.php" method="POST" enctype="multipart/form-data">
          <!-- Campos ocultos para identificación -->
          <input type="hidden" name="accion" value="editar">
          <input type="hidden" name="id_vehiculo" id="edit_id_vehiculo">
          
          <!-- FILA 1: Información básica (placa no editable) -->
          <div class="row mb-3">
            <!-- Campo de placa en modo solo lectura -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_placa" class="form-label">Placa del Vehículo *</label>
                <input type="text" class="form-control" id="edit_placa" name="placa" required 
                       placeholder="Ej: ABC123" maxlength="7" pattern="[A-Za-z0-9]{6,7}" readonly>
                <div class="form-text">La placa no se puede modificar</div>
              </div>
            </div>
            
            <!-- Selector de propietario (editable) -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_documento" class="form-label">Documento del Propietario *</label>
                <select class="form-select" id="edit_documento" name="documento" required>
                  <option value="">Seleccione un propietario</option>
                  <?php
                  // Misma consulta que en el modal de agregar
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

          <!-- FILA 2: Especificaciones editables -->
          <div class="row mb-3">
            <!-- Selector de marca -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_marca" class="form-label">Marca *</label>
                <select class="form-select" id="edit_marca" name="id_marca" required>
                  <option value="">Seleccione una marca</option>
                  <?php
                  // Consulta idéntica al modal de agregar
                  $marcas = $con->prepare("SELECT id_marca, nombre_marca FROM marca ORDER BY nombre_marca");
                  $marcas->execute();
                  while ($marca = $marcas->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $marca['id_marca'] . '">' . htmlspecialchars($marca['nombre_marca']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            
            <!-- Selector de año/modelo -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_modelo" class="form-label">Modelo (Año) *</label>
                <select class="form-select" id="edit_modelo" name="modelo" required>
                  <option value="">Seleccione un año</option>
                  <?php
                  // Generación dinámica de años
                  $anio_actual = date('Y');
                  for ($i = $anio_actual; $i >= $anio_actual - 30; $i--) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <!-- FILA 3: Características físicas -->
          <div class="row mb-3">
            <!-- Campo de color -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_color" class="form-label">Color *</label>
                <input type="text" class="form-control" id="edit_color" name="color" required placeholder="Ej: Blanco">
              </div>
            </div>
            
            <!-- Campo de kilometraje -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_kilometraje" class="form-label">Kilometraje Actual *</label>
                <input type="number" class="form-control" id="edit_kilometraje" name="kilometraje_actual" 
                       required min="0" step="1" placeholder="Ej: 15000">
              </div>
            </div>
          </div>

          <!-- FILA 4: Estado y tipo -->
          <div class="row mb-3">
            <!-- Selector de estado -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit_estado" class="form-label">Estado *</label>
                <select class="form-select" id="edit_estado" name="id_estado" required>
                  <option value="">Seleccione un estado</option>
                  <?php
                  // Consulta de estados disponibles
                  $estados = $con->prepare("SELECT id_estado, estado FROM estado_vehiculo ORDER BY estado");
                  $estados->execute();
                  while ($estado = $estados->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $estado['id_estado'] . '">' . htmlspecialchars($estado['estado']) . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            
            <!-- Selector de tipo de vehículo -->
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

          <!-- FILA 5: Gestión avanzada de imágenes -->
          <div class="row mb-3">
            <div class="col-md-12">
              <div class="form-group">
                <label for="edit_foto_vehiculo" class="form-label">Foto del Vehículo</label>
                
                <!-- 
                    SECCIÓN DE PREVISUALIZACIÓN Y CONTROL DE IMAGEN
                    
                    Características:
                    - Muestra la imagen actual del vehículo
                    - Checkbox para mantener la foto existente
                    - Previsualización en tiempo real de nuevas imágenes
                -->
                <div class="d-flex align-items-center gap-3 mb-2">
                  <!-- Contenedor de previsualización de imagen -->
                  <div id="preview_foto_actual" class="vehicle-image-preview">
                    <img src="/placeholder.svg" alt="Vista previa" id="img_preview" class="img-thumbnail" style="max-height: 100px;">
                  </div>
                  
                  <!-- Checkbox para mantener foto actual -->
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="mantener_foto" name="mantener_foto" value="1" checked>
                    <label class="form-check-label" for="mantener_foto">
                      Mantener foto actual
                    </label>
                  </div>
                </div>
                
                <!-- Campo de archivo para nueva imagen -->
                <input type="file" class="form-control" id="edit_foto_vehiculo" name="foto_vehiculo" 
                       accept="image/jpeg, image/png, image/jpg">
                <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Tamaño máximo: 2MB</div>
              </div>
            </div>
          </div>

          <!-- FILA 6: Observaciones -->
          <div class="row mb-3">
            <div class="col-md-12">
              <div class="form-group">
                <label for="edit_observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="edit_observaciones" name="observaciones" 
                          rows="3" placeholder="Detalles adicionales sobre el vehículo..."></textarea>
              </div>
            </div>
          </div>

          <!-- Botones de acción del formulario de edición -->
          <div class="form-group mt-4">
            <div class="d-flex justify-content-between">
              <!-- Botón de cancelación -->
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Cancelar
              </button>
              <!-- Botón de guardado de cambios -->
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
