<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

if (isset($_GET['id_categoria'])) {
    $id_categoria= $_GET['id_categoria'];

    // Realiza la eliminación del producto
    $deletesql = $conectar->prepare("DELETE FROM categoria WHERE id_categoria = :id_categoria");
    $deletesql->bindParam(":id_categoria", $id_categoria , PDO::PARAM_INT);
    $deletesql->execute();

    echo '<script>alert("categoria  Eliminado Exitosamente");</script>';
    echo '<script>window.location="index.php"</script>';
} else {
    echo '<script>alert("Error: ID de categoria  no proporcionado");</script>';
    echo '<script>window.location="index.php"</script>';
}
?>
