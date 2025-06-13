<?php
class Database {
    private $hostname = "localhost";
    private $database = "proyecto_flota";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";

    function conectar() {
        try {
            $conexion = "mysql:host=" . $this->hostname . ";dbname=" . $this->database . ";charset=" . $this->charset;
            error_log("Intentando conectar a: $conexion");
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $pdo = new PDO($conexion, $this->username, $this->password, $options);
            error_log("Conexión exitosa a " . $this->database);
            return $pdo;
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }
}
?>