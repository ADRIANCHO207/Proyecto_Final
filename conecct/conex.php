<?php
<<<<<<< HEAD

=======
// conexion base de datos por PDO
>>>>>>> 3919379551deb4b47f9332d7afefd7d09b4a114f

class Database
{
    private $hostname = "localhost";
<<<<<<< HEAD
    private $database = "proyecto_flota";
=======
    private $database = "flota";
>>>>>>> 3919379551deb4b47f9332d7afefd7d09b4a114f
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