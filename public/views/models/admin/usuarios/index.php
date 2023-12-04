<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conexion = $db->conectar();
session_start();

$stm = $conexion->prepare("SELECT * FROM usuarios");
$stm->execute();
$user = $stm->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['documento'])) {
    $txtid = $_GET['documento'];

    $stm = $conexion->prepare("DELETE FROM usuarios WHERE documento = :documento");
    $stm->bindParam(":documento", $txtid, PDO::PARAM_INT);
    $stm->execute();

    header("location: index.php");
}

$consulta1 = $conexion->prepare("SELECT * FROM genero");
$consulta1->execute();
$generos = $consulta1->fetchAll(PDO::FETCH_ASSOC);

// Crear el mapa de géneros
$generoMap = [];
foreach ($generos as $genero) {
    $generoMap[$genero['id_genero']] = $genero['genero'];
}

$consulta3 = $conexion->prepare("SELECT * FROM roles");
$consulta3->execute();
$roles = $consulta3->fetchAll(PDO::FETCH_ASSOC);

// Crear el mapa de roles
$rolesMap = [];
foreach ($roles as $rol) {
    $rolesMap[$rol['id_rol']] = $rol['tipo_rol'];
}

$consulta4 = $conexion->prepare("SELECT * FROM tipdocu");
$consulta4->execute();
$tiposDocumento = $consulta4->fetchAll(PDO::FETCH_ASSOC);

// Crear el mapa de tipos de documento
$documentoMap = [];
foreach ($tiposDocumento as $tipoDocumento) {
    $documentoMap[$tipoDocumento['id_tipdocu']] = $tipoDocumento['tipoocu'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de usuarios</title>
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
                <th scope="col">Documento</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Contraseña</th>
                <th scope="col">Correo electrónico</th>
                <th scope="col">Celular</th>
                <th scope="col">Dirección</th>
                <th scope="col">Género</th>
                <th scope="col">Rol</th>
                <th scope="col">Tipo de documento</th>
                <th colspan="2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user as $use) { ?>
                <tr>
                    <td><?php echo $use['documento']; ?></td>
                    <td><?php echo $use['nombre']; ?></td>
                    <td><?php echo $use['apellido']; ?></td>
                    <td><?php echo $use['password']; ?></td>
                    <td><?php echo $use['correo_electronico']; ?></td>
                    <td><?php echo $use['celular']; ?></td>
                    <td><?php echo $use['direccion']; ?></td>
                    <td>
                        <?php
                        if (isset($use['id_genero']) && isset($generoMap[$use['id_genero']])) {
                            echo $generoMap[$use['id_genero']];
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if (isset($use['id_rol']) && isset($rolesMap[$use['id_rol']])) {
                            echo $rolesMap[$use['id_rol']];
                        } else {
                            
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if (isset($use['id_tipdocu']) && isset($documentoMap[$use['id_tipdocu']])) {
                            echo $documentoMap[$use['id_tipdocu']];
                        } else {
                            
                        }
                        ?>
                    </td>
                    <td>
                        <a href="editar.php?documento=<?php echo $use['documento']; ?> " class="btn btn-success btn-margin">Editar</a>
                    </td>
                    <td>
                        <a href="eliminar.php?documento=<?php echo $use['documento']; ?>" class="btn btn-danger btn-margin">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="crear.php" class="btn btn-success btn-margin">Crear un usuario</a>
    <a href="../../../../views/models/admin/index-admin.php" class="btn btn-primary btn-margin">Volver</a>
</div>
</body>
</html>
