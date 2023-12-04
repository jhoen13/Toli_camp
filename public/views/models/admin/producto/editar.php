<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

$stmCategorias = $conectar->prepare("SELECT id_categoria, categoria FROM categoria");
$stmCategorias->execute();
$categorias = $stmCategorias->fetchAll(PDO::FETCH_ASSOC);

$stmDocumentos = $conectar->prepare("SELECT documento FROM usuarios WHERE id_rol IN ('1 administrador', '3 vendedor')");
$stmDocumentos->execute();
$documentos = $stmDocumentos->fetchAll(PDO::FETCH_ASSOC);

$stmembalaje = $conectar->prepare("SELECT id_embala, embalaje FROM embalaje");
$stmembalaje->execute();
$embalaje = $stmembalaje->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id_producto'])) {
    $id_producto = $_GET['id_producto'];
    $stmProducto = $conectar->prepare("SELECT * FROM productos WHERE id_producto = :id_producto");
    $stmProducto->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
    $stmProducto->execute();
    $producto = $stmProducto->fetch(PDO::FETCH_ASSOC);
}

if ((isset($_POST["registro"])) && ($_POST["registro"] == "formu")) {
    $id_producto = $_POST['id_producto'];
    $nom_produc = $_POST['nom_produc'];
    $descrip = $_POST['descrip'];
    $precio_compra = $_POST['precio_compra'];
    $disponibles = $_POST['disponibles'];
    $id_categoria = $_POST['id_categoria'];
    $cantidad = $_POST['cantidad'];
    $id_embala = $_POST['id_embala'];
    $precio_ven = $_POST['precio_ven'];
    $documento = $_POST['documento'];

     if (!empty($_FILES['foto']['name'])) {
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombre = "producto_" . time();
        $foto_nombre = $nombre . "." . $extension;
        $ruta_destino = "../../../../assets/img/img_produc/$foto_nombre";

        // Mueve el archivo a la ruta de destino
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
    } else {
        // Si no se envió un archivo, muestra un mensaje de error y redirige
        echo '<script>alert("No se ha seleccionado una imagen"); window.location="producto.php"</script>';
        exit(); // Detiene la ejecución del script
    }

    $updatesql = $conectar->prepare("UPDATE productos SET nom_produc = :nom_produc, descrip = :descrip, precio_compra = :precio_compra, disponibles = :disponibles, id_categoria = :id_categoria, cantidad = :cantidad, id_embala = :id_embala, foto = :foto, precio_ven = :precio_ven, documento = :documento WHERE id_producto = :id_producto");

    // Añadir parámetros a la consulta de actualización
    $updatesql->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
    $updatesql->bindParam(":nom_produc", $nom_produc, PDO::PARAM_STR);
    $updatesql->bindParam(":descrip", $descrip, PDO::PARAM_STR);
    $updatesql->bindParam(":precio_compra", $precio_compra, PDO::PARAM_STR);
    $updatesql->bindParam(":disponibles", $disponibles, PDO::PARAM_INT);
    $updatesql->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
    $updatesql->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
    $updatesql->bindParam(":id_embala", $id_embala, PDO::PARAM_INT);
    $updatesql->bindParam(":foto", $foto_nombre, PDO::PARAM_STR);
    $updatesql->bindParam(":precio_ven", $precio_ven, PDO::PARAM_STR);
    $updatesql->bindParam(":documento", $documento, PDO::PARAM_STR);

    // Ejecutar la consulta de actualización
    $updatesql->execute();

    echo '<script>alert("Actualización Exitosa");</script>';
    echo '<script> window.location="producto.php"</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../../../controller/img/icono.png" type="image/x-icon">
    <title>Formulario de creación de partidas</title>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden=true" times="" ></span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                <div class="modal-body">
                    <label for="nom_produc">Nombre Del Producto</label>
                    <input id="nom_produc" type="text" class="form-control" name="nom_produc" value="<?php echo $producto['nom_produc']; ?>" required>
                </div>
                <div class="modal-body">
                    <label for="descrip">Descripcion Del Producto</label>
                    <input id="descrip" type="text" class="form-control" name="descrip" value="<?php echo $producto['descrip']; ?>" required>
                </div>
                <div class="modal-body">
                    <label for="precio_compra">Precio</label>
                    <input id="precio_compra" type="number" class="form-control" name="precio_compra" value="<?php echo $producto['precio_compra']; ?>" required>
                </div>
                <div class="modal-body">
                    <label for="disponibles">Productos Disponibles</label>
                    <input id="disponibles" type="number" class="form-control" name="disponibles" value="<?php echo $producto['disponibles']; ?>" required>
                </div>
                <div class="modal-body">
                    <label for="categoria">Categoría De Los Productos</label>
                    <select id="categoria" class="form-control" name="id_categoria" required>
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria['id_categoria']; ?>" <?php if ($categoria['id_categoria'] == $producto['id_categoria']) echo "selected"; ?>><?php echo $categoria['categoria']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="modal-body">
                    <label for="cantidad">Cantidad Del Producto</label>
                    <input id="cantidad" type="number" class="form-control" name="cantidad" value="<?php echo $producto['cantidad']; ?>" required>
                </div>
                <div class="modal-body">
                    <label for="embalaje">Tipo de embalaje</label>
                    <select id="embalaje" class="form-control" name="id_embala" required>
                        <?php foreach ($embalaje as $embalajes) { ?>
                            <option value="<?php echo $embalajes['id_embala']; ?>" <?php if ($embalajes['id_embala'] == $producto['id_embala']) echo "selected"; ?>><?php echo $embalajes['embalaje']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
                </div>
                <div class="modal-body">
                    <label for="precio_ven">Precio venta</label>
                    <input id="precio_ven" type="number" class="form-control" name="precio_ven" value="<?php echo $producto['precio_ven']; ?>" required>
                </div>
                <div class="modal-body">
                    <label for="documento">Documento</label>
                    <select id="documento" class="form-control" name="documento" required>
                        <?php foreach ($documentos as $doc) { ?>
                            <option value="<?php echo $doc['documento']; ?>" <?php if ($doc['documento'] == $producto['documento']) echo "selected"; ?>><?php echo $doc['documento']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="submit" class="btn btn-success" value="Actualizar producto">
                <input type="hidden" name="registro" value="formu">
            </form>
            <div class="modal-footer"><a href="../../../../index.php" class="btn btn-primary btn-margin">Volver</a></div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
