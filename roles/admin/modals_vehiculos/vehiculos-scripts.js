document.addEventListener('DOMContentLoaded', function () {
    console.log('vehiculos-scripts.js loaded');

    // Edit button click handler
    document.querySelectorAll('.action-icon.edit').forEach(button => {
        if (button && typeof button.getAttribute === 'function') {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const placa = button.getAttribute('data-id');
                console.log('Edit clicked for placa:', placa);
                console.log('Fetching from:', window.location.origin + '/proyecto/roles/admin/modals_vehiculos/get_vehicle.php');

                fetch('/proyecto/roles/admin/modals_vehiculos/get_vehicle.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'placa=' + encodeURIComponent(placa)
                })
                .then(response => {
                    console.log('Fetch status:', response.status);
                    console.log('Fetch URL:', response.url);
                    if (!response.ok) throw new Error('Network response was not ok ' + response.status);
                    return response.text(); // Cambiar a text() en lugar de json()
                })
                .then(data => {
                    console.log('Fetch response:', data);
                    if (data.startsWith('success:')) {
                        const parts = data.replace('success: ', '').split('|');
                        if (parts.length >= 6) {
                            document.getElementById('editPlaca').value = parts[0] || '';
                            document.getElementById('editDocumento').value = parts[1] || '';
                            document.getElementById('editMarca').value = parts[2] || '';
                            document.getElementById('editModelo').value = parts[3] || '';
                            document.getElementById('editEstado').value = parts[4] || '';
                            document.getElementById('editKilometraje').value = parts[5] || '';

                            const editModal = new bootstrap.Modal(document.getElementById('editVehicleModal'));
                            editModal.show();
                        } else {
                            alert('Formato de datos inesperado');
                        }
                    } else if (data.startsWith('error:')) {
                        alert(data.replace('error: ', ''));
                    } else {
                        alert('Respuesta inesperada del servidor');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Error al conectar con el servidor: ' + error.message);
                });
            });
        }
    });

    // Delete button click handler
    document.querySelectorAll('.action-icon.delete').forEach(button => {
        if (button && typeof button.getAttribute === 'function') {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const placa = button.getAttribute('data-id');
                console.log('Delete clicked for placa:', placa);
                document.getElementById('deletePlaca').value = placa;

                const deleteModal = new bootstrap.Modal(document.getElementById('editVehicleModal'));
                deleteModal.show();
            });
        }
    });

    // Handle delete form submission
    const deleteForm = document.getElementById('deleteVehicleForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            console.log('Delete form submitted:', Object.fromEntries(formData));

            fetch('/proyecto/roles/admin/modals_vehiculos/delete_vehicle.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Fetch status:', response.status);
                console.log('Fetch URL:', response.url);
                if (!response.ok) throw new Error('Network response was not ok ' + response.status);
                return response.text();
            })
            .then(data => {
                console.log('Delete response:', data);
                if (data.startsWith('success:')) {
                    alert(data.replace('success: ', ''));
                    location.reload();
                } else if (data.startsWith('error:')) {
                    alert(data.replace('error: ', ''));
                } else {
                    alert('Respuesta inesperada del servidor');
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
            console.log('Edit form submitted:', Object.fromEntries(formData));

            fetch('/proyecto/roles/admin/modals_vehiculos/update_vehicle.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok ' + response.status);
                return response.json(); // Esto sigue necesitando JSON por ahora
            })
            .then(data => {
                console.log('Update response:', data);
                if (data.success) {
                    alert('Vehículo actualizado correctamente');
                    location.reload();
                } else {
                    alert('Error al actualizar el vehículo: ' + data.message);
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
});