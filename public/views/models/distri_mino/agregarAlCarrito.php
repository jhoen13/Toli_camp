<?php
session_start();
if (!isset($_POST["id_producto"])) {
    return;
}
$doc = $_SESSION['document'];
$id_producto = $_POST["id_producto"];

require_once("../../../db/conexion.php");
$basedatos = new Database();
$conexion = $basedatos->conectar();

// $sentencia = $conexion->prepare("SELECT p.*, u.nombre AS nomvendedor FROM productos AS p JOIN usuarios AS u ON p.documento = u.documento WHERE (p.id_producto = ? OR p.id_producto = ?) AND p.documento = '$doc' LIMIT 1;");
// $sentencia->execute([$id_producto, $id_producto2, $id_producto, $id_producto2]);
// $producto = $sentencia->fetch(PDO::FETCH_OBJ);

$sentencia = $conexion->prepare("SELECT p.*, u.nombre AS nomvendedor FROM productos AS p JOIN usuarios AS u ON p.documento = u.documento WHERE p.id_producto = ? AND p.documento = ?");
$sentencia->execute([$id_producto, $doc]);
$producto = $sentencia->fetch(PDO::FETCH_OBJ);

# Si no existe, salimos y lo indicamos
if (!$producto) {
    header("Location: ./compras.php?status=4");
    exit;
}

# Si no hay existencia...
if ($producto->cantidad < 1) {
    header("Location: ./compras.php?status=5");
    exit;
}

# Buscar producto dentro del carrito
$indice = false;
for ($i = 0; $i < count($_SESSION["carrito"]); $i++) {
    if ($_SESSION["carrito"][$i]->id_producto === $id_producto) {
        $indice = $i;
        break;
    }
}

# Si no existe, lo agregamos como nuevo
if ($indice === false) {
    $producto->cantidad = 1;
    $producto->total = $producto->precio_ven;
    array_push($_SESSION["carrito"], $producto);
} else {
    # Si ya existe, se agrega la cantidad
    # Pero espera, tal vez ya no haya
    $cantidadExistente = $_SESSION["carrito"][$indice]->cantidad;
    // Verificar si al sumarle uno supera la cantidad disponible
    if ($cantidadExistente + 1 > $producto->cantidad) {
        header("Location: ./compras.php?status=5");
        exit;
    }
    $_SESSION["carrito"][$indice]->cantidad++;
    $_SESSION["carrito"][$indice]->total = $_SESSION["carrito"][$indice]->cantidad * $_SESSION["carrito"][$indice]->precio_ven;
}

header("Location: ./compras.php");
?>
