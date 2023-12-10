<?php
require_once("../../db/conexion.php");
$db = new Database();
$con = $db->conectar();

// SE REALIZAN CONSULTAS PARA LOS SELECT
$consulta1 = $con->prepare("SELECT * FROM genero");
$consulta1->execute();
$consul = $consulta1->fetch();

$consulta2 = $con->prepare("SELECT * FROM tipdocu");
$consulta2->execute();
$consull = $consulta2->fetch();

$consulta3 = $con->prepare("SELECT * FROM roles WHERE id_rol >= 2");
$consulta3->execute();
$consulll = $consulta3->fetch();

$consulta4 = $con->prepare("SELECT * FROM estado");
$consulta4->execute();
$consullll = $consulta4->fetch();
?>


<?php
// BOTON DE REGISTRO EL CUAL VIENE DE UN BUTTON, VALUE DEL FORMULARIO
if ((isset($_POST["btn-registrar"]))) {
  // DATOS DEL FORMULARIO Y DB
  $tip_docu = $_POST['id_tipdocu'];
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

  if (!empty($_FILES['foto']['name'])) {
    $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nom = "usuario_" . time();
    $foto_nombre = $nom . "." . $extension;
    $ruta_destino = "../../assets/img/img_user/$foto_nombre";

    // Mueve el archivo a la ruta de destino
    move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
  } else {
    // Si no se envió un archivo, muestra un mensaje de error y redirige
    echo '<script>alert("No se ha seleccionado una imagen"); window.location="./index.php"</script>';
    exit(); // Detiene la ejecución del script
  }

  $consulta2 = $con->prepare("SELECT * FROM usuarios WHERE documento= '$documento' OR correo_electronico = '$email'");
  $consulta2->execute();
  $consull = $consulta2->fetchAll();

  if ($consull) {
    // SI SE CUMPLE ESTA CONSULTA ES PORQUE EL DOCUMENTO O EL EMAIL YA EXISTEN EN LA DB
    echo '<script> alert ("// Estimado Usuario, los datos ingresados ya están registrados. //");</script>';
    echo '<script>windows.location="registro.php"</script>';
  } elseif ($documento == "" || $nombre == "" || $apellido == "" || $email == "" || $pass == "" || $celular == "" || $direc == "" || $genero == "" || $rol == "" || $foto_nombre == "" || $estado == "") {
    // CONDICIONAL DEPENDIENDO SI EXISTEN DATOS VACÍOS EN EL FORMULARIO 
    echo '<script> alert ("Estimado Usuario, Existen Datos Vacios En El Formulario");</script>';
    echo '<script>windows.location="registro.php"</script>';
  } else {
    // HASH DE LA PASSWORD, SE ENCRIPTA
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);

    $consulta3 = $con->prepare("INSERT INTO usuarios (documento, nombre, apellido, correo_electronico, password, celular, direccion, id_genero, id_rol, id_estado, foto, id_tipdocu ) VALUES ('$documento','$nombre','$apellido','$email','$hash_pass', '$celular', '$direc', '$genero', '$rol', '$estado', '$foto_nombre', $tip_docu)");
    $consulta3->execute();

    // Obtener el último documento de usuario insertado
    $documento_insertado = $documento;

    // Verificar el id_rol y actualizar el id_estado si es necesario
    if ($rol == 3 || $rol == 6) {
      $nuevo_estado = 2;

      $consulta4 = $con->prepare("UPDATE usuarios SET id_estado = :nuevo_estado WHERE documento = :documento");
      $consulta4->bindParam(':nuevo_estado', $nuevo_estado);
      $consulta4->bindParam(':documento', $documento_insertado);
      $consulta4->execute();
    }

    echo '<script>alert ("Registro exitoso, gracias por tu registro, ya puedes iniciar sesión.");</script>';
    echo '<script>window.location="login.php"</script>';
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
  <link rel="stylesheet" href="../../assets/css/stylere.css">

  <!-- Favicon -->
  <link href="../../assets/img/logo.png" rel="icon">
</head>

<body>
  <!-- mt es margin top; l left; b buttom y asi con todas las partes del margin -->
  <div class="container mt-5">
    <button type="submit" class="btn btn-re btn-xl sharp" style="padding: 5px 10px; font-size: 12px;">
      <a href="../../../index.html" style="color: #28503C;" class="d-flex align-items-center">
        <i class="fas fa-arrow-left mr-2 fa-2x"></i>
      </a>
    </button>
    <br>
    <h2>REGISTRO</h2>

    <form method="POST" enctype="multipart/form-data" autocomplete="off">
      <div class="row">
        <div class="col">
          <label for="tipo_docu" style="margin-right: 10px;">Tipo de Documento:</label>
          <select name="id_tipdocu" class="form-control" required id="select3">
            <option value="" disabled selected>Seleccione Tipo de documento</option>
            <?php

            do {

            ?>
              <option value="<?php echo ($consull['id_tipdocu']) ?>"><?php echo ($consull['tipdocu']) ?></option>
            <?php
            } while ($consull = $consulta2->fetch());

            ?>
          </select>
        </div>
        <div class="col">
          <label for="documento" style="margin-right: 10px;">Documento:</label>
          <input type="number" placeholder="Numero de documento" class="form-control" name="documento" id="documento" pattern="[0-9]{6,10}" onkeypress="return(multiplenumber(event));" oninput="maxlengthNumber(this);" maxlength="10" required>
        </div>
        <div class="col">
          <label for="genero" style="margin-right: 10px;">Género:</label>
          <select name="id_genero" class="form-control" required id="select2">
            <option value="" disabled selected>Seleccione tipo de genero</option>
            <?php

            do {

            ?>
              <option value="<?php echo ($consul['id_genero']) ?>"><?php echo ($consul['genero']) ?></option>
            <?php
            } while ($consul = $consulta1->fetch());

            ?>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <label for="nombre" style="margin-right: 10px;">Nombres:</label>
          <input type="text" autocomplete="off" placeholder="Ingrese solo su Nombre" class="form-control" pattern="[a-z]{2,30}" onkeyup="minuscula(this)" minlength="2" maxlength="30" id="nombre" name="nombre" required title="No debe terminar con espacios; su límite es de 30 dígitos">
        </div>
        <div class="col">
          <label for="apellido" style="margin-right: 10px;">Apellidos:</label>
          <input type="text" autocomplete="off" placeholder="Ingrese solo sus apellidos" class="form-control" pattern="[a-z]{2,30}" onkeyup="minuscula(this)" maxlength="30" id="apellido" name="apellido" required title="No debe terminar con espacios; su límite es de 30 dígitos">
        </div>
        <div class="col">
          <label for="email" style="margin-right: 10px;">Correo Electrónico:</label>
          <input type="email" placeholder="Correo Electronico" class="form-control" name="correo_electronico" required onkeyup="espacios(this)" maxlength="40">
        </div>
      </div>

      <div class="row">
        <div class="col">
          <label for="password">Contraseña:</label>
          <div class="input-group">
            <input type="password" placeholder="Contraseña" name="password" pattern="[a-zA-Z0-9]{6,12}" class="form-control input-text clave" title="Debe tener de 6 a 12 dígitos" required onkeyup="espacios(this)" minlength="6" maxlength="12" id="passwordField">
            <div class="input-group-append">
              <button type="button" class="icono fas fa-eye-slash mostrarClave w-20 bg-gradient" id="togglePassword"></button>
            </div>
          </div>
        </div>
        <div class="col">
          <label for="celular" style="margin-right: 10px;">Celular:</label>
          <input type="number" autocomplete="off" placeholder="Numero Del Celular" onkeypress="return(multiplenumber(event));" oninput="maxlengthNumber(this);" maxlength="10" class="form-control" id="celular" name="celular" required>
        </div>
        <div class="col">
          <label for="direccion" style="margin-right: 10px;">Dirección:</label>
          <input type="text" autocomplete="off" placeholder="Ingrese su dirección" class="form-control" onkeyup="validarDireccion(this)" maxlength="50" id="direccion" name="direccion" required>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <label for="rol" style="margin-right: 10px;">Rol:</label>
          <select name="id_rol" class="form-control" required id="select2">
            <option value="" disabled selected>Seleccione tipo de rol</option>
            <?php
            do {

            ?>
              <option value="<?php echo ($consulll['id_rol']) ?>"><?php echo ($consulll['tipo_rol']) ?></option>
            <?php
            } while ($consulll = $consulta3->fetch());

            ?>
          </select>
        </div>
        <div class="form-group col">
          <label for="imagen">Foto De Perfil:</label>
          <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>
        </div>

        <div class="">
          <input type="hidden" placeholder="Estado" readonly class="form-control form-control-lg input-text " value="1" name="id_estado">
        </div>
      </div>

      <div style="text-align: center;">
        <br>
        <button type="submit" value="registrar" name="btn-registrar" class="btn_ing">REGISTRAR</button><br>
        <a class="ingresar" href="login.php" style="color: black;">Ingresar</a><br>
      </div>
    </form>
  </div>
</body>

<script>
  function mayuscula(e) {
    e.value = e.value.toUpperCase();
  }

  function minuscula(e) {
    e.value = e.value.toLowerCase();
  }

  function numeros(e) {
    e.value = e.value.replace(/[^0-9\.]/g, '');
  }

  // FUNCION PARA QUE EN EL INPUT NO HAYA ESPACIOS
  function espacios(e) {
    e.value = e.value.replace(/ /g, '');
  }

  // <!-- FUNCION DE JAVASCRIPT QUE PERMITE INGRESAR SOLO NUMEROS EN EL FORMULARIO ASIGNADO -->
  function multiplenumber(e) {
    key = e.keyCode || e.which;

    teclado = String.fromCharCode(key).toLowerCase();

    numeros = "1234567890";

    especiales = "8-37-38-46-164-46";

    teclado_especial = false;

    for (var i in especiales) {
      if (key == especiales[i]) {
        teclado_especial = true;
        alert("Debe ingresar solo numeros en el formulario");
        break;
      }
    }

    if (numeros.indexOf(teclado) == -1 && !teclado_especial) {
      return false;
      alert("Debe ingresar solo numeros en el formulario ");
    }
  }

  // <!-- FUNCION DE JAVASCRIPT QUE PERMITE INGRESAR SOLO EL NUMERO VALORES REQUERIDOS DE ACUERDO A LA LONGITUD MAXLENGTH DEL CAMPO -->
  function maxlengthNumber(obj) {

    if (obj.value.length > obj.maxLength) {
      obj.value = obj.value.slice(0, obj.maxLength);
      alert("Debe ingresar solo el numeros de digitos requeridos");
    }
  }

  // SE CREA UNA FUNCION PARA QUE SE OCULTE Y SE PUEDA VER LA CONTRASEÑA
  const passwordField = document.getElementById("passwordField");
  const togglePassword = document.getElementById("togglePassword");

  togglePassword.addEventListener("click", function() {
    if (passwordField.type === "password") {
      passwordField.type = "text";
      togglePassword.classList.remove("fa-eye-slash");
      togglePassword.classList.add("fa-eye");
    } else {
      passwordField.type = "password";
      togglePassword.classList.remove("fa-eye");
      togglePassword.classList.add("fa-eye-slash");
    }
  });
</script>

<!-- EN EL SIGUIENTE SCRIPT SE ENCUENTRAN LAS FUNCIONES PARA VALIDAR EL DIRRECCIÓN -->
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

</html>