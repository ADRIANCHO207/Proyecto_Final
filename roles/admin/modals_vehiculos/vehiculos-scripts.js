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
                console.error('Error:', data.error);
                alert('Error al cargar los datos del vehículo');
                return;
            }
            // Procesar datos normalmente
            // ... resto del código para cargar los datos en el modal ...
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del vehículo');
        });
}

// Función para cargar datos en el modal de edición (versión original)
function cargarDatosEdicion(fila) {
    const placa = fila.querySelector(".placa-cell").textContent;
    
    fetch("obtener_vehiculo.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `placa=${encodeURIComponent(placa)}`,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            const vehiculo = data.vehiculo;
            // Cargar datos en el formulario...
            // ... resto del código original ...
        } else {
            alert("Error al cargar los datos del vehículo: " + data.message);
        }
    })
    .catch((error) => {
        console.error("Error:", error);
        alert("Error al cargar los datos del vehículo");
    });
}

// Función para eliminar vehículo (versión original)
function eliminarVehiculo(idVehiculo) {
    const formData = new FormData();
    formData.append("accion", "eliminar");
    formData.append("id_vehiculo", idVehiculo);

    fetch("procesar_vehiculo.php", {
        method: "POST",
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert("Error al eliminar el vehículo: " + data.message);
        }
    })
    .catch((error) => {
        console.error("Error:", error);
        alert("Error al eliminar el vehículo");
    });
}