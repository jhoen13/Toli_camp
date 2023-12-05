<?php
require_once("../../../../db/conexion.php");
$db = new Database();
$con = $db->conectar();

// Obtener el número de documento del usuario a actualizar (puedes pasarlo como parámetro en la URL o de alguna otra manera)
$documento_a_actualizar = $_GET['documento'];

// Consulta para obtener los datos del usuario a actualizar
$consultaUsuario = $con->prepare("SELECT * FROM usuarios WHERE documento = :documento");
$consultaUsuario->bindParam(':documento', $documento_a_actualizar);
$consultaUsuario->execute();
$datosUsuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC);

// SE REALIZAN CONSULTAS PARA LOS SELECT
$consultaGenero = $con->prepare("SELECT * FROM genero");
$consultaGenero->execute();
$generos = $consultaGenero->fetchAll();

$consultaRoles = $con->prepare("SELECT * FROM roles WHERE id_rol ");
$consultaRoles->execute();
$roles = $consultaRoles->fetchAll();

$consultaEstado = $con->prepare("SELECT * FROM estado");
$consultaEstado->execute();
$estados = $consultaEstado->fetchAll();

$consultaTipDocu = $con->prepare("SELECT * FROM tipdocu");
$consultaTipDocu->execute();
$tiposDocumento = $consultaTipDocu->fetchAll(PDO::FETCH_ASSOC);

// Crear el mapa de tipos de documento
$documentoMap = [];
foreach ($tiposDocumento as $tipoDocumento) {
    $documentoMap[$tipoDocumento['id_tipdocu']] = $tipoDocumento['tipdocu'];
}

// BOTON DE ACTUALIZACIÓN EL CUAL VIENE DE UN BUTTON, VALUE DEL FORMULARIO
if (isset($_POST["btn-actualizar"])) {

    // DATOS DEL FORMULARIO Y DB
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['correo_electronico'];
    $celular = $_POST['celular'];
    $direccion = $_POST['direccion'];
    $genero = $_POST['id_genero'];
    $rol = $_POST['id_rol'];
    $estado = $_POST['id_estado'];
    $id_tipdocu = $_POST['id_tipdocu'];

    // Consulta de actualización
    $consultaActualizar = $con->prepare("UPDATE usuarios SET nombre = :nombre, apellido = :apellido, correo_electronico = :email, celular = :celular, direccion = :direccion, id_genero = :genero, id_rol = :rol, id_estado = :estado, id_tipdocu = :id_tipdocu WHERE documento = :documento");

    // Bind de parámetros
    $consultaActualizar->bindParam(':nombre', $nombre);
    $consultaActualizar->bindParam(':apellido', $apellido);
    $consultaActualizar->bindParam(':email', $email);
    $consultaActualizar->bindParam(':celular', $celular);
    $consultaActualizar->bindParam(':direccion', $direccion);
    $consultaActualizar->bindParam(':genero', $genero);
    $consultaActualizar->bindParam(':rol', $rol);
    $consultaActualizar->bindParam(':estado', $estado);
    $consultaActualizar->bindParam(':id_tipdocu', $id_tipdocu);
    $consultaActualizar->bindParam(':documento', $documento_a_actualizar);

    // Ejecutar la consulta
    if ($consultaActualizar->execute()) {
        echo '<script>alert("Actualización exitosa.");</script>';
        echo '<script>window.location="index.php"</script>';
    } else {
        echo '<script>alert("Error al actualizar. Por favor, inténtalo de nuevo.");</script>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Usuarios</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/stylelo.css">

    <!-- Favicon -->
    <link href="../../assets/img/logo.png" rel="icon">
</head>

<body>
    <div class="container mt-5">
        <button type="submit" class="btn btn-re btn-xl sharp" style="padding: 5px 10px; font-size: 12px;">
            <a href="./index.php" style="color: #000000;" class="d-flex align-items-center">
                <i class="fas fa-arrow-left mr-2 fa-2x"></i>
            </a>
        </button>
        <br>
        <h2>Actualización de Usuarios</h2>

        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="form-group col">
                    <label for="documento">Número de documento:</label>
                    <input type="text" class="form-control" name="documento" id="documento" value="<?= $datosUsuario['documento'] ?>" readonly>
                </div>

                <div class="form-group col">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?= $datosUsuario['nombre'] ?>" required>
                </div>

                <div class="form-group col">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="form-control" name="apellido" id="apellido" value="<?= $datosUsuario['apellido'] ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="correo_electronico">Correo Electrónico:</label>
                    <input type="email" class="form-control" name="correo_electronico" value="<?= $datosUsuario['correo_electronico'] ?>" required>
                </div>

                <div class="form-group col">
                    <label for="celular">Número Telefónico:</label>
                    <input type="number" class="form-control" id="celular" name="celular" value="<?= $datosUsuario['celular'] ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="direccion">Dirección:</label>
                    <input type="text" class="form-control" name="direccion" value="<?= $datosUsuario['direccion'] ?>" required>
                </div>

                <div class="col">
                    <label for="id_genero">Género:</label>
                    <select name="id_genero" class="form-control" required>
                        <option value="" disabled>Seleccione tipo de genero</option>
                        <?php foreach ($generos as $genero) : ?>
                            <option value="<?= $genero['id_genero'] ?>" <?= ($genero['id_genero'] == $datosUsuario['id_genero']) ? 'selected' : '' ?>>
                                <?= $genero['genero'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label for="id_rol">Rol:</label>
                    <select name="id_rol" class="form-control" required>
                        <option value="" disabled>Seleccione tipo de rol</option>
                        <?php foreach ($roles as $rol) : ?>
                            <option value="<?= $rol['id_rol'] ?>" <?= ($rol['id_rol'] == $datosUsuario['id_rol']) ? 'selected' : '' ?>>
                                <?= $rol['tipo_rol'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="id_tipdocu">Tipo de Documento:</label>
                    <select name="id_tipdocu" class="form-control" required>
                        <option value="" disabled>Seleccione tipo de documento</option>
                        <?php foreach ($tiposDocumento as $tipoDocumento) : ?>
                            <option value="<?= $tipoDocumento['id_tipdocu'] ?>" <?= ($tipoDocumento['id_tipdocu'] == $datosUsuario['id_tipdocu']) ? 'selected' : '' ?>>
                                <?= $tipoDocumento['tipdocu'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" class="form-control form-control-lg" value="<?= $datosUsuario['id_estado'] ?>" name="id_estado">
            </div>

            <button type="submit" value="actualizar" name="btn-actualizar" class="btn_ing">ACTUALIZAR</button>
            <a class="volver" href="index.php">Volver</a><br>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        // Funciones JavaScript
    </script>

    <!-- Script para validar la dirección -->
    <script>
        function validarDireccion(input) {
            const valor = input.value;
            if (validarCadena(valor)) {
                input.setCustomValidity(''); // La cadena es válida
            } else {
                input.setCustomValidity('La dirección contiene caracteres no permitidos.');
            }
        }

        function validarCadena(cadena) {
            // Expresión regular que permite números, letras, espacios y caracteres especiales permitidos
            const regex = /^[a-zA-Z0-9\s!@#$%^&*()-_+=.,;:'"/\\<>?]+$/;
            return regex.test(cadena);
        }
    </script>
</body>

</html>
