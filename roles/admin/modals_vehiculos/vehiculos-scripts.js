document.addEventListener('DOMContentLoaded', function () {
    console.log('vehiculos-scripts.js loaded');

    // Verificar si los botones existen
    const editButtons = document.querySelectorAll('.action-icon.edit');
    const deleteButtons = document.querySelectorAll('.action-icon.delete');
    console.log('Edit buttons found:', editButtons.length);
    console.log('Delete buttons found:', deleteButtons.length);

    // Edit button click handler
    editButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('Botón editar clickeado', this.getAttribute('data-id'));
            const placa = this.getAttribute('data-id');
            console.log('Edit clicked for placa:', placa, 'Button:', this);

            fetch('modals_vehiculos/get_vehicle.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'placa=' + encodeURIComponent(placa)
            })
            .then(response => {
                console.log('Fetch status:', response.status, 'URL:', response.url);
                if (!response.ok) throw new Error('Network response was not ok ' + response.status);
                return response.text();
            })
            .then(data => {
                console.log('Fetch response:', data);
                if (data.startsWith('success:')) {
                    const parts = data.replace('success: ', '').split('|');
                    const fields = ['editPlaca', 'editDocumento', 'editMarca', 'editModelo', 'editEstado', 'editKilometraje'];
                    if (parts.length >= fields.length) {
                        fields.forEach((id, index) => {
                            const element = document.getElementById(id);
                            if (element) {
                                element.value = parts[index] || '';
                                if (id === 'editPlaca' || id === 'editDocumento') {
                                    element.readOnly = true;
                                }
                            } else {
                                console.error(`Element with id ${id} not found`);
                            }
                        });
                        const editModal = document.getElementById('editVehicleModal');
                        if (editModal) {
                            const modal = new bootstrap.Modal(editModal);
                            modal.show();
                        } else {
                            console.error('Edit modal element not found in DOM');
                        }
                    } else {
                        alert('Datos insuficientes recibidos del servidor: ' + data);
                    }
                } else if (data.startsWith('error:')) {
                    alert(data.replace('error: ', ''));
                } else {
                    alert('Respuesta inesperada del servidor: ' + data);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error al conectar con el servidor: ' + error.message);
            });
        });
    });

    // Delete button click handler
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const placa = this.getAttribute('data-id');
            console.log('Delete clicked for placa:', placa, 'Button:', this);
            document.getElementById('deletePlaca').value = placa;
            document.getElementById('deletePlacaDisplay').textContent = placa;

            const deleteModal = document.getElementById('deleteVehicleModal');
            if (deleteModal) {
                const modal = new bootstrap.Modal(deleteModal);
                modal.show();
            } else {
                console.error('Delete modal element not found in DOM');
            }
        });
    });

    // Handle delete form submission
    const deleteForm = document.getElementById('deleteVehicleForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            console.log('Delete form submitted:', Object.fromEntries(formData));

            fetch('modals_vehiculos/delete_vehicle.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Fetch status:', response.status, 'URL:', response.url);
                if (!response.ok) throw new Error('Network response was not ok ' + response.status);
                return response.text();
            })
            .then(data => {
                console.log('Delete response:', data);
                if (data.startsWith('success:')) {
                    showNotification('Vehículo marcado como inactivo exitosamente');
                    location.reload();
                } else if (data.startsWith('error:')) {
                    if (data.includes('Error interno')) {
                        showNotification('No se puede eliminar el vehículo porque está en uso. Contacte al administrador.');
                    } else {
                        alert(data.replace('error: ', ''));
                    }
                } else {
                    alert('Respuesta inesperada del servidor: ' + data);
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('Error al conectar con el servidor: ' + error.message);
            });
        });
    } else {
        console.error('Delete form not found');
    }

    // Handle edit form submission
    const editForm = document.getElementById('editVehicleForm');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const placaInput = document.getElementById('editPlaca');
            if (placaInput) {
                formData.set('placa', placaInput.value);
            }
            console.log('Edit form submitted:', Object.fromEntries(formData));

            fetch('modals_vehiculos/update_vehicle.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Fetch status:', response.status);
                if (!response.ok) throw new Error('Network response was not ok ' + response.status);
                return response.json();
            })
            .then(data => {
                console.log('Update response:', data);
                if (data.success) {
                    showNotification('Vehículo editado exitosamente');
                    location.reload();
                } else {
                    if (data.message && data.message.includes('documento no corresponde')) {
                        showNotification(data.message);
                    } else {
                        alert('Error al actualizar el vehículo: ' + (data.message || 'Error desconocido'));
                    }
                }
            })
            .catch(error => {
                console.error('Update error:', error);
                alert('Error al conectar con el servidor: ' + error.message);
            });
        });
    } else {
        console.error('Edit form not found');
    }

    // Función para mostrar notificaciones
    function showNotification(msg) {
        document.getElementById('notificationMessage').textContent = msg;
        new bootstrap.Modal(document.getElementById('notificationModal')).show();
    }
});