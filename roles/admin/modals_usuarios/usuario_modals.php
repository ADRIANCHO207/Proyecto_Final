<?php
require_once('../../conecct/conex.php');

$db = new Database();
$con = $db->conectar();
?>

<!-- Modal Agregar Usuario -->
<div class="modal fade" id="agregarUsuarioModal" tabindex="-1" aria-labelledby="agregarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarUsuarioModalLabel">Agregar Nuevo Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="agregarUsuarioForm">
          <div class="mb-3">
            <label for="documentoAgregar" class="form-label">Documento</label>
            <input type="text" class="form-control" id="documentoAgregar" name="documento" required>
          </div>
          <div class="mb-3">
            <label for="nombreCompletoAgregar" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombreCompletoAgregar" name="nombre_completo" required>
          </div>
          <div class="mb-3">
            <label for="emailAgregar" class="form-label">Email</label>
            <input type="email" class="form-control" id="emailAgregar" name="email" required>
          </div>
          <div class="mb-3">
            <label for="passwordAgregar" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="passwordAgregar" name="password" required>
          </div>
          <div class="mb-3">
            <label for="telefonoAgregar" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="telefonoAgregar" name="telefono" required>
          </div>
          <div class="mb-3">
            <label for="estadoAgregar" class="form-label">Estado</label>
            <select class="form-select" id="estadoAgregar" name="estado" required>
              <option value="">Seleccione</option>
              <?php
              $estadoQuery = $con->prepare("SELECT id_estado, tipo_stade FROM estado_usuario");
              $estadoQuery->execute();
              $estados = $estadoQuery->fetchAll(PDO::FETCH_ASSOC);
              foreach ($estados as $estado) {
                echo "<option value='{$estado['id_estado']}'>{$estado['tipo_stade']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="rolAgregar" class="form-label">Rol</label>
            <select class="form-select" id="rolAgregar" name="rol" required>
              <option value="">Seleccione</option>
              <?php
              $rolQuery = $con->prepare("SELECT id_rol, tip_rol FROM roles");
              $rolQuery->execute();
              $roles = $rolQuery->fetchAll(PDO::FETCH_ASSOC);
              foreach ($roles as $rol) {
                echo "<option value='{$rol['id_rol']}'>{$rol['tip_rol']}</option>";
              }
              ?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="guardarUsuario">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editarUsuarioForm">
          <input type="hidden" id="documentoEditar" name="documento">
          <div class="mb-3">
            <label for="nombreCompletoEditar" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="nombreCompletoEditar" name="nombre_completo" required>
          </div>
          <div class="mb-3">
            <label for="emailEditar" class="form-label">Email</label>
            <input type="email" class="form-control" id="emailEditar" name="email" required>
          </div>
          <div class="mb-3">
            <label for="telefonoEditar" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="telefonoEditar" name="telefono" required>
          </div>
          <div class="mb-3">
            <label for="estadoEditar" class="form-label">Estado</label>
            <select class="form-select" id="estadoEditar" name="estado" required>
              <option value="">Seleccione</option>
              <?php
              $estadoQuery = $con->prepare("SELECT id_estado, tipo_stade FROM estado_usuario");
              $estadoQuery->execute();
              $estados = $estadoQuery->fetchAll(PDO::FETCH_ASSOC);
              foreach ($estados as $estado) {
                echo "<option value='{$estado['id_estado']}'>{$estado['tipo_stade']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="rolEditar" class="form-label">Rol</label>
            <select class="form-select" id="rolEditar" name="rol" required>
              <option value="">Seleccione</option>
              <?php
              $rolQuery = $con->prepare("SELECT id_rol, tip_rol FROM roles");
              $rolQuery->execute();
              $roles = $rolQuery->fetchAll(PDO::FETCH_ASSOC);
              foreach ($roles as $rol) {
                echo "<option value='{$rol['id_rol']}'>{$rol['tip_rol']}</option>";
              }
              ?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="actualizarUsuario">Actualizar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Eliminar Usuario -->
<div class="modal fade" id="eliminarUsuarioModal" tabindex="-1" aria-labelledby="eliminarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarUsuarioModalLabel">Eliminar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de eliminar el usuario con documento <span id="documentoEliminar"></span>? Esta acción no se puede deshacer.</p>
        <input type="hidden" id="documentoEliminarInput" name="documento">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmarEliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>