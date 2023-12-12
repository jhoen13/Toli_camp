<?php
session_start(); // Iniciar la sesión

// Se importa el archivo de conexión a la base de datos
require_once("../../../../db/conexion.php");

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
    header("Location:../../../../../index.html");
}

// Obtiene el documento de la sesión del usuario
$document = $_SESSION['document'];

// Consulta SQL para obtener la información del usuario logueado
$user = $conexion->prepare("SELECT * FROM usuarios WHERE documento = '$document'");
$user->execute();
$respuesta = $user->fetch(PDO::FETCH_ASSOC);

// Consulta SQL para obtener la información de todos los Productos
$stm = $conexion->prepare("SELECT * FROM productos WHERE documento = '$document'");
$stm->execute();
$productos = $stm->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id_producto'])) {
    $txtid = $_GET['id_producto'];

    $stm = $conexion->prepare("DELETE FROM productos WHERE id_producto = :id_producto");
    $stm->bindParam(":id_producto", $txtid, PDO::PARAM_INT);
    $stm->execute();

    header("location: index.php");
}

// Realiza una consulta para obtener los nombres de las categorías
$stmCategorias = $conexion->prepare("SELECT id_categoria, categoria FROM categoria");
$stmCategorias->execute();
$categorias = $stmCategorias->fetchAll(PDO::FETCH_ASSOC);
$categoriasMap = [];
foreach ($categorias as $categoria) {
    $categoriasMap[$categoria['id_categoria']] = $categoria['categoria'];
}

// Nueva consulta para obtener los valores de "embalaje"
$stmEmbalaje = $conexion->prepare("SELECT id_embala, embalaje FROM embalaje");
$stmEmbalaje->execute();
$embalajes = $stmEmbalaje->fetchAll(PDO::FETCH_ASSOC);
$embalajesMap = [];
foreach ($embalajes as $embalaje) {
    $embalajesMap[$embalaje['id_embala']] = $embalaje['embalaje'];
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
    <title>Admin <?php echo $respuesta['nombre'] ?></title>
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
</head>



<body class="con" style="font-family: 'Times New Roman', Times, serif;">
    <!--****** Main wrapper start *******-->
    <div id="main-wrapper">
        <!--****** Nav header start ***********-->
        <div class="nav-header">
            <a href="./index-admin.php" class="brand-logo">
                <img src="../../../../assets/img/logo.png" style="border-radius: 20px; width: 600px;" alt="logo Toli-Camp" class="logo-abbr">
                <div class="brand-title">
                    <h2 class="">Bienvenid@</h2>
                    <span class="brand-sub-title">Toli-Camp</span>
                </div>
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--******* Nav header end *************-->
        <!--******** Header start ********-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar" style="color:#4E3F6B">
                                <a aria-expanded="true">
                                    <i class="fas fa-user-check"></i>
                                </a>
                                Campesin@ <?php echo $respuesta['nombre'] ?>
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            
                            <!-- INICIO PERFIL -->
                            <li class="nav-item dropdown  header-profile">
                                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                    <img alt="Foto perfil del usuario" width="100" src="../../../../assets/img/img_user/<?= $respuesta["foto"] ?>">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="../perfil.php" class="dropdown-item ai-icon">
                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <span class="ms-2">Profile</span>
                                    </a>
                                    <a href="page-error-404.html" class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <form method="POST" action="">
                                            <span class="ms-2">
                                                <input type="submit" value="Cerrar sesion" id="btn_quote" name="btncerrar" class="ms-2 btn-logout" />
                                            </span>
                                        </form>
                                    </a>
                                </div>
                            </li>
                            <!-- FIN PERFIL -->
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**** Header end ti-comment-alt ******-->

        <!--****** Sidebar start ******-->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                    <li>
                        <a class="has-arrow " href="../index-admin.php" aria-expanded="false">
                            <i class="fas fa-home"></i>
                            <span class="nav-text">INICIO</span>
                        </a>
                    </li>
                    <!-- MODULO PARA VER PERFIL -->
                    <li>
                        <a class="has-arrow " href="../perfil.php" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <span class="nav-text">PERFIL</span>
                        </a>
                    </li>
                    <!-- MODULO USUARIOS -->
                    <li>
                        <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-user-check"></i>
                            <span class="nav-text">USUARIOS</span>
                        </a>
                        <ul aria-expanded="false">
                            <!-- MODULO PARA ENLISTAR O CREAR UN ADMINISTRADOR -->
                            <li><a href="../usuarios/index.php">Lista Usuarios</a></li>
                            <li><a href="../usuarios/crear.php">Crear Usuarios</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE CATEGORIAS -->
                    <li>
                        <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-folder"></i>
                            <span class="nav-text">CATEGORIAS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="../categoria/index.php">Lista Categorias</a></li>
                            <li><a href="../categoria/crear.php">Crear Categorias</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE DOCUMENTOS -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-file"></i>
                            <span class="nav-text">TIPO DOCUMENTO</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="../documentos/index.php">Lista Documentos</a></li>
                            <li><a href="../documentos/crear.php">Crear Documentos</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE EMBALAJE -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-box"></i>
                            <span class="nav-text">EMBALAJE</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="../embalaje/index.php">Listar Embalaje</a></li>
                            <li><a href="../embalaje/crear.php">Crear Embalaje</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE GENEROS -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-venus-mars"></i>
                            <span class="nav-text">GENEROS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="../genero/index.php">Lista Generos</a></li>
                            <li><a href="../genero/crear.php">Crear Generos</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE PRODUCTOS -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="nav-text">PRODUCTOS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./index.php">Lista Productos</a></li>
                            <li><a href="./crear.php">Crear Productos</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE ROLES -->
                    <li>
                        <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-users-cog"></i>
                            <span class="nav-text">ROLES</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="../roles/index.php">Lista Roles</a></li>
                            <li><a href="../roles/crear.php">Crear Roles</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE ESTADISTICAS
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-chart-line"></i>
                            <span class="nav-text">ESTADISTICAS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="#">Partidas</a></li>
                            <li><a href="#">Usuarios Bloqueados</a></li>
                        </ul>

                    </li> -->
                </ul>
                <!-- FOOTER -->
                <div class="copyright">
                    <p><strong>Toli-Camp © 2023 Todos los derechos reservados</p>
                    <p class="fs-12">Hecho por<span class="heart"></span> Aprendices SENA</p>
                </div>
                <!-- FOOTER END -->
            </div>
        </div>
        <!--****** Sidebar end **********-->





        <!--***** Content body start *********-->
        <div class="content-body">

            <div class="container-fluid">

                <!-- CONTENIDO TABLA DE INGRESO -->
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Productos</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Registrados</a></li>
                    </ol>
                </div>
                <div class="row">
                    <!-- CONTENIDO TABLA DE USUARIOS -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Actividad</h4>
                            </div>
                            <div class="card-body">
                                <div class="row" style="font-size: 25px;">
                                    <div class="col-4">
                                        <a href="./crear.php" class="btn btn-margin" style="background-color: #0097B2; color:#ffff;">Crear un Producto</a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example3" class="display text-center" style="min-width: 845px">
                                        <thead>   
                                            <tr>
                                                <th scope="col">ID producto</th>
                                                <th scope="col">Documento <br> Proveedor </th>
                                                <th scope="col">Nombre del Producto</th>
                                                <th scope="col">Descripcion</th>
                                                <th scope="col">Precio Compra</th>
                                                <th scope="col">Categoría</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col">Embalaje</th>
                                                <th scope="col">Foto</th>
                                                <th scope="col">Codigo de barras</th>
                                                <th scope="col">Precio Venta</th>
                                                <th colspan="2">Acciones</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            <?php foreach ($productos as $producto) { ?>
                                                <tr>
                                                    <td scope="row"><?php echo $producto['id_producto']; ?></td>
                                                    <td scope="row"><?php echo $producto['documento']; ?></td>
                                                    <td><?php echo $producto['nom_produc']; ?></td>
                                                    <td scope="row"><?php echo $producto['descrip']; ?></td>
                                                    <td>$ <?php echo $producto['precio_compra']; ?></td>
                                                    <td><?php echo $categoriasMap[$producto['id_categoria']]; ?></td>
                                                    <td scope="row"><?php echo $producto['cantidad']; ?></td> 
                                                    <td scope="row"><?php echo $embalajesMap[$producto['id_embala']]; ?></td>
                                                    <td><img src="../../../../assets/img/img_produc/<?= $producto["foto"] ?>" alt="" style="width: 75px;"></td>
                                                    <img src="barcode.php?text=<?php echo $producto['barcode']?>&size=40&codetype=Code128&print=true" />                            <a href="ventas.php?id=<?php echo $producto['id_producto']; ?>" class="buy-button">Comprar</a>

                                                    <td scope="row">$ <?php echo $producto['precio_ven']; ?></td>
                                                    <td>
                                                    <td>
                                                        <a href="editar.php?id_producto=<?php echo $producto['id_producto']; ?>" class="btn shadow btn-xxl sharp" style="background-color:#E1C022; color:#fff" onclick="return confirm('¿Está seguro de actualizar este Producto?')">
                                                            <span class="icon"><i class="fas fa-pencil-alt"></i></span>
                                                            <span class="text">ACTUALIZAR</span>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="eliminar.php?id_producto=<?php echo $producto['id_producto']; ?>" class="btn shadow btn-xxl sharp" style="background-color:#E0322A; color:#fff" onclick="return confirm('¿Está seguro de eliminar este Producto?')">
                                                            <span class="icon"><i class="fas fa-trash-alt"></i></span>
                                                            <span class="text">ELIMINAR</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
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

