document.addEventListener("DOMContentLoaded", () => {
  // Inicializar los modales de Bootstrap
  const modales = document.querySelectorAll(".modal")
  modales.forEach((modal) => {
    new bootstrap.Modal(modal)
  })

  // Configurar el botón de agregar vehículo
  const btnAgregar = document.getElementById("btnAgregarVehiculo")
  if (btnAgregar) {
    btnAgregar.addEventListener("click", () => {
      const modalAgregar = new bootstrap.Modal(document.getElementById("modalAgregarVehiculo"))
      modalAgregar.show()
    })
  }

  // Configurar los botones de editar
  const btnsEditar = document.querySelectorAll(".action-icon.edit")
  btnsEditar.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault()
      const fila = this.closest("tr")
      cargarDatosEdicion(fila)
    })
  })

  // Configurar los botones de eliminar
  const btnsEliminar = document.querySelectorAll(".action-icon.delete")
  btnsEliminar.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault()
      const fila = this.closest("tr")
      const placa = fila.querySelector(".placa-cell").textContent

      if (confirm(`¿Está seguro que desea eliminar el vehículo con placa ${placa}?`)) {
        const idVehiculo = fila.getAttribute("data-id")
        eliminarVehiculo(idVehiculo)
      }
    })
  })

  // Configurar el checkbox de mantener foto
  const checkMantenerFoto = document.getElementById("mantener_foto")
  const inputFoto = document.getElementById("edit_foto_vehiculo")

  if (checkMantenerFoto && inputFoto) {
    checkMantenerFoto.addEventListener("change", function () {
      inputFoto.disabled = this.checked
    })
  }

  // Configurar validación de formularios
  const formAgregar = document.getElementById("formAgregarVehiculo")
  const formEditar = document.getElementById("formEditarVehiculo")

  if (formAgregar) {
    formAgregar.addEventListener("submit", function (e) {
      e.preventDefault()
      if (validarFormulario(this)) {
        enviarFormulario(this)
      }
    })
  }

  if (formEditar) {
    formEditar.addEventListener("submit", function (e) {
      e.preventDefault()
      if (validarFormulario(this)) {
        enviarFormulario(this)
      }
    })
  }

  // Previsualización de imagen
  const inputFotoAgregar = document.getElementById("foto_vehiculo")
  if (inputFotoAgregar) {
    inputFotoAgregar.addEventListener("change", function () {
      previsualizarImagen(this)
    })
  }

  const inputFotoEditar = document.getElementById("edit_foto_vehiculo")
  if (inputFotoEditar) {
    inputFotoEditar.addEventListener("change", function () {
      previsualizarImagen(this, "img_preview")
    })
  }
})

// Función para cargar datos en el modal de edición
function cargarDatosEdicion(fila) {
  // Obtener el ID del vehículo (asumiendo que está en un atributo data-id)
  const idVehiculo = fila.getAttribute("data-id")

  // Si no hay ID, intentar obtenerlo mediante una petición AJAX
  if (!idVehiculo) {
    const placa = fila.querySelector(".placa-cell").textContent
    obtenerDatosVehiculo(placa)
    return
  }

  // Obtener datos de la fila
  const placa = fila.querySelector(".placa-cell").textContent
  const documento = fila.querySelector(".documento-cell").textContent
  const marca = fila.querySelector(".marca-cell").textContent
  const modelo = fila.querySelector(".modelo-cell").textContent
  const estado = fila.querySelector(".estado-cell").textContent

  // Obtener la imagen si existe
  let imagenSrc = ""
  const imagenElement = fila.querySelector(".vehicle-image")
  if (imagenElement) {
    imagenSrc = imagenElement.getAttribute("src")
  }

  // Establecer valores en el formulario
  document.getElementById("edit_id_vehiculo").value = idVehiculo
  document.getElementById("edit_placa").value = placa

  // Seleccionar el documento en el select
  const selectDocumento = document.getElementById("edit_documento")
  for (let i = 0; i < selectDocumento.options.length; i++) {
    if (selectDocumento.options[i].value === documento) {
      selectDocumento.selectedIndex = i
      break
    }
  }

  // Para los demás campos, necesitamos hacer una petición AJAX para obtener todos los datos
  // ya que algunos no están visibles en la tabla
  fetch("obtener_vehiculo.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `placa=${encodeURIComponent(placa)}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const vehiculo = data.vehiculo

        // Seleccionar la marca en el select
        const selectMarca = document.getElementById("edit_marca")
        for (let i = 0; i < selectMarca.options.length; i++) {
          if (selectMarca.options[i].value == vehiculo.id_marca) {
            selectMarca.selectedIndex = i
            break
          }
        }

        // Seleccionar el modelo en el select
        const selectModelo = document.getElementById("edit_modelo")
        for (let i = 0; i < selectModelo.options.length; i++) {
          if (selectModelo.options[i].value == vehiculo.modelo) {
            selectModelo.selectedIndex = i
            break
          }
        }

        // Seleccionar el estado en el select
        const selectEstado = document.getElementById("edit_estado")
        for (let i = 0; i < selectEstado.options.length; i++) {
          if (selectEstado.options[i].value == vehiculo.id_estado) {
            selectEstado.selectedIndex = i
            break
          }
        }

        // Seleccionar el tipo de vehículo en el select
        const selectTipo = document.getElementById("edit_tipo_vehiculo")
        for (let i = 0; i < selectTipo.options.length; i++) {
          if (selectTipo.options[i].value === vehiculo.tipo_vehiculo) {
            selectTipo.selectedIndex = i
            break
          }
        }

        // Establecer otros valores
        document.getElementById("edit_color").value = vehiculo.color
        document.getElementById("edit_kilometraje").value = vehiculo.kilometraje_actual
        document.getElementById("edit_observaciones").value = vehiculo.observaciones

        // Mostrar la imagen si existe
        const imgPreview = document.getElementById("img_preview")
        if (vehiculo.foto_vehiculo) {
          imgPreview.src = "../usuario/" + vehiculo.foto_vehiculo
          imgPreview.style.display = "block"
          document.getElementById("mantener_foto").checked = true
          document.getElementById("edit_foto_vehiculo").disabled = true
        } else {
          imgPreview.style.display = "none"
          document.getElementById("mantener_foto").checked = false
          document.getElementById("edit_foto_vehiculo").disabled = false
        }

        // Mostrar el modal
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

// Función para obtener datos de un vehículo por placa
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
      if (data.success) {
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

// Función para eliminar un vehículo
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
      if (data.success) {
        alert(data.message)
        // Recargar la página para ver los cambios
        window.location.reload()
      } else {
        alert("Error: " + data.message)
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("Error al eliminar el vehículo")
    })
}

// Función para validar formulario
function validarFormulario(form) {
  // Implementar validaciones personalizadas aquí
  // Por ejemplo, validar formato de placa, etc.
  return true // Por ahora, siempre retorna true
}

// Función para enviar formulario
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

// Función para previsualizar imagen
function previsualizarImagen(input, previewId = null) {
  if (input.files && input.files[0]) {
    const reader = new FileReader()

    reader.onload = (e) => {
      const imgPreview = previewId ? document.getElementById(previewId) : document.createElement("img")
      imgPreview.src = e.target.result
      imgPreview.style.display = "block"
      imgPreview.classList.add("img-thumbnail")
      imgPreview.style.maxHeight = "100px"

      if (!previewId) {
        const previewContainer = document.createElement("div")
        previewContainer.classList.add("mt-2")
        previewContainer.appendChild(imgPreview)

        // Eliminar vista previa anterior si existe
        const oldPreview = input.parentNode.querySelector(".mt-2")
        if (oldPreview) {
          input.parentNode.removeChild(oldPreview)
        }

        input.parentNode.appendChild(previewContainer)
      }
    }

    reader.readAsDataURL(input.files[0])
  }
}
