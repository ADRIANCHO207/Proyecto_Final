const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');
const selects = document.querySelectorAll('#formulario select');
const textarea = document.querySelector('#observaciones');

// Expresiones regulares para validaciones
const expresiones = {
    numero_licencia: /^[A-Z0-9]{5,15}$/, // Letras y números, entre 5 y 15 caracteres
    observaciones: /^[a-zA-Z0-9\s.,!?'-]{0,500}$/ // Letras, números y puntuación básica, máximo 500 caracteres
};

// Objeto para rastrear el estado de los campos (inicialmente no validados)
const campos = {
    numero_licencia: null,
    tipo_licencia: null,
    fecha_vencimiento: null,
    observaciones: null
};

// Función para validar el formulario
const validarFormulario = (e) => {
    switch (e.target.name) {
        case "numero_licencia":
            validarCampo(expresiones.numero_licencia, e.target, 'numero_licencia', 'El número de licencia debe tener entre 5 y 15 caracteres (letras y números).', false);
            break;
        case "tipo_licencia":
            validarSelect(e.target, 'tipo_licencia', 'Seleccione un tipo de licencia.');
            break;
        case "fecha_vencimiento":
            validarFechaVencimiento(e.target, 'fecha_vencimiento', 'La fecha de vencimiento es obligatoria y debe ser futura.', false);
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
            grupo.classList.remove(`input_field_${campo}_incorrecto`);
            grupo.classList.remove(`input_field_${campo}_correcto`);
            validacion.style.opacity = '0';
            campos[campo] = true;
        } else {
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

// Validar fecha de vencimiento (debe ser futura)
const validarFechaVencimiento = (input, campo, mensaje, opcional = false) => {
    const grupo = document.getElementById(`grupo_${campo}`);
    const validacion = document.getElementById(`validacion_${campo}`);
    const fecha_vencimiento = new Date(input.value);
    const hoy = new Date();

    if (input.value.trim() === '') {
        if (opcional) {
            grupo.classList.remove(`input_field_${campo}_incorrecto`);
            grupo.classList.remove(`input_field_${campo}_correcto`);
            validacion.style.opacity = '0';
            campos[campo] = true;
        } else {
            grupo.classList.remove(`input_field_${campo}_correcto`);
            grupo.classList.add(`input_field_${campo}_incorrecto`);
            validacion.textContent = mensaje;
            validacion.style.opacity = '1';
            campos[campo] = false;
        }
    } else if (fecha_vencimiento < hoy) {
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

    // Validar todos los campos al enviar
    validarCampo(expresiones.numero_licencia, document.getElementById('numero_licencia'), 'numero_licencia', 'El número de licencia debe tener entre 5 y 15 caracteres (letras y números).', false);
    validarSelect(document.getElementById('tipo_licencia'), 'tipo_licencia', 'Seleccione un tipo de licencia.');
    validarFechaVencimiento(document.getElementById('fecha_vencimiento'), 'fecha_vencimiento', 'La fecha de vencimiento es obligatoria y debe ser futura.', false);
    validarCampo(expresiones.observaciones, document.getElementById('observaciones'), 'observaciones', 'Máximo 500 caracteres, solo letras, números y puntuación básica.', true);

    const formularioError = document.getElementById('formulario_error');
    const formularioExito = document.getElementById('formulario_exito');

    if (campos.numero_licencia && campos.tipo_licencia && campos.fecha_vencimiento) {
        formularioError.style.opacity = '0';
        formularioExito.style.opacity = '1';
        formulario.submit();
    } else {
        formularioError.textContent = 'Por favor, corrige los errores en el formulario.';
        formularioError.style.opacity = '1';
        formularioExito.style.opacity = '0';
    }
});