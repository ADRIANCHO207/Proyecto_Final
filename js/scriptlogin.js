const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');

const expresiones = {
    validadocumento: /^\d{6,10}$/,
    validanombre: /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,50}$/,
    validapassword: /^.{4,12}$/
};

// Función reutilizable para validar campos
const validarCampo = (expresion, input, grupo, mensaje) => {
    const grupoElemento = document.getElementById(`grupo_${grupo}`);
    const validacionMensaje = document.getElementById(mensaje);

    if (expresion.test(input.value)) {
        grupoElemento.classList.remove(`input_field_${grupo}`);
        grupoElemento.classList.remove(`input_field_${grupo}_incorrecto`);
        grupoElemento.classList.add(`input_field_${grupo}_correcto`);
        validacionMensaje.style.opacity = 0;
    } else {
        grupoElemento.classList.remove(`input_field_${grupo}_correcto`);
        grupoElemento.classList.add(`input_field_${grupo}_incorrecto`);
        validacionMensaje.style.opacity = 1;
    }
};

const validarformulario = (e) => {
    switch (e.target.name) {
        case "doc":
            validarCampo(expresiones.validadocumento, e.target, 'doc', 'validacion');
            break;
        case "nom":
            validarCampo(expresiones.validanombre, e.target, 'nom', 'validacion1');
            break;
        case "passw":
            validarCampo(expresiones.validapassword, e.target, 'passw', 'validacion2');
            break;
    }
};

inputs.forEach((input) => {
    input.addEventListener('keyup', validarformulario);
    input.addEventListener('blur', validarformulario);
});



formulario.addEventListener('submit', (e) => {
    e.preventDefault();

    const docvalido = expresiones.validadocumento.test($('#doc').val());
    const nomvalido = expresiones.validanombre.test($('#nom').val());
    const passvalido = expresiones.validapassword.test($('#passw').val());

    if (docvalido && nomvalido && passvalido) {
        $.ajax({
            type: "POST",
            url: "../includes/inicio.php",
            data: {
                doc: $('#doc').val(),
                nom: $('#nom').val(),
                passw: $('#passw').val()
            },
            success: function(response) {
                console.log("Respuesta del servidor:", response);
                document.getElementById('formulario_exito').style.opacity = 1;
                document.getElementById('formulario_exito').style.color = "#158000";
                setTimeout(() => {
                    document.getElementById('formulario_exito').style.opacity = 0;
                }, 3000);
            },
            error: function(xhr, status, error) {
                console.error("Error al enviar el formulario:", error);
                document.getElementById('formulario_error').style.opacity = 1;
                $('#doc').focus();
                setTimeout(() => {
                    document.getElementById('formulario_error').style.opacity = 0;
                }, 3000);
            }
        });
    } else {
        document.getElementById('formulario_error').style.opacity = 1;
        document.getElementById('formulario_error').style.color = "#d32f2f";

        setTimeout(() => {
            document.getElementById('formulario_error').style.opacity = 0;
        }, 3000);
        
        if (!docValido) {
            $('#doc').focus();
            validarCampo(expresiones.validadocumento, document.getElementById('doc'), 'doc', 'validacion1');
        } else if (!nomValido) {
            $('#nom').focus();
            validarCampo(expresiones.validanombre, document.getElementById('nom'), 'nom', 'validacion');
        } else if (!passValido) {
            $('#passw').focus();
            validarCampo(expresiones.validapassword, document.getElementById('passw'), 'passw', 'validacion2');
        }

        
    }
});
