<?php

class Database
{
    private $hostname;
    private $database;
    private $username;
    private $password;
    private $charset = "utf8";

    public function __construct()
    {
        if (
            strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
            strpos($_SERVER['DOCUMENT_ROOT'], 'htdocs') !== false
        ) {
            // 🔧 Local (XAMPP)
            $this->hostname = 'localhost';
            $this->database = 'proyecto_flota'; // nombre local
            $this->username = 'root';
            $this->password = '';
        } else {
            // 🌐 Producción (Hostinger u otro)
            $this->hostname = 'localhost';
            $this->database = 'u148394603_flota_agc';
            $this->username = 'u148394603_flota_agc';
            $this->password = 'Faridgomez04';
        }
    }

    public function conectar()
    {
        try {
            $conexion = "mysql:host=" . $this->hostname . "; dbname=" . $this->database . "; charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $pdo = new PDO($conexion, $this->username, $this->password, $options);

            // Zona horaria general (funciona en ambos entornos)
            $pdo->exec("SET time_zone = '-05:00'");

            return $pdo;
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
            exit;
        }
    }
}

?>