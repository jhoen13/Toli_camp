<?php
session_start();

if (!isset($_POST["cantidad"])) {
    exit("No hay cantidad");
}

if (!isset($_POST["indice"])) {
    exit("No hay índice");
}

$cantidad = floatval($_POST["cantidad"]);
$indice = intval($_POST["indice"]);

if ($cantidad <= 0) {
    exit("La cantidad debe ser mayor que cero");
}

if (!isset($_SESSION["carrito"][$indice])) {
    exit("El índice del carrito no es válido");
}

if ($cantidad > $_SESSION["carrito"][$indice]->cantidad) {
    $cantidadDisponible = $_SESSION["carrito"][$indice]->cantidad;
    header("Location: ./compras.php?status=5&disponible=$cantidadDisponible");
    exit;
}

$_SESSION["carrito"][$indice]->cantidad = $cantidad;
$_SESSION["carrito"][$indice]->total = $_SESSION["carrito"][$indice]->cantidad * $_SESSION["carrito"][$indice]->precioVenta;

header("Location: ./compras.php");
?>