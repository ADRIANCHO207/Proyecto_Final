<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consulta de Multas - SIMIT</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      padding: 40px;
    }
    .container {
      max-width: 500px;
      margin: auto;
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    label, input, select, button {
      display: block;
      width: 100%;
      margin-bottom: 15px;
    }
    button {
      padding: 10px;
      background-color: #2196f3;
      color: white;
      border: none;
      border-radius: 5px;
    }
    #resultado {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Consulta de Multas</h2>
    <label for="placa">Placa del vehículo:</label>
    <input type="text" id="placa" placeholder="Ej: ABC123">

    <label for="tipoDoc">Tipo de documento:</label>
    <select id="tipoDoc">
      <option value="CC">Cédula de Ciudadanía</option>
      <option value="CE">Cédula de Extranjería</option>
      <option value="NIT">NIT</option>
    </select>

    <label for="numeroDoc">Número de documento:</label>
    <input type="text" id="numeroDoc" placeholder="Ej: 123456789">

    <button onclick="consultarMultas()">Consultar</button>

    <div id="resultado"></div>
  </div>

  <script>
    async function consultarMultas() {
      const placa = document.getElementById("placa").value.toUpperCase();
      const tipoDoc = document.getElementById("tipoDoc").value;
      const numeroDoc = document.getElementById("numeroDoc").value;

      const resultado = document.getElementById("resultado");
      resultado.innerHTML = "Consultando...";

      try {
        // ⚠️ Simulación: Aquí iría el endpoint real de la API del SIMIT o RUNT
        const response = await fetch("https://api.simit.gov.co/consulta-multas", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer TU_API_KEY_AQUI"
          },
          body: JSON.stringify({
            placa: placa,
            tipo_documento: tipoDoc,
            numero_documento: numeroDoc
          })
        });

        if (!response.ok) throw new Error("Error en la consulta");

        const data = await response.json();

        // Supongamos que la respuesta tiene un array de multas
        if (data.multas && data.multas.length > 0) {
          let html = "<h3>Multas encontradas:</h3><ul>";
          data.multas.forEach(multa => {
            html += `<li><strong>${multa.fecha}</strong>: ${multa.valor} - ${multa.estado}</li>`;
          });
          html += "</ul>";
          resultado.innerHTML = html;
        } else {
          resultado.innerHTML = "<p>No se encontraron multas para este vehículo.</p>";
        }

      } catch (error) {
        console.error(error);
        resultado.innerHTML = `<p style="color:red;">Error al consultar multas. Intenta más tarde.</p>`;
      }
    }
  </script>
</body>
</html>
