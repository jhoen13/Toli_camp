<?php
require_once("../../../../db/conexion.php");
$bd = new Database();
$conexion = $bd->conectar();

// viene del name del formulario al activar usuario
$inac = $_GET['inactivar'];

$con = $conexion->prepare("UPDATE usuarios SET id_estado = 2 WHERE documento = :inactivo");
$con->bindParam(":inactivo", $inac);
$con->execute();
$inactivo = $con->rowCount(); // Usar rowCount para verificar si se realizó la actualización

if ($inactivo == 1) {
    echo '<script>alert ("Bloqueo exitoso, gracias");</script>';
    echo '<script>window.location="./index.php"</script>';

    exit();
} else {
    echo "No se pudo bloquear el usuario, lo sentimos :(";
}
?>