/**
 * Sistema de Gestión de Vehículos - Scripts de Administración
 * 
 * Este archivo contiene toda la funcionalidad JavaScript para la gestión
 * de vehículos en el panel de administración del sistema de flotas.
 * 
 * Funcionalidades principales:
 * - Gestión de modales (agregar, editar vehículos)
 * - Validación y envío de formularios
 * - Previsualización de imágenes
 * - Operaciones CRUD (crear, leer, actualizar, eliminar)
 * - Manejo de sesiones y redirecciones
 * 
 * @author Sistema de Gestión de Flotas
 * @version 1.0
 */

/**
 * Inicialización del sistema cuando el DOM está completamente cargado
 * Configura todos los event listeners y inicializa los componentes
 */
document.addEventListener("DOMContentLoaded", () => {
  // Inicializar todos los modales de Bootstrap presentes en la página
  const modales = document.querySelectorAll(".modal")
  modales.forEach((modal) => {
    new bootstrap.Modal(modal)
  })

  // Configurar el botón de agregar vehículo
  // Abre el modal para registrar un nuevo vehículo
  const btnAgregar = document.getElementById("btnAgregarVehiculo")
  if (btnAgregar) {
    btnAgregar.addEventListener("click", () => {
      const modalAgregar = new bootstrap.Modal(document.getElementById("modalAgregarVehiculo"))
      modalAgregar.show()
    })
  }

  // Configurar los botones de editar vehículos
  // Cada botón de edición carga los datos del vehículo en el modal de edición
  const btnsEditar = document.querySelectorAll(".action-icon.edit")
  btnsEditar.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault()
      const fila = this.closest("tr") // Obtener la fila de la tabla
      cargarDatosEdicion(fila)
    })
  })

  // Configurar los botones de eliminar vehículos
  // Solicita confirmación antes de proceder con la eliminación
  const btnsEliminar = document.querySelectorAll(".action-icon.delete")
  btnsEliminar.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault()
      const fila = this.closest("tr")
      const placa = fila.querySelector(".placa-cell").textContent

      // Mostrar confirmación antes de eliminar
      if (confirm(`¿Está seguro que desea eliminar el vehículo con placa ${placa}?`)) {
        const idVehiculo = fila.getAttribute("data-id")
        eliminarVehiculo(idVehiculo)
      }
    })
  })

  // Configurar el checkbox de mantener foto en el modal de edición
  // Permite al usuario decidir si mantener la foto actual o subir una nueva
  const checkMantenerFoto = document.getElementById("mantener_foto")
  const inputFoto = document.getElementById("edit_foto_vehiculo")

  if (checkMantenerFoto && inputFoto) {
    checkMantenerFoto.addEventListener("change", function () {
      // Deshabilitar el input de foto si se marca mantener la foto actual
      inputFoto.disabled = this.checked
    })
  }

  // Configurar validación y envío de formularios
  const formAgregar = document.getElementById("formAgregarVehiculo")
  const formEditar = document.getElementById("formEditarVehiculo")

  // Formulario de agregar vehículo
  if (formAgregar) {
    formAgregar.addEventListener("submit", function (e) {
      e.preventDefault()
      if (validarFormulario(this)) {
        enviarFormulario(this)
      }
    })
  }

  // Formulario de editar vehículo
  if (formEditar) {
    formEditar.addEventListener("submit", function (e) {
      e.preventDefault()
      if (validarFormulario(this)) {
        enviarFormulario(this)
      }
    })
  }

  // Configurar previsualización de imagen para el formulario de agregar
  const inputFotoAgregar = document.getElementById("foto_vehiculo")
  if (inputFotoAgregar) {
    inputFotoAgregar.addEventListener("change", function () {
      previsualizarImagen(this)
    })
  }

  // Configurar previsualización de imagen para el formulario de editar
  const inputFotoEditar = document.getElementById("edit_foto_vehiculo")
  if (inputFotoEditar) {
    inputFotoEditar.addEventListener("change", function () {
      previsualizarImagen(this, "img_preview")
    })
  }
})

/**
 * Carga los datos de un vehículo en el modal de edición
 * Extrae información de la fila de la tabla y realiza una petición AJAX
 * para obtener datos completos del vehículo
 * 
 * @param {HTMLElement} fila - Elemento TR de la tabla que contiene los datos del vehículo
 */
function cargarDatosEdicion(fila) {
  // Obtener el ID del vehículo desde el atributo data-id de la fila
  const idVehiculo = fila.getAttribute("data-id")

  // Si no hay ID disponible, intentar obtenerlo mediante la placa
  if (!idVehiculo) {
    const placa = fila.querySelector(".placa-cell").textContent
    obtenerDatosVehiculo(placa)
    return
  }

  // Extraer datos visibles de la fila de la tabla
  const placa = fila.querySelector(".placa-cell").textContent
  const documento = fila.querySelector(".documento-cell").textContent
  const marca = fila.querySelector(".marca-cell").textContent
  const modelo = fila.querySelector(".modelo-cell").textContent
  const estado = fila.querySelector(".estado-cell").textContent

  // Obtener la imagen del vehículo si está disponible
  let imagenSrc = ""
  const imagenElement = fila.querySelector(".vehicle-image")
  if (imagenElement) {
    imagenSrc = imagenElement.getAttribute("src")
  }

  // Establecer valores básicos en el formulario de edición
  document.getElementById("edit_id_vehiculo").value = idVehiculo
  document.getElementById("edit_placa").value = placa

  // Seleccionar el documento correspondiente en el select
  const selectDocumento = document.getElementById("edit_documento")
  for (let i = 0; i < selectDocumento.options.length; i++) {
    if (selectDocumento.options[i].value === documento) {
      selectDocumento.selectedIndex = i
      break
    }
  }

  // Realizar petición AJAX para obtener datos completos del vehículo
  // Algunos campos no están visibles en la tabla y necesitan ser consultados
  fetch("obtener_vehiculo.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `placa=${encodeURIComponent(placa)}`,
  })
    .then((response) => response.json())
    .then((data) => {
      // Verificar si la sesión ha expirado y hay redirección
      if (data.redirect) {
        alert(data.message || 'Sesión expirada');
        window.location.href = data.redirect_url || '../../login/login.php';
        return;
      }
      
      if (data.success) {
        const vehiculo = data.vehiculo

        // Seleccionar la marca correspondiente en el select
        const selectMarca = document.getElementById("edit_marca")
        for (let i = 0; i < selectMarca.options.length; i++) {
          if (selectMarca.options[i].value == vehiculo.id_marca) {
            selectMarca.selectedIndex = i
            break
          }
        }

        // Seleccionar el modelo correspondiente en el select
        const selectModelo = document.getElementById("edit_modelo")
        for (let i = 0; i < selectModelo.options.length; i++) {
          if (selectModelo.options[i].value == vehiculo.modelo) {
            selectModelo.selectedIndex = i
            break
          }
        }

        // Seleccionar el estado correspondiente en el select
        const selectEstado = document.getElementById("edit_estado")
        for (let i = 0; i < selectEstado.options.length; i++) {
          if (selectEstado.options[i].value == vehiculo.id_estado) {
            selectEstado.selectedIndex = i
            break
          }
        }

        // Seleccionar el tipo de vehículo correspondiente en el select
        const selectTipo = document.getElementById("edit_tipo_vehiculo")
        for (let i = 0; i < selectTipo.options.length; i++) {
          if (selectTipo.options[i].value === vehiculo.tipo_vehiculo) {
            selectTipo.selectedIndex = i
            break
          }
        }

        // Establecer valores en los campos de texto
        document.getElementById("edit_color").value = vehiculo.color
        document.getElementById("edit_kilometraje").value = vehiculo.kilometraje_actual
        document.getElementById("edit_observaciones").value = vehiculo.observaciones

        // Configurar la previsualización de imagen
        const imgPreview = document.getElementById("img_preview")
        if (vehiculo.foto_vehiculo) {
          // Si el vehículo tiene foto, mostrarla y marcar el checkbox de mantener
          imgPreview.src = "../usuario/" + vehiculo.foto_vehiculo
          imgPreview.style.display = "block"
          document.getElementById("mantener_foto").checked = true
          document.getElementById("edit_foto_vehiculo").disabled = true
        } else {
          // Si no tiene foto, ocultar preview y permitir subir nueva
          imgPreview.style.display = "none"
          document.getElementById("mantener_foto").checked = false
          document.getElementById("edit_foto_vehiculo").disabled = false
        }

        // Mostrar el modal de edición
        const modalEditar = new bootstrap.Modal(document.getElementById("modalEditarVehiculo"))
        modalEditar.show()
      } else {
        alert("Error al cargar los datos del vehículo: " + data.message)
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Error al cargar los datos del vehículo")
    })
}

/**
 * Obtiene los datos completos de un vehículo mediante su placa
 * Utilizada cuando no se tiene el ID del vehículo disponible
 * 
 * @param {string} placa - Placa del vehículo a consultar
 */
function obtenerDatosVehiculo(placa) {
  fetch("obtener_vehiculo.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `placa=${encodeURIComponent(placa)}`,
  })
    .then((response) => response.json())
    .then((data) => {
      // Verificar si la sesión ha expirado
      if (data.redirect) {
        alert(data.message || 'Sesión expirada');
        window.location.href = data.redirect_url || '../../login/login.php';
        return;
      }
      
      if (data.success) {
        // Si se obtuvieron los datos correctamente, cargar el modal de edición
        cargarDatosEdicion(data.vehiculo)
      } else {
        alert("Error al obtener datos del vehículo: " + data.message)
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Error al obtener datos del vehículo")
    })
}

/**
 * Elimina un vehículo del sistema
 * Envía una petición AJAX al servidor para procesar la eliminación
 * 
 * @param {string|number} idVehiculo - ID del vehículo a eliminar
 */
function eliminarVehiculo(idVehiculo) {
  const formData = new FormData()
  formData.append("accion", "eliminar")
  formData.append("id_vehiculo", idVehiculo)

  fetch("procesar_vehiculo.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      // Verificar si la sesión ha expirado
      if (data.redirect) {
        alert(data.message || 'Sesión expirada');
        window.location.href = data.redirect_url || '../../login/login.php';
        return;
      }
      
      if (data.success) {
        alert(data.message)
        // Recargar la página para reflejar los cambios
        window.location.reload()
      } else {
        alert("Error al eliminar el vehículo: " + data.message)
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Error al eliminar el vehículo")
    })
}

/**
 * Valida los datos de un formulario antes del envío
 * Implementa validaciones personalizadas según las reglas de negocio
 * 
 * @param {HTMLFormElement} form - Formulario a validar
 * @returns {boolean} true si el formulario es válido, false en caso contrario
 */
function validarFormulario(form) {
  // TODO: Implementar validaciones personalizadas
  // Ejemplos de validaciones que se podrían agregar:
  // - Formato de placa según estándares colombianos
  // - Validación de kilometraje (debe ser número positivo)
  // - Validación de campos obligatorios
  // - Validación de formato de archivos de imagen
  
  return true // Por ahora, siempre retorna true
}

/**
 * Envía un formulario al servidor mediante AJAX
 * Maneja la respuesta y redirecciona o recarga según sea necesario
 * 
 * @param {HTMLFormElement} form - Formulario a enviar
 */
function enviarFormulario(form) {
  const formData = new FormData(form)

  fetch(form.action, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(data.message)
        // Redireccionar si se especifica, sino recargar la página
        if (data.redirect) {
          window.location.href = data.redirect
        } else {
          window.location.reload()
        }
      } else {
        alert("Error: " + data.message)
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Error al procesar el formulario")
    })
}

/**
 * Genera una previsualización de la imagen seleccionada
 * Permite al usuario ver la imagen antes de enviar el formulario
 * 
 * @param {HTMLInputElement} input - Input de tipo file que contiene la imagen
 * @param {string|null} previewId - ID del elemento img donde mostrar la preview (opcional)
 */
function previsualizarImagen(input, previewId = null) {
  // Verificar que se haya seleccionado un archivo
  if (input.files && input.files[0]) {
    const reader = new FileReader()

    reader.onload = (e) => {
      // Determinar el elemento de previsualización
      const imgPreview = previewId ? 
        document.getElementById(previewId) : 
        document.createElement("img")
      
      // Configurar la imagen de previsualización
      imgPreview.src = e.target.result
      imgPreview.style.display = "block"
      imgPreview.classList.add("img-thumbnail")
      imgPreview.style.maxHeight = "100px"

      // Si no se especificó un ID, crear un contenedor nuevo
      if (!previewId) {
        const previewContainer = document.createElement("div")
        previewContainer.classList.add("mt-2")
        previewContainer.appendChild(imgPreview)

        // Eliminar vista previa anterior si existe
        const oldPreview = input.parentNode.querySelector(".mt-2")
        if (oldPreview) {
          input.parentNode.removeChild(oldPreview)
        }

        // Agregar la nueva vista previa
        input.parentNode.appendChild(previewContainer)
      }
    }

    // Leer el archivo como Data URL para mostrar la imagen
    reader.readAsDataURL(input.files[0])
  }
}
