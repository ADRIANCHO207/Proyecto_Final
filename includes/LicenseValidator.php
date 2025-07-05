<?php
class LicenseValidator {
    private $conexion;
    private $licencia_actual;
    
    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->cargarLicenciaActual();
    }
    
    private function cargarLicenciaActual() {
        try {
            $stmt = $this->conexion->prepare("
                SELECT * FROM sistema_licencias 
                WHERE estado = 'activa' 
                ORDER BY fecha_creacion DESC 
                LIMIT 1
            ");
            $stmt->execute();
            $this->licencia_actual = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $this->licencia_actual = null;
        }
    }
    
    public function validarLicencia() {
        if (!$this->licencia_actual) {
            return ['valida' => false, 'mensaje' => 'No hay licencia configurada'];
        }
        
        // Verificar si la licencia está vencida
        $fecha_actual = date('Y-m-d');
        if ($fecha_actual > $this->licencia_actual['fecha_vencimiento']) {
            $this->actualizarEstadoLicencia('vencida');
            return ['valida' => false, 'mensaje' => 'Licencia vencida'];
        }
        
        // Verificar si está suspendida
        if ($this->licencia_actual['estado'] === 'suspendida') {
            return ['valida' => false, 'mensaje' => 'Licencia suspendida'];
        }
        
        return ['valida' => true, 'mensaje' => 'Licencia válida'];
    }
    
    public function validarLimiteUsuarios() {
        if (!$this->licencia_actual) return false;
        
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM usuarios");
        $stmt->execute();
        $usuarios_actuales = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $usuarios_actuales < $this->licencia_actual['max_usuarios'];
    }
    
    public function validarLimiteVehiculos() {
        if (!$this->licencia_actual) return false;
        
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM vehiculos");
        $stmt->execute();
        $vehiculos_actuales = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $vehiculos_actuales < $this->licencia_actual['max_vehiculos'];
    }
    
    public function obtenerInfoLicencia() {
        return $this->licencia_actual;
    }
    
    public function obtenerEstadisticasUso() {
        if (!$this->licencia_actual) return null;
        
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM usuarios");
        $stmt->execute();
        $usuarios_actuales = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM vehiculos");
        $stmt->execute();
        $vehiculos_actuales = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return [
            'usuarios' => [
                'actuales' => $usuarios_actuales,
                'limite' => $this->licencia_actual['max_usuarios'],
                'porcentaje' => ($usuarios_actuales / $this->licencia_actual['max_usuarios']) * 100
            ],
            'vehiculos' => [
                'actuales' => $vehiculos_actuales,
                'limite' => $this->licencia_actual['max_vehiculos'],
                'porcentaje' => ($vehiculos_actuales / $this->licencia_actual['max_vehiculos']) * 100
            ]
        ];
    }
    
    private function actualizarEstadoLicencia($nuevo_estado) {
        try {
            $stmt = $this->conexion->prepare("
                UPDATE sistema_licencias 
                SET estado = ? 
                WHERE id = ?
            ");
            $stmt->execute([$nuevo_estado, $this->licencia_actual['id']]);
        } catch (Exception $e) {
            error_log("Error actualizando estado de licencia: " . $e->getMessage());
        }
    }
    
    public function diasRestantesLicencia() {
        if (!$this->licencia_actual) return 0;
        
        $fecha_actual = new DateTime();
        $fecha_vencimiento = new DateTime($this->licencia_actual['fecha_vencimiento']);
        $diferencia = $fecha_actual->diff($fecha_vencimiento);
        
        return $diferencia->invert ? 0 : $diferencia->days;
    }
}
?>