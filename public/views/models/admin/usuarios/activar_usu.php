<?php
require_once("../../../../db/conexion.php");
$bd = new Database();
$conexion = $bd->conectar();

// viene del name del formulario al activar usuario
$act = $_GET['activar'];

$con = $conexion->prepare("UPDATE usuarios SET id_estado = 1 WHERE documento = :activo");
$con->bindParam(":activo", $act);
$con->execute();
$activar = $con->rowCount(); // Usar rowCount para verificar si se realizó la actualización

if ($activar == 1) {
    echo '<script>alert ("Activacion exitosa, gracias");</script>';
    echo '<script>window.location="./index.php"</script>';

    exit();
} else {
    echo "No se pudo activar el usuario, lo sentimos :(";
}
?>