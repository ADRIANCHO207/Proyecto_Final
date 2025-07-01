document.addEventListener('DOMContentLoaded', () => {
    // Initialize Bootstrap modals
    const editarVehiculoModal = new bootstrap.Modal(document.getElementById('editarVehiculoModal'));
    const eliminarVehiculoModal = new bootstrap.Modal(document.getElementById('eliminarVehiculoModal'));

    // Handle Edit button clicks
    document.querySelectorAll('.action-icon.edit').forEach(button => {
        button.addEventListener('click', function () {
            const placa = this.getAttribute('data-id');
            fetchVehicleData(placa);
            editarVehiculoModal.show();
        });
    });

    // Handle Delete button clicks
    document.querySelectorAll('.action-icon.delete').forEach(button => {
        button.addEventListener('click', function () {
            const placa = this.getAttribute('data-id');
            document.getElementById('deletePlaca').textContent = placa;
            document.getElementById('confirmarEliminar').setAttribute('data-id', placa);
            eliminarVehiculoModal.show();
        });
    });

    // Fetch vehicle data for editing
    function fetchVehicleData(placa) {
        fetch(`get_vehicle.php?placa=${encodeURIComponent(placa)}`)
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
                    fotoPreview.src = `modals_vehiculos/images/${data.foto_vehiculo}`;
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

    // Image preview for edit modal
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

    // Handle Edit form submission
    document.getElementById('editarVehiculoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('update_vehicle.php', {
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

    // Handle Delete confirmation
    document.getElementById('confirmarEliminar').addEventListener('click', function () {
        const placa = this.getAttribute('data-id');
        fetch('delete_vehicle.php', {
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
<?php
session_start();
header('Content-Type: application/json');

// Validar sesión
if (!isset($_SESSION['documento'])) {
    echo json_encode(['success' => false, 'error' => 'Sesión expirada']);
    exit;
}

// Validar método y parámetro
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['placa'])) {
    echo json_encode(['success' => false, 'error' => 'Solicitud inválida']);
    exit;
}

require_once('../../conecct/conex.php');
$db = new Database();
$con = $db->conectar();

$placa = $_POST['placa'];

// Eliminar el vehículo (ajusta la consulta según tu modelo de datos)
$stmt = $con->prepare("DELETE FROM vehiculos WHERE placa = :placa");
$stmt->bindParam(':placa', $placa);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el vehículo']);
}
exit;
?>