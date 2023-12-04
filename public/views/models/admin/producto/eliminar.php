<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

if (isset($_GET['id_producto'])) {
    $id_producto = $_GET['id_producto'];

    // Realiza la eliminación del producto
    $deletesql = $conectar->prepare("DELETE FROM productos WHERE id_producto = :id_producto");
    $deletesql->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
    $deletesql->execute();

    echo '<script>alert("Producto Eliminado Exitosamente");</script>';
    echo '<script>window.location="producto.php"</script>';
} else {
    echo '<script>alert("Error: ID de producto no proporcionado");</script>';
    echo '<script>window.location="producto.php"</script>';
}
?>
