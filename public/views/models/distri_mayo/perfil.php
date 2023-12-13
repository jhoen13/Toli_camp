<?php
session_start();
require_once("../../../db/conexion.php");

$db = new Database();
$con = $db->conectar();

// Mensaje de éxito
$mensajeExito = '';

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['document'])) {
    $idUsuario = $_SESSION['document'];

    // Consultar los datos del usuario
    $sql_usuario = $con->prepare("SELECT * FROM usuarios WHERE documento = ?");
    $sql_usuario->execute([$idUsuario]);
    $usuario = $sql_usuario->fetch(PDO::FETCH_ASSOC);

    // Verificar si el formulario se ha enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recoger los datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo_electronico = $_POST['correo_electronico'];
        $celular = $_POST['celular'];
        $direccion = $_POST['direccion'];
        $genero = $_POST['id_genero'];

        // Validar que los campos numéricos solo contengan números
        if (!is_numeric($celular)) {
            echo "El campo celular solo debe contener números.";
            exit;
        }

        // Actualizar los datos del usuario en la base de datos
        $sql_actualizar = $con->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, correo_electronico = ?, celular = ?, direccion = ?, id_genero = ? WHERE documento = ?");
        $sql_actualizar->execute([$nombre, $apellido, $correo_electronico, $celular, $direccion, $genero,  $idUsuario]);

        // Volver a cargar los datos actualizados del usuario
        $sql_usuario = $con->prepare("SELECT * FROM usuarios WHERE documento = ?");
        $sql_usuario->execute([$idUsuario]);
        $usuario = $sql_usuario->fetch(PDO::FETCH_ASSOC);

        // Mensaje de éxito
        $mensajeExito = '¡Actualización exitosa!';

        // Redirigir a la página principal después de un breve retraso
        header("refresh:3;url=index-admin.php");
    }
}
$consultaGenero = $con->prepare("SELECT * FROM genero");
$consultaGenero->execute();
$generos = $consultaGenero->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Editar Perfil</title>
    <title>Admin <?php echo $respuesta['nombre'] ?></title>
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="../../../assets/img/logo.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/stylelo.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        form {
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .foto {
            max-width: 100px;
            max-height: 100px;
            border-radius: 50%;
            margin: 10px auto;
        }

        .boton {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .boton:hover {
            background-color: #0056b3;
        }

        /* Estilos para el mensaje de éxito */
        .mensaje-exito {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <button type="submit" class="btn btn-re btn-xl sharp" style="padding: 5px 10px; font-size: 12px;"> 
            <a href="index-distriMayo.php" style="color: #0097B2;" class="d-flex align-items-center">
                <i class="fas fa-arrow-left mr-2 fa-2x"></i>
            </a>
        </button>
        <h2>Editar Perfil</h2>

        <?php if (!empty($mensajeExito)) : ?>
            <div class="mensaje-exito">
                <?php echo $mensajeExito; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($usuario) && !empty($usuario)) : ?>
            <form method="post" action="">
                <label for="documento">Documento:</label>
                <input type="text" id="documento" name="documento" value="<?php echo $usuario['documento']; ?>" readonly>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>

                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo $usuario['apellido']; ?>" required>

                <label for="correo_electronico">Correo Electrónico:</label>
                <input type="email" id="correo_electronico_electronico" name="correo_electronico" value="<?php echo $usuario['correo_electronico']; ?>" required>

                <label for="celular">Celular:</label>
                <input type="tel" id="celular" name="celular" value="<?php echo $usuario['celular']; ?>" required>

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo $usuario['direccion']; ?>" required>

                <label for="id_genero">Género:</label>
                <select name="id_genero" class="form-control" required>
                    <option value="" disabled>Seleccione tipo de genero</option>
                    <?php foreach ($generos as $genero) : ?>
                        <option value="<?= $genero['id_genero'] ?>" <?= ($genero['id_genero'] == $usuario['id_genero']) ? 'selected' : '' ?>>
                            <?= $genero['genero'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="submit" class="boton" value="Guardar Cambios" style="background-color: #0097B2; color: #ffff;">
            </form>
        <?php else : ?>
            <p>No se encontraron datos de usuario.</p>
        <?php endif; ?>
    </div>
</body>

</html>
