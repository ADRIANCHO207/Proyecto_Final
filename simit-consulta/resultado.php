<?php
$data = json_decode(file_get_contents("respuesta.json"), true);

echo "<h2>Resultado de la consulta:</h2>";

if (isset($data['data']['multas']) && is_array($data['data']['multas']) && count($data['data']['multas']) > 0) {
    foreach ($data['data']['multas'] as $multa) {
        echo "<strong>Placa:</strong> " . htmlspecialchars($multa['placa']) . "<br>";
        echo "<strong>Nombre:</strong> " . htmlspecialchars($multa['infractor']['nombre']) . " " . htmlspecialchars($multa['infractor']['apellido']) . "<br>";
        echo "<strong>Valor:</strong> $" . number_format($multa['valor'], 0, ',', '.') . "<br>";
        echo "<strong>Infracción:</strong> " . htmlspecialchars($multa['infracciones'][0]['descripcionInfraccion']) . "<br>";
        echo "<strong>Departamento:</strong> " . htmlspecialchars($multa['departamento']) . "<br>";
        echo "<strong>Estado de Cartera:</strong> " . htmlspecialchars($multa['estadoCartera']) . "<br>";
        echo "<strong>Fecha de Comparendo:</strong> " . htmlspecialchars($multa['fechaComparendo']) . "<br>";
        echo "<strong>Valor Total a Pagar:</strong> $" . number_format($multa['valorPagar'], 0, ',', '.') . "<br><hr>";
    }
} else {
    echo "No hay multas asociadas.";
}
