<?php
require_once("../../../../db/conexion.php");
$db = new Database();
$con = $db->conectar();

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
    $documentoMap[$tipoDocumento['id_tipdocu']] = $tipoDocumento['tipoocu'];
}
?>

<?php
// BOTON DE REGISTRO EL CUAL VIENE DE UN BUTTON, VALUE DEL FORMULARIO
if (isset($_POST["btn-registrar"])) {

    // DATOS DEL FORMULARIO Y DB
    $documento = $_POST['documento'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['correo_electronico'];
    $pass = $_POST['password'];
    $celular = $_POST['celular'];
    $direc = $_POST['direccion'];
    $genero = $_POST['id_genero'];
    $rol = $_POST['id_rol'];
    $estado = $_POST['id_estado'];
    $id_tipdocu = $_POST['id_tipdocu'];

    $consultaExistencia = $con->prepare("SELECT * FROM usuarios WHERE documento= '$documento' OR correo_electronico = '$email'");
    $consultaExistencia->execute();
    $resultadoExistencia = $consultaExistencia->fetchAll();

    if ($resultadoExistencia) {
        // SI SE CUMPLE ESTA CONSULTA ES PORQUE EL DOCUMENTO O EL EMAIL YA EXISTEN EN LA DB
        echo '<script> alert ("// Estimado Usuario, los datos ingresados ya están registrados. //");</script>';
        echo '<script>window.location="registro.php"</script>';
    } elseif ($documento == "" || $nombre == "" || $apellido == "" || $email == "" || $pass == "" || $celular == "" || $direc == "" || $genero == "" || $rol == "" || $estado == "" || $id_tipdocu == "") {
        // CONDICIONAL DEPENDIENDO SI EXISTEN DATOS VACÍOS EN EL FORMULARIO 
        echo '<script> alert ("Estimado Usuario, existen datos vacíos en el formulario");</script>';
        echo '<script>window.location="registro.php"</script>';
    } else {
        // HASH DE LA PASSWORD, SE ENCRIPATA
        $hash_pass = password_hash($pass, PASSWORD_DEFAULT);

        $consultaInsertar = $con->prepare("INSERT INTO usuarios (documento, nombre, apellido, correo_electronico, password, celular, direccion, id_genero, id_rol, id_estado, id_tipdocu) VALUES ('$documento','$nombre','$apellido','$email','$hash_pass', '$celular', '$direc', '$genero', '$rol', '$estado', '$id_tipdocu')");
        $consultaInsertar->execute();
        echo '<script>alert ("Registro exitoso, gracias por tu registro, ya puedes iniciar sesión.");</script>';
        echo '<script>window.location="index.php"</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/stylelo.css">

    <!-- Favicon -->
    <link href="../../assets/img/logo.png" rel="icon">
</head>

<body>
    <div class="container mt-5">
        <button type="submit" class="btn btn-re btn-xl sharp" style="padding: 5px 10px; font-size: 12px;">
            <a href="../../../index.html" style="color: #000000;" class="d-flex align-items-center">
                <i class="fas fa-arrow-left mr-2 fa-2x"></i>
            </a>
        </button>
        <br>
        <h2>Creacion de usuarios</h2>

        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="form-group col">
                    <label for="documento">Número de documento:</label>
                    <input type="number" placeholder="Número de documento" class="form-control" name="documento" id="documento" required>
                </div>

                <div class="form-group col">
                    <label for="nombre">Nombre:</label>
                    <input type="text" placeholder="Ingrese solo su Nombre" class="form-control" name="nombre" id="nombre" required>
                </div>

                <div class="form-group col">
                    <label for="apellido">Apellido:</label>
                    <input type="text" placeholder="Ingrese solo sus apellidos" class="form-control" name="apellido" id="apellido" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="correo_electronico">Correo Electrónico:</label>
                    <input type="email" placeholder="Correo Electrónico" class="form-control" name="correo_electronico" required>
                </div>

                <div class="col">
                    <div class="input-group">
                        <input type="password" placeholder="Contraseña" name="password" class="form-control input-text clave" title="Debe tener de 6 a 12 dígitos" required minlength="6" maxlength="12" id="passwordField">
                        <div class="input-group-append">
                            <button type="button" class="icono fas fa-eye-slash mostrarClave w-20 bg-gradient" id="togglePassword"></button>
                        </div>
                    </div>
                </div>

                <div class="form-group col">
                    <label for="celular">Número Telefónico:</label>
                    <input type="number" placeholder="Número Telefónico" class="form-control" id="celular" name="celular" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="direccion">Dirección:</label>
                    <input type="text" placeholder="Ingrese su dirección" class="form-control" name="direccion" required>
                </div>

                <div class="col">
                    <label for="id_genero">Género:</label>
                    <select name="id_genero" class="form-control" required>
                        <option value="" disabled selected>Seleccione tipo de genero</option>
                        <?php foreach ($generos as $genero) : ?>
                            <option value="<?php echo $genero['id_genero']; ?>"><?php echo $genero['genero']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col">
                    <label for="id_rol">Rol:</label>
                    <select name="id_rol" class="form-control" required>
                        <option value="" disabled selected>Seleccione tipo de rol</option>
                        <?php foreach ($roles as $rol) : ?>
                            <option value="<?php echo $rol['id_rol']; ?>"><?php echo $rol['tipo_rol']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="id_tipdocu">Tipo de Documento:</label>
                    <select name="id_tipdocu" class="form-control" required>
                        <option value="" disabled selected>Seleccione tipo de documento</option>
                        <?php foreach ($tiposDocumento as $tipoDocumento) : ?>
                            <option value="<?php echo $tipoDocumento['id_tipdocu']; ?>"><?php echo $tipoDocumento['tipoocu']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" placeholder="Estado" readonly class="form-control form-control-lg input-text " value="1" name="id_estado">
            </div>

            <button type="submit" value="registrar" name="btn-registrar" class="btn_ing">REGISTRAR</button>
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
