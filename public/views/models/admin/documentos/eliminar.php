<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

if (isset($_GET['id_tipdocu'])) {
    $id_tipdocu= $_GET['id_tipdocu'];

    // Realiza la eliminación del producto
    $deletesql = $conectar->prepare("DELETE FROM tipdocu WHERE id_tipdocu = :id_tipdocu");
    $deletesql->bindParam(":id_tipdocu", $id_tipdocu, PDO::PARAM_INT);
    $deletesql->execute();

    echo '<script>alert("Identificacion  Eliminada Exitosamente");</script>';
    echo '<script>window.location="index.php"</script>';
} else {
    echo '<script>alert("Error: ID de Identificacion  no proporcionado");</script>';
    echo '<script>window.location="index.php"</script>';
}
?>
