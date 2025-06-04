const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');

const expresiones = {
    validadocumento: /^\d{6,10}$/,
    validanombre: /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,50}$/,
    validacorreo: /^[a-zA-Z0-9._%+-]+@gmail\.com$/,
    validapassword: /^.{4,12}$/
};

// Función reutilizable para validar campos
const validarcampo = (expresion, input, grupo, mensaje) => {
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

// validar contraseña 2

const validarPassword2 = () => {
    const inputPassword1 = document.getElementById('con');
    const inputPassword2 = document.getElementById('con2');
    const grupo = document.getElementById('grupo_con2');
    const mensaje = document.getElementById('validacion4');

    if (inputPassword1.value !== inputPassword2.value || inputPassword2.value.length === 0) {
        grupo.classList.add('input_field_con2_incorrecto');
        grupo.classList.remove('input_field_con2_correcto');
        mensaje.style.opacity = 1;
        mensaje.textContent = "Las contraseñas no coinciden...";
    } else {
        grupo.classList.remove('input_field_con2_incorrecto');
        grupo.classList.add('input_field_con2_correcto');
        mensaje.style.opacity = 0;
    }
};

const validarformulario = (e) => {
    switch (e.target.name) {
        case "doc":
            validarcampo(expresiones.validadocumento, e.target, 'doc', 'validacion');
            break;
        case "nom":
            validarcampo(expresiones.validanombre, e.target, 'nom', 'validacion1');
            break;
        case "correo":
            validarcampo(expresiones.validacorreo, e.target, 'correo', 'validacion2');
            break;
        case "con":
            validarcampo(expresiones.validapassword, e.target, 'con', 'validacion3');
            validarPassword2()
            break;
        case "con2":
            validarPassword2()
            break;
        case "cel":
            validarcampo(expresiones.validadocumento, e.target, 'cel', 'validacion5');
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
    const corrvalido = expresiones.validacorreo.test($('#correo').val());
    const passvalido = expresiones.validapassword.test($('#con').val());
    const pass2valido = expresiones.validapassword.test($('#con2').val()) && $('#con').val() === $('#con2').val();
    const celvalido = expresiones.validapassword.test($('#cel').val());

    if (docvalido && nomvalido && corrvalido && passvalido && pass2valido && celvalido) {
        $.ajax({
            type: "POST",
            url: "../ajax/datos_registro.php",
            data: {
                doc: $('#doc').val(),
                nom: $('#nom').val(),
                correo: $('#correo').val(),
                con: $('#con').val(),
                con2: $('#con2').val(),
                cel: $('#cel').val(),
            },
            success: function(response) {
                console.log("Respuesta del servidor:", response);
                if (response.status === "success") {
                    document.getElementById('formulario_exito').style.opacity = 1;
                    document.getElementById('formulario_exito').style.color = "#158000";
                    setTimeout(() => {
                        window.location.href = "login.php";
                    }, 1000);
                } else {
                    document.getElementById('formulario_error').style.opacity = 1;
                    document.getElementById('formulario_error').textContent = "Error: " + response.message;
                    document.getElementById('formulario_error').style.color = "#d32f2f";
                    setTimeout(() => {
                        document.getElementById('formulario_error').style.opacity = 0;
                    }, 3000);
                }
            }
            
        });
    }else{
        document.getElementById('formulario_error').style.opacity = 1;
        document.getElementById('formulario_error').style.color = "#d32f2f";


        setTimeout(() => {
            document.getElementById('formulario_error').style.opacity = 0;
        }, 3000);


        if (!docvalido) {
            $('#doc').focus();
            validarcampo(expresiones.validadocumento, document.getElementById('doc'), 'doc', 'validacion');
        } else if (!nomvalido) {
            $('#nom').focus();
            validarcampo(expresiones.validanombre, document.getElementById('nom'), 'nom', 'validacion1');
        } else if (!corrvalido) {
            $('#correo').focus();
            validarcampo(expresiones.validacorreo, document.getElementById('correo'), 'correo', 'validacion2');
        }else if (!passvalido) {
            $('#con').focus();
            validarcampo(expresiones.validapassword, document.getElementById('con'), 'con', 'validacion3');
        }else if (!pass2valido) {
            $('#con2').focus();
            validarcampo(expresiones.validapassword, document.getElementById('con2'), 'con2', 'validacion4');
        }else if (!celvalido) {
            $('#cel').focus();
            validarcampo(expresiones.validapassword, document.getElementById('cel'), 'cel', 'validacion5');
        }

        
       
    }
});
  