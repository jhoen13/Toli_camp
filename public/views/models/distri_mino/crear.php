<?php
session_start(); // Iniciar la sesión

// Se importa el archivo de conexión a la base de datos
require_once("../../../db/conexion.php");

// Se instancia la clase Database para la conexión a la base de datos
$db = new database();
$conexion = $db->conectar();

// Asegúrate de que la sesión esté iniciada y que 'document' esté definida
if (isset($_SESSION['document'])) {
    $documento = $_SESSION['document'];

    // Corrige la consulta SQL
    $sql = $conexion->prepare("SELECT * FROM usuarios AS u
        JOIN roles AS r ON u.id_rol = r.id_rol
        WHERE u.documento = :documento");
    $sql->bindParam(":documento", $documento, PDO::PARAM_STR);
    $sql->execute();
    $usua = $sql->fetch();
} else {
    // Manejar el caso en el que la sesión no esté iniciada 
    echo "<script>alert('La sesión no está iniciada, Redirigiendo...');</script>";
    echo '<script>window.location="../../auth/login.php"</script>';
    exit(); // Agrega un exit() para detener la ejecución del script en este punto
}

// Cierre de sesión al presionar 'btncerrar'
if (isset($_POST['btncerrar'])) {
    $documento = $_SESSION['document'];

    date_default_timezone_set('America/Bogota');
    $fecha_salida = date('Y-m-d');
    $hora_salida = date('H:i:s');

    // Consulta para obtener la fecha de ingreso y el código de ingreso; se trae el último registro de la tabla
    $consulta2 = $conexion->prepare("SELECT fecha_ingre, hora_ingre, codi_ingre FROM ingreso WHERE documento = :documento ORDER BY id_ingreso DESC LIMIT 1");
    $consulta2->execute([':documento' => $documento]);
    $resultado = $consulta2->fetch(PDO::FETCH_ASSOC); // Obteniendo el resultado de la consulta

    $fecha_ingreso = $resultado['fecha_ingre'];  // Obteniendo la fecha de ingreso de la tabla
    $hora_ingreso = $resultado['hora_ingre'];  // Obteniendo la hora de ingreso de la tabla
    $codi_ingre = $resultado['codi_ingre']; // Obteniendo código de ingreso

    // Calcular duración teniendo en cuenta la "fecha_ingreso" y "fecha_salida"
    $diferencia = strtotime("$fecha_salida $hora_salida") - strtotime("$fecha_ingreso $hora_ingreso");
    // diferencia en segundos se utiliza para calcular la duración formatada
    $duracion = gmdate('H:i:s', $diferencia); // Formato de duración en horas:minutos:segundos

    // se realiza el update a la tabla iongreso calculando la duración del usuario en la pagina
    $consulta3 = $conexion->prepare("UPDATE ingreso SET fecha_sali = :fecha_salida, hora_sali = :hora_salida, durac = :duracion WHERE documento = :documento AND codi_ingre = :codi_ingre");
    $consulta3->bindParam(":fecha_salida", $fecha_salida);
    $consulta3->bindParam(":hora_salida", $hora_salida);
    $consulta3->bindParam(":duracion", $duracion);
    $consulta3->bindParam(":documento", $documento);
    $consulta3->bindParam(":codi_ingre", $codi_ingre);
    $consulta3->execute();

    session_destroy();
    header("Location:../../../../index.html");
}

// Obtiene el documento de la sesión del usuario
$document = $_SESSION['document'];

// Consulta SQL para obtener la información del usuario logueado
$user = $conexion->prepare("SELECT * FROM usuarios WHERE documento = '$document'");
$user->execute();
$respuesta = $user->fetch(PDO::FETCH_ASSOC);

// Consulta SQL para obtener las últimas 6 entradas de usuarios
$userEntry = $conexion->prepare("SELECT * FROM ingreso INNER JOIN usuarios INNER JOIN roles ON ingreso.documento = usuarios.documento AND usuarios.id_rol = roles.id_rol WHERE roles.id_rol >= 1 ORDER BY ingreso.id_ingreso DESC LIMIT 10");
$userEntry->execute();
$entry = $userEntry->fetchAll(PDO::FETCH_ASSOC);


// Consulta para obtener las categorías
$stmCategorias = $conexion->prepare("SELECT id_categoria, categoria FROM categoria");
$stmCategorias->execute();
$categorias = $stmCategorias->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener los documentos relacionados con los roles de administrador y vendedor
$stmDocumentos = $conexion->prepare("SELECT documento, nombre FROM usuarios WHERE id_rol IN ('1 administrador', '3 vendedor', '4 campesino')");
$stmDocumentos->execute();
$documentos = $stmDocumentos->fetchAll(PDO::FETCH_ASSOC);

$stmembalaje = $conexion->prepare("SELECT id_embala, embalaje FROM embalaje");
$stmembalaje->execute();
$embalaje = $stmembalaje->fetchAll(PDO::FETCH_ASSOC);

$consulta = $conexion->prepare("SELECT nom_produc, barcode FROM productos");
$consulta->execute();
$resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

if ((isset($_POST["registro"])) && ($_POST["registro"] == "formu")) {
    $nom_produc = $_POST['nom_produc'];
    $descrip = $_POST['descrip'];
    $precio_compra = $_POST['precio_compra'];
    $id_categoria = $_POST['id_categoria'];
    $cantidad = $_POST['cantidad'];
    $id_embala = $_POST['id_embala'];

    // Verifica si se ha enviado un archivo
    if (!empty($_FILES['foto']['name'])) {
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombre = "producto_" . time();
        $foto_nombre = $nombre . "." . $extension;
        $ruta_destino = "../../../../assets/img/img_produc/$foto_nombre";

        // Mueve el archivo a la ruta de destino
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
    } else {
        // Si no se envió un archivo, muestra un mensaje de error y redirige
        echo '<script>alert("No se ha seleccionado una imagen"); window.location="index.php"</script>';
        exit(); // Detiene la ejecución del script
    }

    $precio_ven = $_POST['precio_ven'];
    $documento = $_POST['documento'];
    $barcode = $_POST['barcode'];

    $validar = $conexion->prepare("SELECT * FROM productos WHERE nom_produc = '$nom_produc'");
    $validar->execute();
    $filaa1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if ($nom_produc == "" || $descrip == "" || $precio_compra == "" || $id_categoria == "" || $cantidad == "" || $id_embala == "" || $foto_nombre == "" || $precio_ven == "" || $documento == "") {
        echo '<script> alert ("EXISTEN DATOS VACÍOS");</script>';
        echo '<script> window.location="./crear.php"</script>';
    } else {
        $insertsql = $conexion->prepare("INSERT INTO productos( nom_produc, descrip, precio_compra,id_categoria,cantidad,id_embala,foto,precio_ven,documento,barcode) VALUES ( '$nom_produc','$descrip', '$precio_compra','$id_categoria','$cantidad','$id_embala','$foto_nombre','$precio_ven','$documento','$barcode');");
        $insertsql->execute();
        echo '<script>alert("Registro Exitoso");</script>';
        echo '<script> window.location="index-vende.php"</script>';

        // Obtener el último ID insertado
        $id = $pdo->lastInsertId();

        // Generar código con el último ID y milisegundos
        $codigo = $id . date('is');

        // Actualizar el producto con el código generado
        $actualizarCodigo = $pdo->prepare("UPDATE productos SET barcode = :codigo WHERE id_producto = :id");
        $actualizarCodigo->bindParam(':barcode', $barcode, PDO::PARAM_STR);
        $actualizarCodigo->bindParam(':id', $id, PDO::PARAM_INT);
        $actualizarCodigo->execute();
    }
    
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Agrega los estilos de Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="robots" content="">
    <meta name="description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template">
    <meta property="og:title" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template">
    <meta property="og:description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template">
    <meta property="og:image" content="https://fillow.dexignlab.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">
    <!-- NOMBRE DE LA PERSONA LA CUAL SE ENCUENTRA LOGUEADA -->
    <title>Mayo <?php echo $respuesta['nombre'] ?></title>
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="../../../../assets/img/logo.png">
    <!-- Datatable -->
    <link href="../../../../../vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="../../../../../vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="../../../../../css/style.css" rel="stylesheet">

    <style>
        /* ocultar texto de los icono */
        .icon {
            display: inline;
        }

        .text {
            display: none;
        }

        .btn:hover .icon {
            display: none;
        }

        .btn:hover .text {
            display: inline;
        }

        /* end ocultar texto de icono */
    </style>
    <style>
        .modal-body label {
        font-size: 18px;
    }
        .modal-body input {
        font-size: 18px;
    }
    </style>

</head>



<body class="con" style="font-family: 'Times New Roman', Times, serif;">
    <!--****** Main wrapper start *******-->
    <div id="main-wrapper">

        <!--***** Content body start *********-->
        <div class="content-body">

            <div class="container-fluid">
                <div class="row">
                    <!-- CONTENIDO TABLA DE USUARIOS -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Actividad</h4>
                            </div>
                            <div class="card-body">
                                <div class="row" style="font-size: 25px;">
                                </div>
                                <div class="table-responsive" >
                                    <h2 class="modal-title" id="exampleModalLabel">Crear Producto</h2>
                                    <table id="example3" class="display text-center" style="min-width: 845px">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <label for="nom_produc">Nombre Del Producto</label>
                                                <input id="nom_produc" type="text" class="form-control" name="nom_produc" placeholder="Ingresa un producto">
                                            </div>
                                            <div class="modal-body">
                                                <label for="descrip">Descripcion Del Producto</label>
                                                <input id="descrip" type="text" class="form-control" name="descrip" placeholder="Ingresa una descripcion del producto">
                                            </div>
                                            <div class="modal-body">
                                                <label for="categoria">Categoría De Los Productos</label>
                                                <select id="categoria" class="form-control" name="id_categoria">
                                                    <?php foreach ($categorias as $categoria) { ?>
                                                        <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['categoria']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="modal-body">
                                                <label for="cantidad">Cantidad Del Producto</label>
                                                <input id="cantidad" type="number" class="form-control" name="cantidad" placeholder="Ingresa una cantidad">
                                            </div>
                                            <div class="modal-body">
                                                <label for="embalaje">Tipo de embalaje</label>
                                                <select id="embalaje" class="form-control" name="id_embala">
                                                    <?php foreach ($embalaje as $embalajes) { ?>
                                                        <option value="<?php echo $embalajes['id_embala']; ?>"><?php echo $embalajes['embalaje']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="imagen" style="font-size: 18px;">Imagen:</label>
                                                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>
                                            </div>
                                            <div class="modal-body">
                                                <label for="precio_compra">Precio De compra</label>
                                                <input id="precio_compra" type="number" class="form-control" name="precio_compra" placeholder="Ingresa el precio de compra del Producto">
                                            </div>
                                            <div class="modal-body">
                                                <label for="precio_ven">Precio venta</label>
                                                <input id="precio_ven" type="number" class="form-control" name="precio_ven" placeholder="Ingresa el precio de venta del Producto">
                                            </div>
                                            <div class="modal-body">
                                                <label for="barcode">Codigo de barras</label>
                                                <input id="barcode" type="number" class="form-control" name="barcode" placeholder="Ingresa el codigo de barras">
                                            </div>
                                            <div class="modal-body">
                                                <input id="documento" type="hidden" name="documento" value = "<?= $documento ?>">
                                            </div>
                                            <div class="modal-footer">
                                            <button type="submit" name="registro" value="formu" class="btn_ing btn btn-margin col-4 mx-auto" style="background-color: #0097B2; color: #ffff;">CREAR PRODUCTO</button>  
                                            </div>
                                        </form>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--***** Content body end *****-->

        <!--***** Footer start *******-->
        <div class="footer">
            <div class="copyright">
                <p><strong>Toli-Camp © 2023 Todos los derechos reservados</p>
                <p class="fs-12">Hecho por<span class="heart"></span> Aprendices SENA</p>
            </div><br>
        </div>
        <!--****** Footer end ********-->
    </div>
    <!--***** Main wrapper end ********-->


    <!--**** Scripts menu, tables *******-->
    <!-- Required vendors -->
    <script src="../../../../../vendor/global/global.min.js"></script>
    <script src="../../../../../vendor/chart.js/Chart.bundle.min.js"></script>
    <!-- Apex Chart -->
    <script src="../../../../../vendor/apexchart/apexchart.js"></script>
    <!-- Datatable -->
    <script src="../../../../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../../../../js/plugins-init/datatables.init.js"></script>
    <script src="../../../../../vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
    <script src="../../../../../js/custom.min.js"></script>
    <script src="../../../../../js/dlabnav-init.js"></script>
    <script src="../../../../../js/demo.js"></script>
    <script src="../../../../../js/styleSwitcher.js"></script>
    <!--**** Fin Scripts menu, tables *******-->
</body>

</html>

<script src="JsBarcode.all.min.js"></script>
<script type="text/javascript">
    function arrayjsonbarcode(j) {
        json = JSON.parse(j);
        arr = [];
        for (var x in json) {
            arr.push(json[x]);
        }
        return arr;
    }

    jsonvalor = '<?php echo json_encode($arrayCodigos); ?>';
    valores = arrayjsonbarcode(jsonvalor);

    for (var i = 0; i < valores.length; i++) {
        JsBarcode("#barcode" + valores[i], valores[i].toString(), {
            format: "CODE128", // Cambiado a CODE128, ajusta el formato según tus necesidades
            lineColor: "#000",
            width: 2,
            height: 30,
            displayValue: true
        });
    }
</script>







<!-- <?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

// Consulta para obtener las categorías
$stmCategorias = $conectar->prepare("SELECT id_categoria, categoria FROM categoria");
$stmCategorias->execute();
$categorias = $stmCategorias->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener los documentos relacionados con los roles de administrador y vendedor
$stmDocumentos = $conectar->prepare("SELECT documento FROM usuarios WHERE id_rol IN ('1 administrador', '3 vendedor')");
$stmDocumentos->execute();
$documentos = $stmDocumentos->fetchAll(PDO::FETCH_ASSOC);

$stmembalaje = $conectar->prepare("SELECT id_embala, embalaje FROM embalaje");
$stmembalaje->execute();
$embalaje = $stmembalaje->fetchAll(PDO::FETCH_ASSOC);

if ((isset($_POST["registro"])) && ($_POST["registro"] == "formu")) {
    $nom_produc = $_POST['nom_produc'];
    $descrip = $_POST['descrip'];
    $precio_compra = $_POST['precio_compra'];
    $disponibles = $_POST['disponibles'];
    $id_categoria = $_POST['id_categoria'];
    $cantidad = $_POST['cantidad'];
    $id_embala = $_POST['id_embala'];

    // Verifica si se ha enviado un archivo
    if (!empty($_FILES['foto']['name'])) {
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombre = "producto_" . time();
        $foto_nombre = $nombre . "." . $extension;
        $ruta_destino = "../../../../assets/img/img_produc/$foto_nombre";

        // Mueve el archivo a la ruta de destino
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
    } else {
        // Si no se envió un archivo, muestra un mensaje de error y redirige
        echo '<script>alert("No se ha seleccionado una imagen"); window.location="index.php"</script>';
        exit(); // Detiene la ejecución del script
    }

    $precio_ven = $_POST['precio_ven'];
    $documento = $_POST['documento'];

    $validar = $conectar->prepare("SELECT * FROM productos WHERE nom_produc = '$nom_produc'");
    $validar->execute();
    $filaa1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if ($nom_produc == "" || $descrip == "" || $precio_compra == "" || $disponibles == "" || $id_categoria == "" || $cantidad == "" || $id_embala == "" || $foto_nombre == "" || $precio_ven == "" || $documento == "") {
        echo '<script> alert ("EXISTEN DATOS VACÍOS");</script>';
        echo '<script> window.location="./crear.php"</script>';
    } else {
        $insertsql = $conectar->prepare("INSERT INTO productos( nom_produc, descrip, precio_compra,disponibles,id_categoria,cantidad,id_embala,foto,precio_ven,documento) VALUES ( '$nom_produc','$descrip', '$precio_compra', '$disponibles','$id_categoria','$cantidad','$id_embala','$foto_nombre','$precio_ven','$documento');");
        $insertsql->execute();
        echo '<script>alert("Registro Exitoso");</script>';
        echo '<script> window.location="index.php"</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../../../controller/img/icono.png" type="image/x-icon">
    <title>Formulario de creación de productos</title>
</head>

<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear Un Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <label for="nom_produc">Nombre Del Producto</label>
                    <input id="nom_produc" type="text" class="form-control" name="nom_produc" placeholder="Ingresa un producto">
                </div>
                <div class="modal-body">
                    <label for="descrip">Descripcion Del Producto</label>
                    <input id="descrip" type="text" class="form-control" name="descrip" placeholder="Ingresa una descripcion del producto">
                </div>
                <div class="modal-body">
                    <label for="precio_compra">Precio</label>
                    <input id="precio_compra" type="number" class="form-control" name="precio_compra" placeholder="Ingresa Un Precio Del Producto">
                </div>
                <div class="modal-body">
                    <label for="categoria">Categoría De Los Productos</label>
                    <select id="categoria" class="form-control" name="id_categoria">
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['categoria']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="modal-body">
                    <label for="cantidad">Cantidad Del Producto</label>
                    <input id="cantidad" type="number" class="form-control" name="cantidad" placeholder="Ingresa una cantidad">
                </div>
                <div class="modal-body">
                    <label for="embalaje">Tipo de embalaje</label>
                    <select id="embalaje" class="form-control" name="id_embala">
                        <?php foreach ($embalaje as $embalajes) { ?>
                            <option value="<?php echo $embalajes['id_embala']; ?>"><?php echo $embalajes['embalaje']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>
                </div>
                <div class="modal-body">
                    <label for="precio_ven">Precio venta</label>
                    <input id="precio_ven" type="number" class="form-control" name="precio_ven" placeholder="Precio venta">
                </div>
                <div class="modal-body">
                    <label for="documento">Documento</label>
                    <select id="documento" class="form-control" name="documento">
                        <?php foreach ($documentos as $doc) { ?>
                            <option value="<?php echo $doc['documento']; ?>"><?php echo $doc['documento']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="submit" class="btn btn-success" value="Crear producto">
                <input type="hidden" name="registro" value="formu">
                <div class="modal-footer"><a href="../producto/producto.php" class="btn btn-primary btn-margin">Volver</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html> -->