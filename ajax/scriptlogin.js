const formulario = document.getElementById('formulario')
const inputs = document.querySelectorAll('#formulario input')

const expresiones ={
    validadocumento: /^\d{10}$/,
    validanombre: /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,50}$/,
    validapassword: /^.{4,12}$/
}

const validarformulario =  (e) => {
    switch (e.target.name) {
        case "doc":
            if(expresiones.validadocumento.test(e.target.value)){
                document.getElementById('grupo_doc').classList.remove('input_field_doc')
                document.getElementById('grupo_doc').classList.add('input_field_doc_correcto')
                document.getElementById('validacion1').style.opacity = 0;
            }else{  
                document.getElementById('grupo_doc').classList.remove('input_field_doc_correcto')
                document.getElementById('grupo_doc').classList.add('input_field_doc_incorrecto')
                document.getElementById('validacion1').style.opacity = 1;
            }
        break
        case "nom":
            if(expresiones.validanombre.test(e.target.value)){
                document.getElementById('grupo_nom').classList.remove('input_field_nom')
                document.getElementById('grupo_nom').classList.add('input_field_nom_correcto')
                document.getElementById('validacion').style.opacity = 0;
            }else{  
                document.getElementById('grupo_nom').classList.remove('input_field_nom_correcto')
                document.getElementById('grupo_nom').classList.add('input_field_nom_incorrecto')
                document.getElementById('validacion').style.opacity = 1;

            }
            
            break
        case "passw":
            if(expresiones.validapassword.test(e.target.value)){
                document.getElementById('grupo_passw').classList.remove('input_field_passw')
                document.getElementById('grupo_passw').classList.add('input_field_passw_correcto')
                document.getElementById('validacion2').style.opacity = 0;
            }else{  
                document.getElementById('grupo_passw').classList.remove('input_field_passw_correcto')
                document.getElementById('grupo_passw').classList.add('input_field_passw_incorrecto')
                document.getElementById('validacion2').style.opacity = 1;
            }
            break

    }
}

// const validarcampo = (expresion, input, campo) =>{
//     if(expresion.test(input.value)){
//             document.getElementById(`grupo_${campo}`).classList.remove(`input_field_${campo}`)
//             document.getElementById(`grupo_${campo}`).classList.add(`input_field_${campo}_correcto`)
//             document.getElementById('validacion1').style.opacity = 0;
            
//     }else{  
//         document.getElementById(`grupo_${campo}`).classList.remove(`input_field_${campo}_correcto`)
//         document.getElementById(`grupo_${campo}`).classList.add(`input_field_${campo}_incorrecto`)
//         document.getElementById('validacion1').style.opacity = 1;
//     }
// }        


inputs.forEach((input) => {
    input.addEventListener('keyup', validarformulario)
    input.addEventListener('blur', validarformulario)
})  

formulario.addEventListener('submit', (e) =>{
    e.preventDefault();

    const documentovalido = expresiones.validadocumento.test($('#doc').val());
    const nombrevalido = expresiones.validanombre.test($('#nom').val());
    const passwvalida = expresiones.validapassword.test($('#passw').val());

    console.log("Intentando enviar formulario...");


    if (documentovalido && nombrevalido && passwvalida) {
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
                document.getElementById('formulario_exito').style.color = "#158000"
                


                setTimeout(() => {
                    document.getElementById('formulario_exito').style.opacity = 0;
                }, 3000);
            },
            error: function(xhr, status, error) {
                console.log("Error detectado:", error);
                console.error("Error al enviar el formulario:", error);
                document.getElementById('formulario_error').style.opacity = 1;
                document.getElementById('#doc').focus();

                setTimeout(() => {
                    document.getElementById('formulario_error').style.opacity = 0;
                }, 3000)
            } 

        })
    }else{
        document.getElementById('formulario_error').style.opacity = 1;
        document.getElementById('formulario_error').style.color = "#d32f2f"

        if (!documentovalido) {
            document.getElementById('doc').focus();
            document.getElementById('grupo_doc').classList.add('input_field_doc_incorrecto')
            document.getElementById('validacion1').style.opacity = 1;
        } else if (!nombrevalido) {
            document.getElementById('nom').focus();
            document.getElementById('grupo_nom').classList.add('input_field_nom_incorrecto')
            document.getElementById('validacion').style.opacity = 1;
        } else if (!passwvalida) {
            document.getElementById('passw').focus();
            document.getElementById('grupo_passw').classList.add('input_field_passw_incorrecto')
            document.getElementById('validacion2').style.opacity = 1;
        }

        setTimeout(() => {
            document.getElementById('formulario_error').style.opacity = 0;
        }, 3000)
    }
})

