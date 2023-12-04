<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

if (isset($_GET['id_embala'])) {
    $id_embala= $_GET['id_embala'];

    // Realiza la eliminación del producto
    $deletesql = $conectar->prepare("DELETE FROM embalaje WHERE id_embala = :id_embala");
    $deletesql->bindParam(":id_embala", $id_embala, PDO::PARAM_INT);
    $deletesql->execute();

    echo '<script>alert(" Embalaje Eliminado Exitosamente");</script>';
    echo '<script>window.location="index.php"</script>';
} else {
    echo '<script>alert("Error: ID de embalaje  no proporcionado");</script>';
    echo '<script>window.location="index.php"</script>';
}
?>
