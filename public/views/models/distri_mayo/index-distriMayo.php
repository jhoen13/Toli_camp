<?php
// Habilitar la visualización de errores PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); // Iniciar la sesión

// Se importa el archivo de conexión a la base de datos
require_once("../../../db/conexion.php");

// Se instancia la clase Database para la conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Asegúrate de que la sesión esté iniciada y que 'document' esté definida
if (isset($_SESSION['document'])) {
    $documento = $_SESSION['document'];

    // Corrige la consulta SQL
    $sql = $con->prepare("SELECT * FROM usuarios AS u JOIN roles AS r ON u.id_rol = r.id_rol WHERE u.documento = :documento");
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
    $consulta2 = $con->prepare("SELECT fecha_ingre, hora_ingre, codi_ingre FROM ingreso WHERE documento = :documento ORDER BY id_ingreso DESC LIMIT 1");
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
    $consulta3 = $con->prepare("UPDATE ingreso SET fecha_sali = :fecha_salida, hora_sali = :hora_salida, durac = :duracion WHERE documento = :documento AND codi_ingre = :codi_ingre");
    $consulta3->bindParam(":fecha_salida", $fecha_salida);
    $consulta3->bindParam(":hora_salida", $hora_salida);
    $consulta3->bindParam(":duracion", $duracion);
    $consulta3->bindParam(":documento", $documento);
    $consulta3->bindParam(":codi_ingre", $codi_ingre);
    $consulta3->execute();

    session_destroy();  // Se cierra la sesión del usuario
    header("Location:../../../../index.html");
}

// Obtiene el documento de la sesión del usuario
$document = $_SESSION['document'];


// Consulta SQL para obtener la información del usuario logueado
$user = $con->prepare("SELECT * FROM usuarios WHERE documento = '$document'");
$user->execute();
$respuesta = $user->fetch(PDO::FETCH_ASSOC);

// Consulta SQL para obtener la información de la tabla ingreso
$user_log = $con->prepare("SELECT * FROM ingreso INNER JOIN usuarios INNER JOIN roles ON ingreso.documento = usuarios.documento AND usuarios.id_rol = roles.id_rol WHERE roles.id_rol >= 1");
$user_log->execute();
$entra = $user_log->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="robots" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template">
    <meta property="og:title" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template">
    <meta property="og:description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template">
    <meta property="og:image" content="https://fillow.dexignlab.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">
    <!-- PAGE TITLE HERE -->
    <title>Admin <?php echo $_SESSION['name'] ?></title>
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="../../../assets/img/logo.png">
    <!-- Datatable -->
    <link href="../../../../vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="../../../../vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="../../../../css/style.css" rel="stylesheet">
</head>

<body class="con" style="font-family: 'Times New Roman', Times, serif;">
    <!--****** Main wrapper start *******-->
    <div id="main-wrapper">
        <!--****** Nav header start ***********-->
        <div class="nav-header">
            <a href="./index-admin.php" class="brand-logo">
                <img src="../../../assets/img/logo.png" style="border-radius: 20px; width: 600px;" alt="logo Toli-Camp" class="logo-abbr">
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
                                D.Mayotista <?php echo $respuesta['nombre'] ?>
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            
                            <!-- INICIO PERFIL -->
                            <li class="nav-item dropdown  header-profile">
                                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                    <img alt="Foto perfil del usuario" width="100" src="../../../assets/img/img_user/<?= $respuesta["foto"] ?>">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="./perfil.php" class="dropdown-item ai-icon">
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
                        <a class="has-arrow " href="./index-distriMayo.php" aria-expanded="false">
                            <i class="fas fa-home"></i>
                            <span class="nav-text">INICIO</span>
                        </a>
                    </li>
                    <!-- MODULO PARA VER PERFIL -->
                    <li>
                        <a class="has-arrow " href="./perfil.php" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <span class="nav-text">PERFIL</span>
                        </a>
                    </li>
                    <!-- MODULO ventas -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="nav-text">VENTA</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./ventas.php">Crear Venta</a></li>
                            <li><a href="./producto/reporteventa.php">Reportes de Ventas</a></li>
                        </ul>
                    </li>
                    <!-- MODULO Compras -->
                    <li>
                        <a class="has-arrow " href="./reportecompras.php"  aria-expanded="false">
                            <i class="fas fa-user-check"></i>
                            <span class="nav-text">COMPRAS</span>
                        </a>
                    
                    </li>

                    <!-- MODULO DE PRODUCTOS -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="nav-text">PRODUCTOS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./producto/index.php">Listar Productos</a></li>
                            <li><a href="./producto/crear.php">crear Productos</a></li>
                        </ul>
                    </li>
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
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Mis</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Clientes</a></li>
                    </ol>
                </div>
                <div class="row">

                    <div class="col-12" id="ingreso">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Actividad</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Documento</th>
                                                <th>Correo</th>
                                                <th>Rol</th>
                                                <th>Fecha ingreso</th>
                                                <th>Hora ingreso</th>
                                                <th>Fecha salida</th>
                                                <th>Hora salida</th>
                                                <th>Duración</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($entra as $entrada) { ?>
                                                <tr>
                                                    <td><?= $entrada["id_ingreso"] ?></td>
                                                    <td><?= $entrada["documento"] ?></td>
                                                    <td><?= $entrada["correo_electronico"] ?></td>
                                                    <td><?= $entrada["tipo_rol"] ?></td>
                                                    <td><?= $entrada["fecha_ingre"] ?></td>
                                                    <td><?= $entrada["hora_ingre"] ?></td>
                                                    <td><?= $entrada["fecha_sali"] ?></td>
                                                    <td><?= $entrada["hora_sali"] ?></td>
                                                    <td><?= $entrada["durac"] ?></td>
                                                </tr>

                                            <?php
                                            }
                                            ?>

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

    <!--**** Scripts *******-->
    <!-- Required vendors -->
    <script src="../../../../vendor/global/global.min.js"></script>
    <script src="../../../../vendor/chart.js/Chart.bundle.min.js"></script>
    <!-- Apex Chart -->
    <script src="../../../../vendor/apexchart/apexchart.js"></script>
    <!-- Datatable -->
    <script src="../../../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../../../js/plugins-init/datatables.init.js"></script>
    <script src="../../../../vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
    <script src="../../../../js/custom.min.js"></script>
    <script src="../../../../js/dlabnav-init.js"></script>
    <script src="../../../../js/demo.js"></script>
    <script src="../../../../js/styleSwitcher.js"></script>
</body>

</html>