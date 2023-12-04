<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

if (isset($_GET['documento'])) {
    $documento= $_GET['documento'];

    // Realiza la eliminación del producto
    $deletesql = $conectar->prepare("DELETE FROM usuarios WHERE documento = :documento");
    $deletesql->bindParam(":documento", $documento, PDO::PARAM_INT);
    $deletesql->execute();

    echo '<script>alert("usuario Eliminado Exitosamente");</script>';
    echo '<script>window.location="index.php"</script>';
} else {
    echo '<script>alert("Error: Documento  no proporcionado");</script>';
    echo '<script>window.location="index.php"</script>';
}
?>
