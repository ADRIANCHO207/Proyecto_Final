// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar instancias de modales de Bootstrap
    const editarVehiculoModal = new bootstrap.Modal(document.getElementById('editarVehiculoModal'));
    const eliminarVehiculoModal = new bootstrap.Modal(document.getElementById('eliminarVehiculoModal'));

    // Delegación de eventos para el botón de editar vehículo
    // Permite manejar elementos dinámicos agregados después de la carga inicial
    document.addEventListener('click', function (e) {
        const btnEdit = e.target.closest('.action-icon.edit');
        if (btnEdit) {
            // Obtener la placa del vehículo desde el atributo data-id
            const placa = btnEdit.getAttribute('data-id');
            // Cargar datos del vehículo y mostrar modal
            fetchVehicleData(placa);
            editarVehiculoModal.show();
        }
    });

    // Delegación de eventos para el botón de eliminar vehículo
    document.addEventListener('click', function (e) {
        const btnDelete = e.target.closest('.action-icon.delete');
        if (btnDelete) {
            // Obtener la placa del vehículo desde el atributo data-id
            const placa = btnDelete.getAttribute('data-id');
            // Configurar modal de eliminación con la placa
            document.getElementById('deletePlaca').textContent = placa;
            document.getElementById('confirmarEliminar').setAttribute('data-id', placa);
            eliminarVehiculoModal.show();
        }
    });

    /**
     * Función para obtener datos del vehículo desde el servidor
     * @param {string} placa - Placa del vehículo a consultar
     */
    function fetchVehicleData(placa) {
        // Realizar petición AJAX para obtener datos del vehículo
        fetch(`modals_vehiculos/get_vehicle.php?placa=${encodeURIComponent(placa)}`)
            .then(response => {
                // Verificar si la respuesta HTTP es exitosa
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Verificar si hay errores en la respuesta del servidor
                if (data.error) {
                    alert('Error al cargar los datos del vehículo: ' + data.error);
                    return;
                }
                
                // Poblar campos del formulario con los datos obtenidos
                document.getElementById('editPlaca').value = data.placa;
                document.getElementById('editDocumento').value = data.Documento;
                document.getElementById('editMarca').value = data.id_marca;
                document.getElementById('editModelo').value = data.modelo;
                document.getElementById('editKilometraje').value = data.kilometraje_actual;
                document.getElementById('editEstado').value = data.id_estado;

                // Manejar vista previa de la imagen del vehículo
                const fotoPreview = document.getElementById('editFotoPreview');
                if (data.foto_vehiculo) {
                    fotoPreview.src = data.foto_vehiculo;
                    fotoPreview.style.display = 'block';
                } else {
                    fotoPreview.style.display = 'none';
                    fotoPreview.src = '';
                }
            })
            .catch(error => {
                // Manejo de errores de red o procesamiento
                console.error('Error:', error);
                alert('Error al cargar los datos del vehículo: ' + error.message);
            });
    }

    // Event listener para vista previa de imagen al seleccionar archivo
    document.getElementById('editFoto').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('editFotoPreview');
        
        if (file) {
            // Usar FileReader para mostrar vista previa de la imagen
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            // Ocultar vista previa si no hay archivo seleccionado
            preview.style.display = 'none';
            preview.src = '';
        }
    });

    // Event listener para el envío del formulario de edición
    document.getElementById('editarVehiculoForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevenir envío tradicional del formulario
        
        // Crear FormData para envío de archivos y datos
        const formData = new FormData(this);
        
        // Enviar datos al servidor mediante AJAX
        fetch('modals_vehiculos/update_vehicle.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Vehículo actualizado correctamente.');
                    location.reload(); // Recargar página para mostrar cambios
                } else {
                    alert('Error al actualizar el vehículo: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el vehículo: ' + error.message);
            });
    });

    // Event listener para confirmar eliminación de vehículo
    document.getElementById('confirmarEliminar').addEventListener('click', function () {
        const placa = this.getAttribute('data-id');
        
        // Enviar petición de eliminación al servidor
        fetch('modals_vehiculos/delete_vehicle.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `placa=${encodeURIComponent(placa)}`
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Vehículo eliminado correctamente.');
                    location.reload(); // Recargar página para mostrar cambios
                } else {
                    alert('Error al eliminar el vehículo: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el vehículo: ' + error.message);
            });
    });
});