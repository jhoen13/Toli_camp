<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

if (isset($_GET['id_rol'])) {
    $id_rol= $_GET['id_rol'];

    // Realiza la eliminación del producto
    $deletesql = $conectar->prepare("DELETE FROM roles WHERE id_rol = :id_rol");
    $deletesql->bindParam(":id_rol", $id_rol, PDO::PARAM_INT);
    $deletesql->execute();

    echo '<script>alert("Rol Eliminado Exitosamente");</script>';
    echo '<script>window.location="index.php"</script>';
} else {
    echo '<script>alert("Error: ID de Rol no proporcionado");</script>';
    echo '<script>window.location="index.php"</script>';
}
?>
