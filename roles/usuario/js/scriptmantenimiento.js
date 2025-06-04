$(document).ready(function() {
    const formulario = $('#formulario');
    const inputs = $('#formulario input, #formulario select, #formulario textarea');

    // Expresiones regulares
    const expresiones = {
        placa: /.+/, // Cualquier valor no vacío
        tipo_mantenimiento: /.+/, // Cualquier valor no vacío
        fecha: /^\d{4}-\d{2}-\d{2}$/, // Formato YYYY-MM-DD
        kilometraje: /^[0-9]+$/, // Solo números positivos
        observaciones: /^[a-zA-Z0-9\s.,!?'-]{0,500}$/, // Máximo 500 caracteres
    };

    // Validar campos
    const validarCampo = (expresion, input, grupo, mensaje) => {
        const grupoElemento = $(`#${grupo}`);
        const validacionMensaje = $(`#validacion_${grupo.replace('grupo_', '')}`);

        if (!grupoElemento.length || !validacionMensaje.length) {
            console.error(`Elementos ${grupo} o validacion_${grupo.replace('grupo_', '')} no encontrados`);
            return false;
        }

        const valor = input.val().trim();
        if (expresion.test(valor)) {
            grupoElemento.removeClass(`input_field_${grupo.replace('grupo_', '')}_incorrecto`).addClass(`input_field_${grupo.replace('grupo_', '')}_correcto`);
            validacionMensaje.text(mensaje).css('opacity', '0');
            return true;
        } else {
            grupoElemento.removeClass(`input_field_${grupo.replace('grupo_', '')}_correcto`).addClass(`input_field_${grupo.replace('grupo_', '')}_incorrecto`);
            validacionMensaje.text(mensaje).css('opacity', '1');
            return false;
        }
    };

    // Validar fechas
    const validarFecha = (input, grupo, mensaje, opcional = false, futura = false) => {
        const grupoElemento = $(`#${grupo}`);
        const validacionMensaje = $(`#validacion_${grupo.replace('grupo_', '')}`);
        const inputVal = input.val().trim();
        const inputDate = new Date(inputVal);
        const today = new Date();

        if (!grupoElemento.length || !validacionMensaje.length) {
            console.error(`Elementos ${grupo} o validacion_${grupo.replace('grupo_', '')} no encontrados`);
            return false;
        }

        if (!inputVal) {
            if (opcional) {
                grupoElemento.removeClass(`input_field_${grupo.replace('grupo_', '')}_correcto input_field_${grupo.replace('grupo_', '')}_incorrecto`);
                validacionMensaje.text('').css('opacity', '0');
                return true;
            } else {
                grupoElemento.removeClass(`input_field_${grupo.replace('grupo_', '')}_correcto`).addClass(`input_field_${grupo.replace('grupo_', '')}_incorrecto`);
                validacionMensaje.text(mensaje).css('opacity', '1');
                return false;
            }
        } else if (!expresiones.fecha.test(inputVal)) {
            grupoElemento.removeClass(`input_field_${grupo.replace('grupo_', '')}_correcto`).addClass(`input_field_${grupo.replace('grupo_', '')}_incorrecto`);
            validacionMensaje.text('Formato de fecha inválido (YYYY-MM-DD).').css('opacity', '1');
            return false;
        } else if (grupo === 'grupo_fecha_realizada' && inputDate > today) {
            grupoElemento.removeClass(`input_field_${grupo.replace('grupo_', '')}_correcto`).addClass(`input_field_${grupo.replace('grupo_', '')}_incorrecto`);
            validacionMensaje.text('La fecha no puede ser futura.').css('opacity', '1');
            return false;
        } else if (futura && inputDate < today) {
            grupoElemento.removeClass(`input_field_${grupo.replace('grupo_', '')}_correcto`).addClass(`input_field_${grupo.replace('grupo_', '')}_incorrecto`);
            validacionMensaje.text('La fecha no puede ser pasada.').css('opacity', '1');
            return false;
        } else {
            grupoElemento.removeClass(`input_field_${grupo.replace('grupo_', '')}_incorrecto`).addClass(`input_field_${grupo.replace('grupo_', '')}_correcto`);
            validacionMensaje.text('').css('opacity', '0');
            return true;
        }
    };

    // Validaciones específicas
    const validarFormulario = (e) => {
        const target = $(e.target);
        switch (target.attr('id')) {
            case 'placa':
                validarCampo(expresiones.placa, target, 'grupo_placa', 'Seleccione un vehículo.');
                break;
            case 'tipo_mantenimiento':
                validarCampo(expresiones.tipo_mantenimiento, target, 'grupo_id_tipo_mantenimiento', 'Seleccione un tipo de mantenimiento.');
                break;
            case 'fecha_programada':
                validarFecha(target, 'grupo_fecha_programada', 'Seleccione una fecha válida.', false);
                break;
            case 'fecha_realizada':
                validarFecha(target, 'grupo_fecha_realizada', 'Fecha no puede ser futura.', true);
                break;
            case 'kilometraje_actual':
                validarCampo(expresiones.kilometraje, target, 'grupo_kilometraje_actual', 'Ingrese un número positivo.', true);
                break;
            case 'proximo_cambio_km':
                validarCampo(expresiones.kilometraje, target, 'grupo_proximo_cambio_km', 'Ingrese un número positivo.', true);
                break;
            case 'proximo_cambio_fecha':
                validarFecha(target, 'grupo_proximo_cambio_fecha', 'Fecha no puede ser pasada.', true, true);
                break;
            case 'observaciones':
                validarCampo(expresiones.observaciones, target, 'grupo_observaciones', 'Máximo 500 caracteres, solo letras, números y puntuación básica.', true);
                break;
        }
    };

    // Event listeners
    inputs.on('keyup change blur', validarFormulario);

    // Validación al enviar
    formulario.on('submit', function(e) {
        e.preventDefault();

        const isPlacaValid = validarCampo(expresiones.placa, $('#placa'), 'grupo_placa', 'Seleccione un vehículo.');
        const isTipoMantenimientoValid = validarCampo(expresiones.tipo_mantenimiento, $('#tipo_mantenimiento'), 'grupo_id_tipo_mantenimiento', 'Seleccione un tipo de mantenimiento.');
        const isFechaProgramadaValid = validarFecha($('#fecha_programada'), 'grupo_fecha_programada', 'Seleccione una fecha válida.', false);
        const isFechaRealizadaValid = validarFecha($('#fecha_realizada'), 'grupo_fecha_realizada', 'Fecha no puede ser futura.', true);
        const isKilometrajeActualValid = validarCampo(expresiones.kilometraje, $('#kilometraje_actual'), 'grupo_kilometraje_actual', 'Ingrese un número positivo.', true);
        const isProximoKmValid = validarCampo(expresiones.kilometraje, $('#proximo_cambio_km'), 'grupo_proximo_cambio_km', 'Ingrese un número positivo.', true);
        const isProximoFechaValid = validarFecha($('#proximo_cambio_fecha'), 'grupo_proximo_cambio_fecha', 'Fecha no puede ser pasada.', true, true);
        const isObservacionesValid = validarCampo(expresiones.observaciones, $('#observaciones'), 'grupo_observaciones', 'Máximo 500 caracteres, solo letras, números y puntuación básica.', true);

        const formularioError = $('#formulario_error');
        const formularioExito = $('#formulario_exito');

        if (isPlacaValid && isTipoMantenimientoValid && isFechaProgramadaValid && isFechaRealizadaValid &&
            isKilometrajeActualValid && isProximoKmValid && isProximoFechaValid && isObservacionesValid) {
            formularioError.css('opacity', '0');
            formularioExito.css('opacity', '1').css('color', '#158000');
            $.ajax({
                type: 'POST',
                url: 'guardar_mantenimiento.php',
                data: formulario.serialize(),
                success: function(response) {
                    if (response.includes('Guardado exitosamente')) {
                        formularioExito.text('Guardado exitosamente');
                        setTimeout(() => formularioExito.css('opacity', '0'), 3000);
                        formulario[0].reset();
                    } else {
                        formularioError.text(response || 'Error al guardar. Inténtelo de nuevo.').css('opacity', '1').css('color', '#d32f2f');
                        setTimeout(() => formularioError.css('opacity', '0'), 5000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', status, error);
                    console.error('Respuesta del servidor:', xhr.responseText);
                    formularioError.text('Error al guardar. Inténtelo de nuevo.').css('opacity', '1').css('color', '#d32f2f');
                    setTimeout(() => formularioError.css('opacity', '0'), 5000);
                    $('#placa').focus();
                }
            });
        } else {
            formularioError.text('Por favor, corrige los errores en el formulario.').css('opacity', '1').css('color', '#d32f2f');
            setTimeout(() => formularioError.css('opacity', '0'), 5000);
            if (!isPlacaValid) $('#placa').focus();
            else if (!isTipoMantenimientoValid) $('#tipo_mantenimiento').focus();
            else if (!isFechaProgramadaValid) $('#fecha_programada').focus();
            else if (!isFechaRealizadaValid) $('#fecha_realizada').focus();
            else if (!isKilometrajeActualValid) $('#kilometraje_actual').focus();
            else if (!isProximoKmValid) $('#proximo_cambio_km').focus();
            else if (!isProximoFechaValid) $('#proximo_cambio_fecha').focus();
            else if (!isObservacionesValid) $('#observaciones').focus();
        }
    });
});