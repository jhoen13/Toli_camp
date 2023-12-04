<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conexion = $db->conectar();
session_start();
?>

<?php
$stm = $conexion->prepare("SELECT * FROM categoria");
$stm->execute();
$categoria = $stm->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
if (isset($_GET['id_categoria'])) {
    $txtid = $_GET['id_categoria'];

    $stm = $conexion->prepare("DELETE FROM categoria WHERE id_categoria = :id_categoria");
    $stm->bindParam(":id_categoria", $txtid, PDO::PARAM_INT);
    $stm->execute();

    // Redirige después de realizar la eliminación
    header("location: index.php");
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de la categorias </title>
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
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID categoria </th>
                <th scope="col">Categoria del producto </th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categoria as $catego) { ?>
                <tr>
                    <td scope="row"><?php echo $catego['id_categoria']; ?></td>
                    <td><?php echo $catego['categoria']; ?></td>
                    <td>
                        <a href="editar.php?id_categoria=<?php echo $catego['id_categoria']; ?> " class="btn btn-success">Editar</a>
                        <a href="index.php?id_categoria=<?php echo $catego['id_categoria']; ?>" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        
    </table>
    <a href="crear.php" class="btn btn-success btn-margin">Crear una categoria</a>
    <a href="../../../../views/models/admin/index-admin.php" class="btn btn-primary btn-margin">Volver</a></div>

</body>
</html>
