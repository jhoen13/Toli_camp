<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conexion = $db->conectar();
session_start();
?>

<?php
$stm = $conexion->prepare("SELECT * FROM genero");
$stm->execute();
$genero = $stm->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de generos</title>
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
                <th scope="col">ID genero</th>
                <th scope="col">Genero</th>
                <th colspan="2">Acciones</th>
                
        </thead>
        <tbody>
            <?php foreach ($genero as $gen) { ?>
                <tr>
                    <td scope="row"><?php echo $gen['id_genero']; ?></td>
                    <td><?php echo $gen['genero']; ?></td>
                    <td>
                        <a href="editar.php?id_genero=<?php echo $gen['id_genero']; ?>" class="btn btn-success btn-margin">Editar</a>
                    </td>
                    <td>
                        <a href="eliminar.php?id_genero=<?php echo $gen['id_genero']; ?>" class="btn btn-danger btn-margin">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        
    </table>
    <a href="crear.php" class="btn btn-success btn-margin">Crear un genero</a>
    <a href="../../../../views/models/admin/index-admin.php" class="btn btn-primary btn-margin">Volver</a>
</div>

</body>
</html>
