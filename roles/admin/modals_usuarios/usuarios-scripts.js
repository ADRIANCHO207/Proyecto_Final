document.addEventListener('DOMContentLoaded', function () {
  // Abrir modal de agregar usuario
  document.getElementById('btnAgregarUsuario').addEventListener('click', function (e) {
    e.preventDefault();
    // Resetear el formulario
    document.getElementById('agregarUsuarioForm').reset();
    new bootstrap.Modal(document.getElementById('agregarUsuarioModal')).show();
  });

  // Abrir modal de editar usuario
  document.querySelectorAll('.edit-user').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      const documento = this.getAttribute('data-id');
      document.getElementById('documentoEditar').value = documento;
      document.getElementById('documentoEliminar').textContent = documento;
      document.getElementById('documentoEliminarInput').value = documento;

      // Cargar datos del usuario
      fetch(`modals_usuarios/get_usuario_data.php?documento=${encodeURIComponent(documento)}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const usuario = data.data;
            document.getElementById('nombreCompletoEditar').value = usuario.nombre_completo || '';
            document.getElementById('emailEditar').value = usuario.email || '';
            document.getElementById('telefonoEditar').value = usuario.telefono || '';
            document.getElementById('estadoEditar').value = usuario.id_estado_usuario || '';
            document.getElementById('rolEditar').value = usuario.id_rol || '';
            new bootstrap.Modal(document.getElementById('editarUsuarioModal')).show();
          } else {
            alert('Error al cargar los datos del usuario');
          }
        })
        .catch(error => console.error('Error:', error));
    });
  });

  // Actualizar usuario
  document.getElementById('actualizarUsuario').addEventListener('click', function (e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('editarUsuarioForm'));
    fetch('actualizar_usuario.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(result => {
      alert(result);
      new bootstrap.Modal(document.getElementById('editarUsuarioModal')).hide();
      location.reload();
    })
    .catch(error => console.error('Error:', error));
  });

  // Abrir modal de eliminar usuario
  document.querySelectorAll('.delete-user').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      const documento = this.getAttribute('data-id');
      document.getElementById('documentoEliminar').textContent = documento;
      document.getElementById('documentoEliminarInput').value = documento;
      new bootstrap.Modal(document.getElementById('eliminarUsuarioModal')).show();
    });
  });

  // Confirmar eliminación
  document.getElementById('confirmarEliminar').addEventListener('click', function (e) {
    e.preventDefault();
    const documento = document.getElementById('documentoEliminarInput').value;
    fetch('eliminar_usuario.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `documento=${encodeURIComponent(documento)}`
    })
    .then(response => response.text())
    .then(result => {
      alert(result);
      new bootstrap.Modal(document.getElementById('eliminarUsuarioModal')).hide();
      location.reload();
    })
    .catch(error => console.error('Error:', error));
  });

  // Guardar nuevo usuario
  document.getElementById('guardarUsuario').addEventListener('click', function (e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('agregarUsuarioForm'));
    fetch('agregar_usuario.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(result => {
      alert(result);
      new bootstrap.Modal(document.getElementById('agregarUsuarioModal')).hide();
      location.reload();
    })
    .catch(error => console.error('Error:', error));
  });
});