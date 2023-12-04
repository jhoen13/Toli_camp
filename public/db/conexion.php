<?php
class database
{
    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "toli_camp";
    private $chasrset = "utf8";

    function conectar()
    {
        try{
            $conexion= "mysql:host=". $this -> hostname ."; dbname=". $this->database. "; chasret=" . $this ->chasrset ;
            $option = [
                PDO::ATTR_ERRMODE => PDO ::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $pdo =new PDO($conexion,$this->username, $this->password,$option);

            return $pdo;

        }
        catch(PDOException $e)
        {
            echo'Error de conexion:'.$e->getMessage();
            exit;
        }
    }  
}
?>