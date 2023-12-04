<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

if (isset($_GET['id_genero'])) {
    $id_genero= $_GET['id_genero'];

    // Realiza la eliminación del producto
    $deletesql = $conectar->prepare("DELETE FROM genero WHERE id_genero = :id_genero");
    $deletesql->bindParam(":id_genero", $id_genero, PDO::PARAM_INT);
    $deletesql->execute();

    echo '<script>alert(" genero Eliminado Exitosamente");</script>';
    echo '<script>window.location="index.php"</script>';
} else {
    echo '<script>alert("Error: ID de genero  no proporcionado");</script>';
    echo '<script>window.location="index.php"</script>';
}
?>
