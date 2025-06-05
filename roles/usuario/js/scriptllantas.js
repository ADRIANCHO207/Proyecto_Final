$(document).ready(function() {
    const formulario = $('#formulario');
    const inputs = $('#formulario input, #formulario select, #formulario textarea');

    const expresiones = {
        placa: /.+/, // Cualquier valor no vacío para placa (puede ajustarse si hay formato específico)
        estado: /^(Bueno|Regular|Malo)$/, // Solo los valores permitidos
        fecha: /^\d{4}-\d{2}-\d{2}$/, // Formato YYYY-MM-DD
        presion: /^(?:[0-9]|[1-9][0-9]|100)(?:\.[0-9]{1,2})?$/, // 0.1 a 100.0
        kilometraje: /^[0-9]+$/, // Solo números positivos
        notas: /^[a-zA-Z0-9\s.,!?'-]{0,500}$/ // Máximo 500 caracteres, solo letras, números y puntuación básica
    };

    // Función reutilizable para validar campos
    const validarCampo = (expresion, input, grupo, mensaje) => {
        const grupoElemento = $(`#grupo_${grupo}`);
        const validacionMensaje = $(`#validacion_${grupo}`);

        if (expresion.test(input.val())) {
            grupoElemento.removeClass(`input_field_${grupo}_incorrecto`).addClass(`input_field_${grupo}_correcto`);
            validacionMensaje.css('opacity', '0');
            return true;
        } else {
            grupoElemento.removeClass(`input_field_${grupo}_correcto`).addClass(`input_field_${grupo}_incorrecto`);
            validacionMensaje.css('opacity', '1');
            return false;
        }
    };

    // Validaciones específicas
    const validarFormulario = (e) => {
        switch (e.target.id) {
            case "placa":
                validarCampo(expresiones.placa, $(e.target), 'placa', 'placa');
                break;
            case "estado":
                validarCampo(expresiones.estado, $(e.target), 'estado', 'estado');
                break;
            case "ultimo_cambio":
                if ($(e.target).val()) {
                    const inputDate = new Date($(e.target).val());
                    const today = new Date();
                    if (inputDate > today) {
                        $(`#grupo_${e.target.id}`).removeClass('input_field_ultimo_cambio_correcto').addClass('input_field_ultimo_cambio_incorrecto');
                        $(`#validacion_${e.target.id}`).css('opacity', '1');
                    } else {
                        $(`#grupo_${e.target.id}`).removeClass('input_field_ultimo_cambio_incorrecto').addClass('input_field_ultimo_cambio_correcto');
                        $(`#validacion_${e.target.id}`).css('opacity', '0');
                    }
                }
                break;
            case "presion_llantas":
                validarCampo(expresiones.presion, $(e.target), 'presion_llantas', 'presion_llantas');
                break;
            case "kilometraje_actual":
                validarCampo(expresiones.kilometraje, $(e.target), 'kilometraje_actual', 'kilometraje_actual');
                break;
            case "proximo_cambio_km":
                validarCampo(expresiones.kilometraje, $(e.target), 'proximo_cambio_km', 'proximo_cambio_km');
                break;
            case "proximo_cambio_fecha":
                if ($(e.target).val()) {
                    const inputDate = new Date($(e.target).val());
                    const today = new Date();
                    if (inputDate < today) {
                        $(`#grupo_${e.target.id}`).removeClass('input_field_proximo_cambio_fecha_correcto').addClass('input_field_proximo_cambio_fecha_incorrecto');
                        $(`#validacion_${e.target.id}`).css('opacity', '1');
                    } else {
                        $(`#grupo_${e.target.id}`).removeClass('input_field_proximo_cambio_fecha_incorrecto').addClass('input_field_proximo_cambio_fecha_correcto');
                        $(`#validacion_${e.target.id}`).css('opacity', '0');
                    }
                }
                break;
            case "notas":
                validarCampo(expresiones.notas, $(e.target), 'notas', 'notas');
                break;
        }
    };

    // Event listeners para validación en tiempo real
    inputs.on('keyup', validarFormulario);
    inputs.on('blur', validarFormulario);

    // Validación al enviar el formulario
    formulario.on('submit', function(e) {
        e.preventDefault();

        const isPlacaValid = validarCampo(expresiones.placa, $('#placa'), 'placa', 'placa');
        const isEstadoValid = validarCampo(expresiones.estado, $('#estado'), 'estado', 'estado');
        const isUltimoCambioValid = $('#ultimo_cambio').val() ? (new Date($('#ultimo_cambio').val()) <= new Date()) : true;
        const isPresionValid = validarCampo(expresiones.presion, $('#presion_llantas'), 'presion_llantas', 'presion_llantas');
        const isKilometrajeActualValid = validarCampo(expresiones.kilometraje, $('#kilometraje_actual'), 'kilometraje_actual', 'kilometraje_actual');
        const isProximoKmValid = validarCampo(expresiones.kilometraje, $('#proximo_cambio_km'), 'proximo_cambio_km', 'proximo_cambio_km');
        const isProximoFechaValid = $('#proximo_cambio_fecha').val() ? (new Date($('#proximo_cambio_fecha').val()) >= new Date()) : true;
        const isNotasValid = validarCampo(expresiones.notas, $('#notas'), 'notas', 'notas');

        if (isPlacaValid && isEstadoValid && isUltimoCambioValid && isPresionValid && 
            isKilometrajeActualValid && isProximoKmValid && isProximoFechaValid && isNotasValid) {
            $.ajax({
                type: "POST",
                url: "gestionar_llantas.php",
                data: formulario.serialize(),
                success: function(response) {
                    $('#formulario_exito').css('opacity', '1').css('color', '#158000');
                    setTimeout(() => $('#formulario_exito').css('opacity', '0'), 3000);
                    formulario[0].reset(); // Limpia el formulario
                },
                error: function(xhr, status, error) {
                    $('#formulario_error').css('opacity', '1').css('color', '#d32f2f');
                    setTimeout(() => $('#formulario_error').css('opacity', '0'), 5000);
                    $('#placa').focus();
                }
            });
        } else {
            $('#formulario_error').css('opacity', '1').css('color', '#d32f2f');
            setTimeout(() => $('#formulario_error').css('opacity', '0'), 5000);
            if (!isPlacaValid) $('#placa').focus();
            else if (!isEstadoValid) $('#estado').focus();
            else if (!isUltimoCambioValid) $('#ultimo_cambio').focus();
            else if (!isPresionValid) $('#presion_llantas').focus();
            else if (!isKilometrajeActualValid) $('#kilometraje_actual').focus();
            else if (!isProximoKmValid) $('#proximo_cambio_km').focus();
            else if (!isProximoFechaValid) $('#proximo_cambio_fecha').focus();
            else if (!isNotasValid) $('#notas').focus();
        }
    });
});