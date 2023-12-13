<?php
if (!isset($_POST["total"])) {
    exit;
}

session_start();
$total = $_POST["total"];

require_once("../../../db/conexion.php");
$basedatos = new Database();
$conexion = $basedatos->conectar();

date_default_timezone_set('America/Bogota');
$ahora = date("Y-m-d H:i:s");

$nombre = $_SESSION["document"];
$docu_cli = $_POST["cliente"]; // Verifica si estás recibiendo correctamente el valor del cliente

$sentencia = $conexion->prepare("INSERT INTO compras (fecha, docu_ven, docu_clien, total) VALUES (?, ?, ?, ?);");
$sentencia->execute([$ahora, $nombre, $docu_cli, $total]);

$sentencia = $conexion->prepare("SELECT id_compra FROM compras ORDER BY id_compra DESC LIMIT 1;");
$sentencia->execute();
$resultado = $sentencia->fetch(PDO::FETCH_OBJ);

$idCompra = $resultado === false ? 1 : $resultado->id_compra;

$conexion->beginTransaction();

$sentencia = $conexion->prepare("INSERT INTO det_compra (id_producto, id_compra, cantidad, sub_tot) VALUES (?, ?, ?, ?);");

$sentenciaExistencia = $conexion->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id_producto = ?;");

foreach ($_SESSION["carrito"] as $producto) {
    $sentencia->execute([$producto->id_producto, $idCompra, $producto->cantidad, $producto->total]);

    $sentenciaExistencia->execute([$producto->cantidad, $producto->id_producto]);
}

$conexion->commit();

unset($_SESSION["carrito"]);
$_SESSION["carrito"] = [];
header("Location: ./compras.php?status=1");
?>