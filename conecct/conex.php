<?php
// conexion base de datos por PDO

class Database
{
    private $hostname = "localhost";
    private $database = "flota";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";



     function conectar()
     {
         try{
         $conexion = "mysql:host=". $this->hostname . "; dbname=" . $this->database . "; charset=" . $this->charset;

        //  if (!$conexion){
        //     echo ("No hubo conexion con la base de datos");  
        //     }
        //     else{
        //     echo (" Tenemos conexion ");
        //     }
        



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