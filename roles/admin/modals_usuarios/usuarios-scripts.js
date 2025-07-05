document.addEventListener('DOMContentLoaded', function () {
  // Función para mostrar mensajes
  function showMessage(message, type = 'info') {
    // Crear un toast o alert personalizado
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.insertBefore(alertDiv, document.body.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
      if (alertDiv.parentNode) {
        alertDiv.remove();
      }
    }, 5000);
  }

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
      
      if (!documento) {
        showMessage('Error: No se pudo obtener el documento del usuario', 'danger');
        return;
      }

      // Cargar datos del usuario
      fetch(`modals_usuarios/get_usuario_data.php?documento=${encodeURIComponent(documento)}`)
        .then(response => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            const usuario = data.data;
            document.getElementById('documentoEditar').value = documento;
            document.getElementById('nombreCompletoEditar').value = usuario.nombre_completo || '';
            document.getElementById('emailEditar').value = usuario.email || '';
            document.getElementById('telefonoEditar').value = usuario.telefono || '';
            document.getElementById('estadoEditar').value = usuario.id_estado_usuario || '';
            document.getElementById('rolEditar').value = usuario.id_rol || '';
            new bootstrap.Modal(document.getElementById('editarUsuarioModal')).show();
          } else {
            showMessage('Error al cargar los datos del usuario: ' + (data.message || 'Error desconocido'), 'danger');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showMessage('Error de conexión al cargar los datos del usuario', 'danger');
        });
    });
  });

  // Actualizar usuario
  document.getElementById('actualizarUsuario').addEventListener('click', function (e) {
    e.preventDefault();
    
    // Validar formulario
    const form = document.getElementById('editarUsuarioForm');
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    const formData = new FormData(form);
    
    fetch('modals_usuarios/actualizar_usuario.php', {
      method: 'POST',
      body: formData
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.text();
    })
    .then(result => {
      if (result.includes('exitosamente')) {
        showMessage(result, 'success');
        bootstrap.Modal.getInstance(document.getElementById('editarUsuarioModal')).hide();
        setTimeout(() => location.reload(), 1500);
      } else {
        showMessage(result, 'danger');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showMessage('Error de conexión al actualizar el usuario', 'danger');
    });
  });

  // Abrir modal de eliminar usuario
  document.querySelectorAll('.delete-user').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      const documento = this.getAttribute('data-id');
      
      if (!documento) {
        showMessage('Error: No se pudo obtener el documento del usuario', 'danger');
        return;
      }
      
      document.getElementById('documentoEliminar').textContent = documento;
      document.getElementById('documentoEliminarInput').value = documento;
      new bootstrap.Modal(document.getElementById('eliminarUsuarioModal')).show();
    });
  });

  // Confirmar eliminación
  document.getElementById('confirmarEliminar').addEventListener('click', function (e) {
    e.preventDefault();
    const documento = document.getElementById('documentoEliminarInput').value;
    
    if (!documento) {
      showMessage('Error: No se pudo obtener el documento del usuario', 'danger');
      return;
    }

    fetch('modals_usuarios/eliminar_usuario.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `documento=${encodeURIComponent(documento)}`
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        showMessage(data.message, 'success');
        bootstrap.Modal.getInstance(document.getElementById('eliminarUsuarioModal')).hide();
        setTimeout(() => location.reload(), 1500);
      } else {
        showMessage(data.error || 'Error al eliminar el usuario', 'danger');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showMessage('Error de conexión al eliminar el usuario', 'danger');
    });
  });

  // Guardar nuevo usuario
  document.getElementById('guardarUsuario').addEventListener('click', function (e) {
    e.preventDefault();
    
    // Validar formulario
    const form = document.getElementById('agregarUsuarioForm');
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    const formData = new FormData(form);
    
    fetch('modals_usuarios/agregar_usuario.php', {
      method: 'POST',
      body: formData
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.text();
    })
    .then(result => {
      if (result.includes('exitosamente')) {
        showMessage(result, 'success');
        bootstrap.Modal.getInstance(document.getElementById('agregarUsuarioModal')).hide();
        setTimeout(() => location.reload(), 1500);
      } else {
        showMessage(result, 'danger');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showMessage('Error de conexión al agregar el usuario', 'danger');
    });
  });
});