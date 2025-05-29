const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');
const selects = document.querySelectorAll('#formulario select');
const textarea = document.querySelector('#observaciones');

// Expresiones regulares para validaciones
const expresiones = {
    placa: /^[A-Z0-9]{3,8}$/, // Letras y números, entre 3 y 8 caracteres
    kilometraje: /^\d{1,7}$/, // Solo números, máximo 7 dígitos
    observaciones: /^[a-zA-Z0-9\s.,!?'-]{1,500}$/, // Letras, números y puntuación básica, máximo 500 caracteres
};

// Objeto para rastrear el estado de los campos (inicializamos todos como no válidos)
const campos = {
    placa: false,
    id_tipo_mantenimiento: false,
    fecha_programada: false,
    fecha_realizada: false, // Opcional, pero inicia como no válido
    kilometraje_actual: false, // Opcional, pero inicia como no válido
    proximo_cambio_km: false, // Opcional, pero inicia como no válido
    proximo_cambio_fecha: false, // Opcional, pero inicia como no válido
    observaciones: false, // Opcional, pero inicia como no válido
    trabajos: false
};

// Función para validar el formulario
const validarFormulario = (e) => {
    switch (e.target.name) {
        case "placa":
            validarSelect(e.target, 'placa', 'Seleccione un vehículo.');
            break;
        case "id_tipo_mantenimiento":
            validarSelect(e.target, 'id_tipo_mantenimiento', 'Seleccione un tipo de mantenimiento.');
            break;
        case "fecha_programada":
            validarFecha(e.target, 'fecha_programada', 'Seleccione una fecha válida.', false);
            break;
        case "fecha_realizada":
            validarFecha(e.target, 'fecha_realizada', 'Fecha no puede ser futura.', true);
            break;
        case "kilometraje_actual":
            validarCampo(expresiones.kilometraje, e.target, 'kilometraje_actual', 'Ingrese un número positivo.', true);
            break;
        case "proximo_cambio_km":
            validarCampo(expresiones.kilometraje, e.target, 'proximo_cambio_km', 'Ingrese un número positivo.', true);
            break;
        case "proximo_cambio_fecha":
            validarFechaFutura(e.target, 'proximo_cambio_fecha', 'Fecha no puede ser pasada.', true);
            break;
        case "observaciones":
            validarCampo(expresiones.observaciones, e.target, 'observaciones', 'Máximo 500 caracteres, solo letras, números y puntuación básica.', true);
            break;
    }
};

// Validar campos de texto o número
const validarCampo = (expresion, input, campo, mensaje, opcional = false) => {
    const grupo = document.getElementById(`grupo_${campo}`);
    const validacion = document.getElementById(`validacion_${campo}`);

    if (input.value.trim() === '') {
        if (opcional) {
            // Si es opcional y está vacío, se considera válido para el envío, pero visualmente se muestra como incorrecto
            grupo.classList.remove(`input_field_${campo}_correcto`);
            grupo.classList.add(`input_field_${campo}_incorrecto`);
            validacion.textContent = mensaje;
            validacion.style.opacity = '1';
            campos[campo] = true; // Válido para el envío, pero visualmente incorrecto
        } else {
            // Si no es opcional y está vacío, es inválido
            grupo.classList.remove(`input_field_${campo}_correcto`);
            grupo.classList.add(`input_field_${campo}_incorrecto`);
            validacion.textContent = mensaje;
            validacion.style.opacity = '1';
            campos[campo] = false;
        }
    } else if (expresion.test(input.value.trim())) {
        grupo.classList.remove(`input_field_${campo}_incorrecto`);
        grupo.classList.add(`input_field_${campo}_correcto`);
        validacion.style.opacity = '0';
        campos[campo] = true;
    } else {
        grupo.classList.remove(`input_field_${campo}_correcto`);
        grupo.classList.add(`input_field_${campo}_incorrecto`);
        validacion.textContent = mensaje;
        validacion.style.opacity = '1';
        campos[campo] = false;
    }
};

// Validar selects
const validarSelect = (select, campo, mensaje) => {
    const grupo = document.getElementById(`grupo_${campo}`);
    const validacion = document.getElementById(`validacion_${campo}`);

    if (select.value === '') {
        grupo.classList.remove(`input_field_${campo}_correcto`);
        grupo.classList.add(`input_field_${campo}_incorrecto`);
        validacion.textContent = mensaje;
        validacion.style.opacity = '1';
        campos[campo] = false;
    } else {
        grupo.classList.remove(`input_field_${campo}_incorrecto`);
        grupo.classList.add(`input_field_${campo}_correcto`);
        validacion.style.opacity = '0';
        campos[campo] = true;
    }
};

// Validar fechas (no futuras para fecha_realizada)
const validarFecha = (input, campo, mensaje, opcional = false) => {
    const grupo = document.getElementById(`grupo_${campo}`);
    const validacion = document.getElementById(`validacion_${campo}`);
    const fecha = new Date(input.value);
    const hoy = new Date();

    if (input.value.trim() === '') {
        if (opcional) {
            grupo.classList.remove(`input_field_${campo}_correcto`);
            grupo.classList.add(`input_field_${campo}_incorrecto`);
            validacion.textContent = mensaje;
            validacion.style.opacity = '1';
            campos[campo] = true; // Válido para el envío, pero visualmente incorrecto
        } else {
            grupo.classList.remove(`input_field_${campo}_correcto`);
            grupo.classList.add(`input_field_${campo}_incorrecto`);
            validacion.textContent = mensaje;
            validacion.style.opacity = '1';
            campos[campo] = false;
        }
    } else if (campo === 'fecha_realizada' && fecha > hoy) {
        grupo.classList.remove(`input_field_${campo}_correcto`);
        grupo.classList.add(`input_field_${campo}_incorrecto`);
        validacion.textContent = mensaje;
        validacion.style.opacity = '1';
        campos[campo] = false;
    } else {
        grupo.classList.remove(`input_field_${campo}_incorrecto`);
        grupo.classList.add(`input_field_${campo}_correcto`);
        validacion.style.opacity = '0';
        campos[campo] = true;
    }
};

// Validar fechas futuras (para proximo_cambio_fecha)
const validarFechaFutura = (input, campo, mensaje, opcional = false) => {
    const grupo = document.getElementById(`grupo_${campo}`);
    const validacion = document.getElementById(`validacion_${campo}`);
    const fecha = new Date(input.value);
    const hoy = new Date();

    if (input.value.trim() === '') {
        if (opcional) {
            grupo.classList.remove(`input_field_${campo}_correcto`);
            grupo.classList.add(`input_field_${campo}_incorrecto`);
            validacion.textContent = mensaje;
            validacion.style.opacity = '1';
            campos[campo] = true; // Válido para el envío, pero visualmente incorrecto
        } else {
            grupo.classList.remove(`input_field_${campo}_correcto`);
            grupo.classList.add(`input_field_${campo}_incorrecto`);
            validacion.textContent = mensaje;
            validacion.style.opacity = '1';
            campos[campo] = false;
        }
    } else if (fecha < hoy) {
        grupo.classList.remove(`input_field_${campo}_correcto`);
        grupo.classList.add(`input_field_${campo}_incorrecto`);
        validacion.textContent = mensaje;
        validacion.style.opacity = '1';
        campos[campo] = false;
    } else {
        grupo.classList.remove(`input_field_${campo}_incorrecto`);
        grupo.classList.add(`input_field_${campo}_correcto`);
        validacion.style.opacity = '0';
        campos[campo] = true;
    }
};

// Validar trabajos dinámicos
const validarTrabajos = () => {
    const trabajosContainer = document.getElementById('trabajos-container');
    const trabajos = trabajosContainer.querySelectorAll('.trabajo-item');
    const grupo = document.getElementById('grupo_trabajos');
    const validacion = document.getElementById('validacion_trabajos');

    if (trabajos.length === 0 || Array.from(trabajos).some(trabajo => trabajo.querySelector('select').value === '')) {
        grupo.classList.remove('input_field_trabajos_correcto');
        grupo.classList.add('input_field_trabajos_incorrecto');
        validacion.textContent = 'Debe seleccionar al menos un trabajo.';
        validacion.style.opacity = '1';
        campos.trabajos = false;
    } else {
        grupo.classList.remove('input_field_trabajos_incorrecto');
        grupo.classList.add('input_field_trabajos_correcto');
        validacion.style.opacity = '0';
        campos.trabajos = true;
    }
};

// Añadir eventos a los inputs
inputs.forEach((input) => {
    input.addEventListener('blur', validarFormulario);
    input.addEventListener('input', validarFormulario);
});

selects.forEach((select) => {
    select.addEventListener('blur', validarFormulario);
    select.addEventListener('change', validarFormulario);
});

textarea.addEventListener('blur', validarFormulario);
textarea.addEventListener('input', validarFormulario);

// Validar al enviar el formulario
formulario.addEventListener('submit', (e) => {
    e.preventDefault();
    validarTrabajos();

    const formularioError = document.getElementById('formulario_error');
    const formularioExito = document.getElementById('formulario_exito');

    // Validar todos los campos al enviar
    validarSelect(document.getElementById('placa'), 'placa', 'Seleccione un vehículo.');
    validarSelect(document.getElementById('id_tipo_mantenimiento'), 'id_tipo_mantenimiento', 'Seleccione un tipo de mantenimiento.');
    validarFecha(document.getElementById('fecha_programada'), 'fecha_programada', 'Seleccione una fecha válida.', false);
    validarFecha(document.getElementById('fecha_realizada'), 'fecha_realizada', 'Fecha no puede ser futura.', true);
    validarCampo(expresiones.kilometraje, document.getElementById('kilometraje_actual'), 'kilometraje_actual', 'Ingrese un número positivo.', true);
    validarCampo(expresiones.kilometraje, document.getElementById('proximo_cambio_km'), 'proximo_cambio_km', 'Ingrese un número positivo.', true);
    validarFechaFutura(document.getElementById('proximo_cambio_fecha'), 'proximo_cambio_fecha', 'Fecha no puede ser pasada.', true);
    validarCampo(expresiones.observaciones, document.getElementById('observaciones'), 'observaciones', 'Máximo 500 caracteres, solo letras, números y puntuación básica.', true);

    // Solo los campos obligatorios (placa, id_tipo_mantenimiento, fecha_programada, trabajos) deben ser true para enviar
    if (campos.placa && campos.id_tipo_mantenimiento && campos.fecha_programada && campos.trabajos) {
        formularioError.style.opacity = '0';
        formularioExito.style.opacity = '1';
        formulario.submit();
    } else {
        formularioError.textContent = 'Por favor, corrige los errores en el formulario.';
        formularioError.style.opacity = '1';
        formularioExito.style.opacity = '0';
    }
});

// Funciones para trabajos dinámicos
let trabajoCount = 0;

function agregarTrabajo() {
    const trabajosContainer = document.getElementById('trabajos-container');
    const grupoTrabajos = document.getElementById('grupo_trabajos');
    const trabajosData = JSON.parse(grupoTrabajos.getAttribute('data-trabajos'));

    const div = document.createElement('div');
    div.classList.add('trabajo-item');
    div.id = `trabajo-${trabajoCount}`;
    div.innerHTML = `
        <select name="trabajos[]" required>
            <option value="">Seleccionar Trabajo</option>
            ${trabajosData.map(trabajo => `<option value="${trabajo.id}">${trabajo.Trabajo} - $${trabajo.Precio}</option>`).join('')}
        </select>
        <input type="number" name="cantidades[]" value="1" min="1" placeholder="Cantidad">
        <button type="button" onclick="eliminarTrabajo(${trabajoCount})">Eliminar</button>
    `;

    trabajosContainer.appendChild(div);
    trabajoCount++;

    const select = div.querySelector('select');
    select.addEventListener('change', validarTrabajos);
    const inputCantidad = div.querySelector('input');
    inputCantidad.addEventListener('input', validarTrabajos);

    validarTrabajos();
}

function eliminarTrabajo(id) {
    const trabajo = document.getElementById(`trabajo-${id}`);
    if (trabajo) {
        trabajo.remove();
        validarTrabajos();
    }
}

// Validar trabajos al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    validarTrabajos();

    // Validar todos los campos al cargar para que se muestren en rojo si están vacíos
    validarSelect(document.getElementById('placa'), 'placa', 'Seleccione un vehículo.');
    validarSelect(document.getElementById('id_tipo_mantenimiento'), 'id_tipo_mantenimiento', 'Seleccione un tipo de mantenimiento.');
    validarFecha(document.getElementById('fecha_programada'), 'fecha_programada', 'Seleccione una fecha válida.', false);
    validarFecha(document.getElementById('fecha_realizada'), 'fecha_realizada', 'Fecha no puede ser futura.', true);
    validarCampo(expresiones.kilometraje, document.getElementById('kilometraje_actual'), 'kilometraje_actual', 'Ingrese un número positivo.', true);
    validarCampo(expresiones.kilometraje, document.getElementById('proximo_cambio_km'), 'proximo_cambio_km', 'Ingrese un número positivo.', true);
    validarFechaFutura(document.getElementById('proximo_cambio_fecha'), 'proximo_cambio_fecha', 'Fecha no puede ser pasada.', true);
    validarCampo(expresiones.observaciones, document.getElementById('observaciones'), 'observaciones', 'Máximo 500 caracteres, solo letras, números y puntuación básica.', true);
});