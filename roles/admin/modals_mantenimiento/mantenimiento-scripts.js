// Variables globales
let mantenimientoIdParaEliminar = null;

// Función para abrir modal de agregar mantenimiento
function abrirModalAgregarMantenimiento() {
    // Limpiar formulario
    document.getElementById('formAgregarMantenimiento').reset();
    
    // Establecer fecha mínima como hoy
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fechaProgramadaAgregar').min = hoy;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalAgregarMantenimiento'));
    modal.show();
}

// Función para editar mantenimiento
function editarMantenimiento(id) {
    fetch(`modals_mantenimiento/get_mantenimiento.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const mant = data.mantenimiento;
                
                // Llenar formulario de edición
                document.getElementById('idMantenimientoEditar').value = mant.id_mantenimiento;
                document.getElementById('placaEditar').value = mant.placa;
                document.getElementById('tipoMantenimientoEditar').value = mant.id_tipo_mantenimiento;
                document.getElementById('fechaProgramadaEditar').value = mant.fecha_programada;
                document.getElementById('fechaRealizadaEditar').value = mant.fecha_realizada || '';
                document.getElementById('kilometrajeActualEditar').value = mant.kilometraje_actual || '';
                document.getElementById('proximoCambioKmEditar').value = mant.proximo_cambio_km || '';
                document.getElementById('proximoCambioFechaEditar').value = mant.proximo_cambio_fecha || '';
                document.getElementById('observacionesEditar').value = mant.observaciones || '';
                
                // Mostrar modal
                const modal = new bootstrap.Modal(document.getElementById('modalEditarMantenimiento'));
                modal.show();
            } else {
                mostrarAlerta('Error al cargar los datos del mantenimiento', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión', 'danger');
        });
}

// Función para ver detalles del mantenimiento
function verMantenimiento(id) {
    fetch(`modals_mantenimiento/get_mantenimiento.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const mant = data.mantenimiento;
                
                // Llenar modal de detalles
                document.getElementById('verIdMantenimiento').textContent = mant.id_mantenimiento;
                document.getElementById('verPlaca').textContent = mant.placa;
                document.getElementById('verTipoMantenimiento').textContent = mant.tipo_descripcion;
                document.getElementById('verEstado').textContent = mant.fecha_realizada ? 'Completado' : 'Pendiente';
                document.getElementById('verFechaProgramada').textContent = formatearFecha(mant.fecha_programada);
                document.getElementById('verFechaRealizada').textContent = mant.fecha_realizada ? formatearFecha(mant.fecha_realizada) : 'No realizado';
                document.getElementById('verKilometrajeActual').textContent = mant.kilometraje_actual ? formatearNumero(mant.kilometraje_actual) + ' km' : 'No especificado';
                document.getElementById('verProximoCambioKm').textContent = mant.proximo_cambio_km ? formatearNumero(mant.proximo_cambio_km) + ' km' : 'No especificado';
                document.getElementById('verProximoCambioFecha').textContent = mant.proximo_cambio_fecha ? formatearFecha(mant.proximo_cambio_fecha) : 'No especificado';
                document.getElementById('verObservaciones').textContent = mant.observaciones || 'Sin observaciones';
                
                // Mostrar modal
                const modal = new bootstrap.Modal(document.getElementById('modalVerMantenimiento'));
                modal.show();
            } else {
                mostrarAlerta('Error al cargar los datos del mantenimiento', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión', 'danger');
        });
}

// Función para confirmar eliminación
function eliminarMantenimiento(id, placa) {
    mantenimientoIdParaEliminar = id;
    
    // Obtener datos del mantenimiento para mostrar más información
    fetch(`modals_mantenimiento/get_mantenimiento.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const mant = data.mantenimiento;
                
                // Mostrar información del mantenimiento a eliminar
                document.getElementById('placaEliminar').textContent = mant.placa;
                document.getElementById('tipoEliminar').textContent = mant.tipo_descripcion;
                document.getElementById('fechaEliminar').textContent = formatearFecha(mant.fecha_programada);
                document.getElementById('idMantenimientoEliminar').value = id;
                
                // Mostrar modal de confirmación
                const modal = new bootstrap.Modal(document.getElementById('modalEliminarMantenimiento'));
                modal.show();
            } else {
                mostrarAlerta('Error al cargar los datos del mantenimiento', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión', 'danger');
        });
}

// Event listeners para formularios
document.addEventListener('DOMContentLoaded', function() {
    // Formulario agregar mantenimiento
    document.getElementById('formAgregarMantenimiento').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('accion', 'agregar');
        
        fetch('modals_mantenimiento/procesar_mantenimiento.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('Mantenimiento agregado exitosamente', 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalAgregarMantenimiento')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                mostrarAlerta(data.message || 'Error al agregar el mantenimiento', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión', 'danger');
        });
    });
    
    // Formulario editar mantenimiento
    document.getElementById('formEditarMantenimiento').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('accion', 'editar');
        
        fetch('modals_mantenimiento/procesar_mantenimiento.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('Mantenimiento actualizado exitosamente', 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalEditarMantenimiento')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                mostrarAlerta(data.message || 'Error al actualizar el mantenimiento', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión', 'danger');
        });
    });
    
    // Confirmar eliminación
    document.getElementById('confirmarEliminar').addEventListener('click', function() {
        if (mantenimientoIdParaEliminar) {
            const formData = new FormData();
            formData.append('accion', 'eliminar');
            formData.append('id_mantenimiento', mantenimientoIdParaEliminar);
            
            fetch('modals_mantenimiento/procesar_mantenimiento.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Mantenimiento eliminado exitosamente', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalEliminarMantenimiento')).hide();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarAlerta(data.message || 'Error al eliminar el mantenimiento', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error de conexión', 'danger');
            });
        }
    });
});

// Funciones auxiliares
function formatearFecha(fecha) {
    if (!fecha) return '';
    const date = new Date(fecha + 'T00:00:00');
    return date.toLocaleDateString('es-ES');
}

function formatearNumero(numero) {
    return new Intl.NumberFormat('es-ES').format(numero);
}

function mostrarAlerta(mensaje, tipo) {
    // Crear elemento de alerta
    const alerta = document.createElement('div');
    alerta.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
    alerta.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alerta.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Agregar al body
    document.body.appendChild(alerta);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alerta.parentNode) {
            alerta.remove();
        }
    }, 5000);
}