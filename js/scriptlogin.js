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
    const passvalido = expresiones.validapassword.test($('#passw').val());

    if (docvalido && passvalido) {
        $.ajax({
            type: "POST",
            url: "../includes/inicio.php",
            data: {
                doc: $('#doc').val(),
                passw: $('#passw').val(),
                log: true
            },
            dataType: 'json', // <--- importante
            success: function(response) {
                console.log("Respuesta del servidor:", response);

                if (response.status === "success") {
                    document.getElementById('formulario_exito').style.opacity = 1;
                    document.getElementById('formulario_exito').style.color = "#158000";

                    setTimeout(() => {
                        document.getElementById('formulario_exito').style.opacity = 0;

                        // Redirección dependiendo del rol
                        if (response.rol === "admin") {
                            location.href = "/roles/admin/index";
                        } else if (response.rol === "usuario") {
                            location.href = "/roles/usuario/index";
                        }

                    }, 2000);

                } else {
                    document.getElementById('formulario_error').style.opacity = 1;
                    document.getElementById('formulario_error').style.color = "#d32f2f";
                    document.getElementById('formulario_error').innerText = response.message; 
                    $('#doc').focus();
                    setTimeout(() => {
                        document.getElementById('formulario_error').style.opacity = 0;
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al enviar el formulario:", xhr.responseText);
                document.getElementById('formulario_error').style.opacity = 1;
                document.getElementById('formulario_error').style.color = "#d32f2f";
                document.getElementById('formulario_error').innerText = "Error de conexión con el servidor";
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
        
        if (!docvalido) {
            $('#doc').focus();
            validarCampo(expresiones.validadocumento, document.getElementById('doc'), 'doc', 'validacion1');
        } else if (!passvalido) {
            $('#passw').focus();
            validarCampo(expresiones.validapassword, document.getElementById('passw'), 'passw', 'validacion2');
        }

        
    }
});
