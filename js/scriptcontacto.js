const mensaje = document.getElementById("mensa")


const form = document.getElementById('form')
const inputs = document.querySelectorAll('#form input')

const expresiones = {
    validanombre:  /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,50}$/,
    validaapellido: /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,50}$/,
    validacorreo:  /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
    valodaparrafo: /^[\s\S]{10,500}$/
}

const validarform = (e) => {
    switch (e.target.name) {
        case "nom":
            if(expresiones.validanombre.test(e.target.value)){
                document.getElementById('warnings').style.opacity = 0;
                document.getElementById('nom').style.border = "solid 3px green";;
                document.getElementById('nom').style.color = "black";

            }else{
                document.getElementById('warnings').style.background = "#d32f2f";
                document.getElementById('warnings').style.opacity = 1;
                document.getElementById('nom').style.border = "solid 3px #d32f2f";
                document.getElementById('nom').style.color = "#d32f2f";
            }
        break
        case "ape":
            if(expresiones.validaapellido.test(e.target.value)){
                document.getElementById('warnings1').style.opacity = 0;
                document.getElementById('ape').style.border = "solid 3px green";;
                document.getElementById('ape').style.color = "black";
            }else{
                document.getElementById('warnings1').style.background = "#d32f2f";
                document.getElementById('warnings1').style.opacity = 1;
                document.getElementById('ape').style.border = "solid 3px #d32f2f";
                document.getElementById('ape').style.color = "#d32f2f";
            }
        break
        case "corre":
            if(expresiones.validacorreo.test(e.target.value)){
                document.getElementById('warnings2').style.opacity = 0;
                document.getElementById('corre').style.border = "solid 3px green";
                document.getElementById('corre').style.color = "black";

            }else{
                document.getElementById('warnings2').style.background = "#d32f2f";
                document.getElementById('warnings2').style.opacity = 1;
                document.getElementById('corre').style.border = "solid 3px #d32f2f";
                document.getElementById('corre').style.color = "#d32f2f";
            }
        break
        
    }
}

const textarea = document.getElementById('mensa');
const warnings = document.getElementById('warnings3');

textarea.addEventListener('input', () => {
    const value = textarea.value.trim();
    if (value.length < 10) {
        warnings.textContent = "El mensaje debe tener al menos 10 caracteres.";
        warnings.style.background = "#d32f2f";
        textarea.style.color = "#d32f2f";
        textarea.style.border = "solid 3px #d32f2f";
        warnings.style.opacity = 1;
        
        
    } else if (value.length > 500) {
        warnings.textContent = "El mensaje no debe exceder los 500 caracteres.";
        warnings.style.background = "#d32f2f";
        textarea.style.color = "#d32f2f";
        textarea.style.border = "solid 3px #d32f2f";
        warnings.style.opacity = 1;
    } else {
        warnings.style.background = "none";
        warnings.style.opacity = 0;
        textarea.style.color = "black";
        textarea.style.border = "solid 3px green";
        
    }
});

inputs.forEach((input) => {
    input.addEventListener('keyup', validarform)
    input.addEventListener('blur', validarform)
})

const mensajeerror = document.getElementById('warnings4')
const mensajecorrecto = document.getElementById('warnings5')

form.addEventListener('submit', function(event) {

    event.preventDefault(); // Evita el envío del formulario
    console.log('no envia')

})
