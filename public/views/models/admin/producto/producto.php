<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conexion = $db->conectar();
session_start();

$stm = $conexion->prepare("SELECT * FROM productos");
$stm->execute();
$productos = $stm->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id_producto'])) {
    $txtid = $_GET['id_producto'];

    $stm = $conexion->prepare("DELETE FROM productos WHERE id_producto = :id_producto");
    $stm->bindParam(":id_producto", $txtid, PDO::PARAM_INT);
    $stm->execute();

    header("location: index.php");
}

// Realiza una consulta para obtener los nombres de las categorías
$stmCategorias = $conexion->prepare("SELECT id_categoria, categoria FROM categoria");
$stmCategorias->execute();
$categorias = $stmCategorias->fetchAll(PDO::FETCH_ASSOC);
$categoriasMap = [];
foreach ($categorias as $categoria) {
    $categoriasMap[$categoria['id_categoria']] = $categoria['categoria'];
}

// Nueva consulta para obtener los valores de "embalaje"
$stmEmbalaje = $conexion->prepare("SELECT id_embala, embalaje FROM embalaje");
$stmEmbalaje->execute();
$embalajes = $stmEmbalaje->fetchAll(PDO::FETCH_ASSOC);
$embalajesMap = [];
foreach ($embalajes as $embalaje) {
    $embalajesMap[$embalaje['id_embala']] = $embalaje['embalaje'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de productos</title>
    <!-- Agrega los estilos de Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../../../controller/img/icono.png" type="image/x-icon">
    <style>
        /* Estilos personalizados para centrar la tabla */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100;
            margin: 0;
        }

        /* Estilos para los botones */
        .btn-margin {
            margin: 10px;
        }
    </style>
</head>

<body>
    <div class="table-responsive"><br>
        <a href="../../../../views/models/admin/index-admin.php" class="btn btn-warning btn-margin">Volver</a>
        <a href="crear.php" class="btn btn-success btn-margin">Crear un Producto</a>
        <a href="../archivo_csv/index.php" class="btn btn-primary btn-margin">Archivo CSV</a>
        <br>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID producto</th>
                    <th scope="col">Nombre del Producto</th>
                    <th scope="col">Descripcion</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Disponibles</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Embalaje</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Precio venta</th>
                    <th scope="col">Documento</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto) { ?>
                    <tr>
                        <td scope="row"><?php echo $producto['id_producto']; ?></td>
                        <td><?php echo $producto['nom_produc']; ?></td>
                        <td scope="row"><?php echo $producto['descrip']; ?></td>
                        <td><?php echo $producto['precio_compra']; ?></td>
                        <td scope="row"><?php echo $producto['disponibles']; ?></td>
                        <td><?php echo $categoriasMap[$producto['id_categoria']]; ?></td>
                        <td scope="row"><?php echo $producto['cantidad']; ?></td>
                        <td scope="row"><?php echo $embalajesMap[$producto['id_embala']]; ?></td>
                        <td><img src="../../../../assets/img/img_produc/<?= $producto["foto"] ?>" alt="" style="width: 75px;"></td>
                        <td scope="row"><?php echo $producto['precio_ven']; ?></td>
                        <td scope="row"><?php echo $producto['documento']; ?></td>
                        <td>
                            <a href="editar.php?id_producto=<?php echo $producto['id_producto']; ?> " class="btn btn-success btn-margin">Editar</a>
                        </td>
                        <td>
                            <a href="eliminar.php?id_producto=<?php echo $producto['id_producto']; ?>" class="btn btn-danger btn-margin">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>

</body>

</html>