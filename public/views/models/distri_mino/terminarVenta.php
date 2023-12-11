<?php
if(!isset($_POST["total"])) exit;

session_start();
$total = $_POST["total"];

require_once("../../../db/conexion.php");
$basedatos = new Database();
$conexion = $basedatos->conectar();

date_default_timezone_set('America/Bogota');
$ahora = date("Y-m-d H:i:s");

$nombre = $_SESSION["document"];
if ($_POST["cliente"] == ""){}
$docu_cli = $_POST["cliente"];

// $sentencia = $conexion->prepare("INSERT INTO ventas(vendedor, doc_cliente, fecha, total) VALUES (?, ?, ?, ?);");
// $sentencia->execute([$nombre, $docu_cli, $ahora, $total]);

$sentencia = $conexion->prepare("INSERT INTO compras (fecha, docu_ven, docu_clien, total) VALUES (?, ?, ?, ?);");
$sentencia->execute([$ahora, $nombre, $docu_cli, $total]);

// $sentencia = $conexion->prepare("SELECT id FROM ventas ORDER BY id DESC LIMIT 1;");
// $sentencia->execute();
// $resultado = $sentencia->fetch(PDO::FETCH_OBJ);

$sentencia = $conexion->prepare("SELECT id_compra FROM compras ORDER BY id_compra DESC LIMIT 1;");
$sentencia->execute();
$resultado = $sentencia->fetch(PDO::FETCH_OBJ);

$idCompra = $resultado === false ? 1 : $resultado->id_compra;

$conexion->beginTransaction();
$sentencia = $conexion->prepare("INSERT INTO productos_vendidos(id_producto, id_venta, cantidad) VALUES (?, ?, ?);");

$sentenciaExistencia = $conexion->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?;");
foreach ($_SESSION["carrito"] as $producto) {   
    $total += $producto->total;
    $sentencia->execute([$producto->id_compra, $idCompra, $producto->cantidad]);

    $sentenciaExistencia->execute([$producto->cantidad, $producto->id_compra]);
}
$conexion->commit();

unset($_SESSION["carrito"]);
$_SESSION["carrito"] = [];
header("Location: ./vender.php?status=1");
?> 