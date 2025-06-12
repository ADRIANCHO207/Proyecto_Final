const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');
const selects = document.querySelectorAll('#formulario select');
const textarea = document.querySelector('#observaciones');

// Expresiones regulares
const expresiones = {
    numero_licencia: /^[A-Z0-9]{5,15}$/,
    observaciones: /^[a-zA-Z0-9\s.,!?'-]{0,500}$/
};

// Campos validados
const campos = {
    numero_licencia: null,
    tipo_licencia: null,
    fecha_vencimiento: null,
    observaciones: null
};

// Validaciones dinámicas por campo
const validarFormulario = (e) => {
    switch (e.target.name) {
        case "categoria":
            validarSelect(e.target, 'categoria', 'Seleccione una categoria valida.');
            break;
        case "fecha_expedicion":
            validarFechaExpedicion(e.target, 'fecha_expedicion', 'La fecha de expedicion no puede ser futura.');
            break;
        case "fecha_vencimiento":
            validarFechaVencimiento(e.target, 'fecha_vencimiento', 'La fecha debe ser exactamente 10 años después de la expedición.');
            break;
        case "tipo_servicio":
            validarSelect(e.target, 'tipo_servicio', 'Selecciona un servicio de licencia valido.');
            break;
        case "observaciones":
            validarCampo(expresiones.observaciones, e.target, 'observaciones', 'Máximo 500 caracteres, solo letras, números y puntuación básica.', true);
            break;
    }
};

// Validar campos de texto
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
            grupo.classList.add(`input_field_${campo}_incorrecto`);
            grupo.classList.remove(`input_field_${campo}_correcto`);
            validacion.textContent = mensaje;
            validacion.style.opacity = '1';
            campos[campo] = false;
        }
    } else if (expresion.test(input.value.trim())) {
        grupo.classList.add(`input_field_${campo}_correcto`);
        grupo.classList.remove(`input_field_${campo}_incorrecto`);
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
        grupo.classList.add(`input_field_${campo}_incorrecto`);
        grupo.classList.remove(`input_field_${campo}_correcto`);
        validacion.textContent = mensaje;
        validacion.style.opacity = '1';
        campos[campo] = false;
    } else {
        grupo.classList.add(`input_field_${campo}_correcto`);
        grupo.classList.remove(`input_field_${campo}_incorrecto`);
        validacion.style.opacity = '0';
        campos[campo] = true;
    }
};

// Validar fecha de expedición
const validarFechaExpedicion = (input, campo, mensaje) => {
    const grupo = document.getElementById(`grupo_${campo}`);
    const validacion = document.getElementById(`validacion_${campo}`);
    const fecha = new Date(input.value + 'T00:00:00'); // fuerza hora local
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);

    if (input.value.trim() === '' || fecha > hoy) {
        grupo.classList.add(`input_field_${campo}_incorrecto`);
        grupo.classList.remove(`input_field_${campo}_correcto`);
        validacion.textContent = mensaje;
        validacion.style.opacity = '1';
        campos[campo] = false;
    } else {
        grupo.classList.add(`input_field_${campo}_correcto`);
        grupo.classList.remove(`input_field_${campo}_incorrecto`);
        validacion.style.opacity = '0';
        campos[campo] = true;

        // Calcular vencimiento
        const vencimiento = new Date(fecha);
        vencimiento.setFullYear(vencimiento.getFullYear() + 10);

        const yyyy = vencimiento.getFullYear();
        const mm = String(vencimiento.getMonth() + 1).padStart(2, '0');
        const dd = String(vencimiento.getDate()).padStart(2, '0');

        const vencimientoInput = document.getElementById("fecha_vencimiento");
        vencimientoInput.value = `${yyyy}-${mm}-${dd}`;

        // Validar vencimiento automáticamente
        validarFechaVencimiento(vencimientoInput, 'fecha_vencimiento', 'La fecha debe ser exactamente 10 años después de la expedición.');
    }
};

// Validar fecha de vencimiento
const validarFechaVencimiento = (input, campo, mensaje) => {
    const grupo = document.getElementById(`grupo_${campo}`);
    const validacion = document.getElementById(`validacion_${campo}`);
    const expedicionInput = document.getElementById("fecha_expedicion");

    if (!expedicionInput || expedicionInput.value.trim() === '') {
        grupo.classList.add(`input_field_${campo}_incorrecto`);
        grupo.classList.remove(`input_field_${campo}_correcto`);
        validacion.textContent = "Primero debe ingresar la fecha de expedición.";
        validacion.style.opacity = "1";
        campos[campo] = false;
        return;
    }

    const expedicion = new Date(expedicionInput.value);
    const vencimiento = new Date(input.value);
    expedicion.setHours(0, 0, 0, 0);
    vencimiento.setHours(0, 0, 0, 0);

    const esperado = new Date(expedicion);
    esperado.setFullYear(expedicion.getFullYear() + 10);
    esperado.setHours(0, 0, 0, 0);

    if (vencimiento.getTime() !== esperado.getTime()) {
        grupo.classList.add(`input_field_${campo}_incorrecto`);
        grupo.classList.remove(`input_field_${campo}_correcto`);
        validacion.textContent = mensaje;
        validacion.style.opacity = "1";
        campos[campo] = false;
    } else {
        grupo.classList.add(`input_field_${campo}_correcto`);
        grupo.classList.remove(`input_field_${campo}_incorrecto`);
        validacion.style.opacity = "0";
        campos[campo] = true;
    }
};

// Eventos en inputs y selects
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

// Submit del formulario
formulario.addEventListener('submit', (e) => {
    e.preventDefault();

    // Validar obligatorios
    validarSelect(document.getElementById('categoria'), 'categoria', 'Seleccione una categoria de licencia.');
    validarSelect(document.getElementById('tipo_servicio'), 'tipo_servicio', 'Seleccione un tipo de servicio valido.');
    validarFechaExpedicion(document.getElementById('fecha_expedicion'), 'fecha_expedicion', 'La fecha de expedicon no debe ser futura.')
    validarFechaVencimiento(document.getElementById('fecha_vencimiento'), 'fecha_vencimiento', 'La fecha debe ser exactamente 10 años después de la expedición.');
    validarCampo(expresiones.observaciones, document.getElementById('observaciones'), 'observaciones', 'Máximo 500 caracteres, solo letras, números y puntuación básica.', true);


    if (campos.categoria && campos.tipo_servicio && campos.fecha_vencimiento && campos.fecha_expedicion && campos.observaciones) {
        const formdatos = new FormData(formulario);

        $.ajax({
            type: "POST",
            url: "../AJAX/guardar_licencia.php",
            data: formdatos,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log("Respuesta del servidor:", response);
                
                if (response.status === "success") {
                    document.getElementById('formulario_exito').style.opacity = 1;
                    document.getElementById('formulario_exito').style.color = "#158000";
                    // Limpiar el formulario antes de redirigir
                    formulario.reset();
                    // Eliminar clases de validación visual
                    Object.keys(campos).forEach(campo => {
                        const grupo = document.getElementById(`grupo_${campo}`);
                        if (grupo) {
                            grupo.classList.remove(`input_field_${campo}_correcto`);
                            grupo.classList.remove(`input_field_${campo}_incorrecto`);
                        }
                        campos[campo] = false;
                    });
                    setTimeout(() => {
                        window.location.href = '../historiales/ver_licencia.php';
                    }, 3000);
                } else {
                    document.getElementById('formulario_error').style.opacity = 1;
                    document.getElementById('formulario_error').textContent = "Error: " + response.message;
                    document.getElementById('formulario_error').style.color = "#d32f2f";
                    setTimeout(() => {
                        document.getElementById('formulario_error').style.opacity = 0;
                    }, 3000);
                }
            },
            error: function () {
                document.getElementById('formulario_error').style.opacity = 1;
                document.getElementById('formulario_error').textContent = "Error en la conexión con el servidor.";
                document.getElementById('formulario_error').style.color = "#d32f2f";
                setTimeout(() => {
                    document.getElementById('formulario_error').style.opacity = 0;
                }, 3000);
            }
        });
    } else {
        document.getElementById('formulario_error').style.opacity = 1;
        document.getElementById('formulario_error').style.color = "#d32f2f";
        document.getElementById('formulario_error').textContent = "Debe completar correctamente todos los campos obligatorios.";

        setTimeout(() => {
            document.getElementById('formulario_error').style.opacity = 0;
        }, 3000);
    }
});

