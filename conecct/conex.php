<?php

class Database
{
    private $hostname = "localhost";
    private $database = "proyecto_flota";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";

//        private $hostname = "localhost";
//     private $database = "    u148394603_flota
// ";
//     private $username = "    u148394603_flota
// ";
//     private $password = "Faridgomez04";
//     private $charset = "utf8";




     function conectar()
     {
         try{
         $conexion = "mysql:host=". $this->hostname . "; dbname=" . $this->database . "; charset=" . $this->charset;


         $option = [
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES=>false
         ];
        
         $spo = new PDO($conexion, $this->username, $this->password, $option);


         return $spo;
        }
        catch(PDOException $e)
        {
            echo "error de conexion".$e->getMessage();
            exit;
        }
     
    }
}   

?>