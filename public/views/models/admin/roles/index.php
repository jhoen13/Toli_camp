<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conexion = $db->conectar();
session_start();
?>

<?php
$stm = $conexion->prepare("SELECT * FROM roles");
$stm->execute();
$rol = $stm->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
if (isset($_GET['id_rol'])) {
    $txtid = $_GET['id_rol'];

    $stm = $conexion->prepare("DELETE FROM roles WHERE id_rol = :id_rol");
    $stm->bindParam(":id_rol", $txtid, PDO::PARAM_INT);
    $stm->execute();

    header("location: index.php");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de roles</title>
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
                <th scope="col">ID rol</th>
                <th scope="col">Roles</th>
                <th colspan="2">Acciones</th>
                
        </thead>
        <tbody>
            <?php foreach ($rol as $roles) { ?>
                <tr>
                    <td scope="row"><?php echo $roles['id_rol']; ?></td>
                    <td><?php echo $roles['tipo_rol']; ?></td>
                    <td>
                        <a href="eliminar.php?id_rol=<?php echo $roles['id_rol']; ?>" class="btn btn-danger btn-margin">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        
    </table>
    <a href="crear.php" class="btn btn-success btn-margin">Crear un Rol</a>
    <a href="../../../../views/models/admin/index-admin.php" class="btn btn-primary btn-margin">Volver</a>
</div>

</body>
</html>
