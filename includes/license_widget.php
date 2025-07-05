<?php
require_once __DIR__ . '/LicenseValidator.php';
require_once __DIR__ . '/../conecct/conex.php';

function mostrarWidgetLicencia() {
    $database = new Database();
    $conexion = $database->conectar();
    
    $validator = new LicenseValidator($conexion);
    $info_licencia = $validator->obtenerInfoLicencia();
    $estadisticas = $validator->obtenerEstadisticasUso();
    $dias_restantes = $validator->diasRestantesLicencia();
    
    if (!$info_licencia) return '';
    
    $color_alerta = $dias_restantes <= 30 ? 'danger' : ($dias_restantes <= 60 ? 'warning' : 'success');
    
    return '
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h6 class="card-title"><i class="fas fa-certificate me-2"></i>Estado de Licencia</h6>
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Usuarios</small>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar" style="width: ' . $estadisticas['usuarios']['porcentaje'] . '%"></div>
                    </div>
                    <small>' . $estadisticas['usuarios']['actuales'] . '/' . $estadisticas['usuarios']['limite'] . '</small>
                </div>
                <div class="col-6">
                    <small class="text-muted">Vehículos</small>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar" style="width: ' . $estadisticas['vehiculos']['porcentaje'] . '%"></div>
                    </div>
                    <small>' . $estadisticas['vehiculos']['actuales'] . '/' . $estadisticas['vehiculos']['limite'] . '</small>
                </div>
            </div>
            <hr>
            <small class="text-' . $color_alerta . '">
                <i class="fas fa-clock me-1"></i>' . $dias_restantes . ' días restantes
            </small>
        </div>
    </div>';
}
?>