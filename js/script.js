// const nombre = document.getElementById("nom")
// const apellido = document.getElementById("ape")
// const correo = document.getElementById("corre")
const mensaje = document.getElementById("mensa")
// const form = document.getElementById("form")
// const parrafo = document.getElementById("warnings") 
// const parrafo1 = document.getElementById("warnings1") 
// const parrafo2 = document.getElementById("warnings2") 
// const parrafo3 = document.getElementById("warnings3") 

// form.addEventListener("submit", e=>{
//     e.preventDefault()
//     let warnings = ""
//     let warnings1 = ""
//     let warnings2 = ""
//     let warnings3 = ""

//     let entrar = false
//     let validanombre = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{2,50}$/
//         validacorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
//         valodaparrafo = /^[\s\S]{10,500}$/

//     console.log(validanombre.test(nombre.value))
//     if(!validanombre.test(nombre.value)){
//         warnings += ``
//         entrar = true
//     }else{
//         warnings.opacity = 0  
//     }
//     console.log(validanombre.test(apellido.value))
//     if(!validanombre.test(apellido.value)){
//         warnings1 += `Asegurate de escribir bien el apellido <br>`
//         entrar = true
//     }else{
//         warnings1.opacity = 0
//     }
//     console.log(validacorreo.test(correo.value))
//     if(validacorreo.test(correo.value)) {
//         warnings2 += `Asegurate de escribir bien el correo <br>`
//         entrar = true
//     }

//     if(valodaparrafo.test(parrafo.value)){
//         warnings3 += `El mesaje debe llevar minimo 10 caracteres maximo 500 caracteres`
//         entrar = true
//     }

//     if(entrar){
//         parrafo.innerHTML = warnings
//         parrafo1.innerHTML = warnings1
//         parrafo2.innerHTML = warnings2
//         parrafo3.innerHTML = warnings3
//     }

// })

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

//     const nombre = document.getElementById('nom').value
//     const apellido = document.getElementById('ape').value
//     const correo = document.getElementById('corre').value
//     const mensaje = document.getElementById('mensa').value

//     const formData = new FormData() 

//     formData.append('nom', nombre)
//     formData.append('ape', apellido)
//     formData.append('corre', correo)
//     formData.append('mensa', mensaje)

//     fetch('../contacto.php',{
//         method: 'POST',
//         body: formData
    })
    
    





//     let camposVacios = false; // Variable para comprobar si hay campos vacíos
    
//     // Iterar sobre todos los inputs
//     inputs.forEach(input => {
//         if (input.value.trim() === "") { // Verifica si el campo está vacío o tiene solo espacios
//             document.getElementById('warnings4').style.opacity = 1; // Muestra el mensaje de advertencia
//             input.style.border = "solid 2px red"; // Resalta el campo vacío
//             camposVacios = true; // Marca que hay al menos un campo vacío
//         } else {
//             input.style.border = "solid 2px green"; // Resalta el campo lleno
//         }
//     });

//     // Si no hay campos vacíos, procede con la lógica que necesites
//     if (!camposVacios) {
//         document.getElementById('warnings4').style.opacity = 0
//         document.getElementById('warnings5').style.opacity = 1
//         // Aquí podrías enviar el formulario si es necesario
//         form.submit(), 5000
//     }

// });
