document.addEventListener('DOMContentLoaded', () => {
    const editarVehiculoModal = new bootstrap.Modal(document.getElementById('editarVehiculoModal'));
    const eliminarVehiculoModal = new bootstrap.Modal(document.getElementById('eliminarVehiculoModal'));

    // Delegar evento para el botón de editar
    document.addEventListener('click', function (e) {
        const btnEdit = e.target.closest('.action-icon.edit');
        if (btnEdit) {
            const placa = btnEdit.getAttribute('data-id');
            fetchVehicleData(placa);
            editarVehiculoModal.show();
        }
    });

    // Delegar evento para el botón de eliminar
    document.addEventListener('click', function (e) {
        const btnDelete = e.target.closest('.action-icon.delete');
        if (btnDelete) {
            const placa = btnDelete.getAttribute('data-id');
            document.getElementById('deletePlaca').textContent = placa;
            document.getElementById('confirmarEliminar').setAttribute('data-id', placa);
            eliminarVehiculoModal.show();
        }
    });

    function fetchVehicleData(placa) {
        fetch(`modals_vehiculos/get_vehicle.php?placa=${encodeURIComponent(placa)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert('Error al cargar los datos del vehículo: ' + data.error);
                    return;
                }
                document.getElementById('editPlaca').value = data.placa;
                document.getElementById('editDocumento').value = data.Documento;
                document.getElementById('editMarca').value = data.id_marca;
                document.getElementById('editModelo').value = data.modelo;
                document.getElementById('editKilometraje').value = data.kilometraje_actual;
                document.getElementById('editEstado').value = data.id_estado;

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
                console.error('Error:', error);
                alert('Error al cargar los datos del vehículo: ' + error.message);
            });
    }

    document.getElementById('editFoto').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('editFotoPreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            preview.src = '';
        }
    });

    document.getElementById('editarVehiculoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
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
                    location.reload();
                } else {
                    alert('Error al actualizar el vehículo: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el vehículo: ' + error.message);
            });
    });

    document.getElementById('confirmarEliminar').addEventListener('click', function () {
        const placa = this.getAttribute('data-id');
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
                    location.reload();
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
