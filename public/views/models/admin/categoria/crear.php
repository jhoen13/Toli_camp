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

// Consulta SQL para obtener las últimas 6 entradas de usuarios
$userEntry = $conexion->prepare("SELECT * FROM ingreso INNER JOIN usuarios INNER JOIN roles ON ingreso.documento = usuarios.documento AND usuarios.id_rol = roles.id_rol WHERE roles.id_rol >= 1 ORDER BY ingreso.id_ingreso DESC LIMIT 10");
$userEntry->execute();
$entry = $userEntry->fetchAll(PDO::FETCH_ASSOC);


if ((isset($_POST["registro"])) && ($_POST["registro"] == "formu")) {
    $categoria = $_POST['categoria'];

    $validar = $conexion->prepare("SELECT * FROM categoria WHERE categoria = '$categoria'");
    $validar->execute();
    $filaa1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if ($categoria == "") {
        echo '<script> alert ("EXISTEN DATOS VACÍOS");</script>';
        echo '<script> window.location="index.php"</script>';
    } 
     else {
        $insertsql = $conexion->prepare("INSERT INTO categoria (categoria) VALUES ( '$categoria');");
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
        <!--****** Nav header start ***********-->
        <div class="nav-header">
            <a href="../index-admin.php" class="brand-logo">
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
                                Administrador <?php echo $respuesta['nombre'] ?>
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            <!-- CONTENIDO DE LAS ULTIMAS 10 PERSONAS QUE SE LOGUEARON A LA PAGINA -->
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                    <svg width="28" height="28" viewbox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M23.3333 19.8333H23.1187C23.2568 19.4597 23.3295 19.065 23.3333 18.6666V12.8333C23.3294 10.7663 22.6402 8.75902 21.3735 7.12565C20.1068 5.49228 18.3343 4.32508 16.3333 3.80679V3.49996C16.3333 2.88112 16.0875 2.28763 15.6499 1.85004C15.2123 1.41246 14.6188 1.16663 14 1.16663C13.3812 1.16663 12.7877 1.41246 12.3501 1.85004C11.9125 2.28763 11.6667 2.88112 11.6667 3.49996V3.80679C9.66574 4.32508 7.89317 5.49228 6.6265 7.12565C5.35983 8.75902 4.67058 10.7663 4.66667 12.8333V18.6666C4.67053 19.065 4.74316 19.4597 4.88133 19.8333H4.66667C4.35725 19.8333 4.0605 19.9562 3.84171 20.175C3.62292 20.3938 3.5 20.6905 3.5 21C3.5 21.3094 3.62292 21.6061 3.84171 21.8249C4.0605 22.0437 4.35725 22.1666 4.66667 22.1666H23.3333C23.6428 22.1666 23.9395 22.0437 24.1583 21.8249C24.3771 21.6061 24.5 21.3094 24.5 21C24.5 20.6905 24.3771 20.3938 24.1583 20.175C23.9395 19.9562 23.6428 19.8333 23.3333 19.8333Z" fill="#717579"></path>
                                        <path d="M9.9819 24.5C10.3863 25.2088 10.971 25.7981 11.6766 26.2079C12.3823 26.6178 13.1838 26.8337 13.9999 26.8337C14.816 26.8337 15.6175 26.6178 16.3232 26.2079C17.0288 25.7981 17.6135 25.2088 18.0179 24.5H9.9819Z" fill="#717579"></path>
                                    </svg>
                                    <?php
                                    $conteoEntrada = "SELECT COUNT(*) AS conteo FROM ingreso INNER JOIN usuarios ON ingreso.documento = usuarios.documento WHERE usuarios.id_rol >= 1";
                                    try {
                                        $conteos = $conexion->query($conteoEntrada);
                                        $conteo = $conteos->fetch(PDO::FETCH_ASSOC)['conteo'];
                                        if ($conteo) {
                                    ?>
                                            <span class="badge light text-white bg-warning rounded-circle"><?php echo $conteo ?></span>
                                        <?php
                                        } else {
                                        ?>
                                            <span class="badge light text-white bg-warning rounded-circle">0</span>
                                    <?php
                                        }
                                    } catch (PDOException $e) {
                                        echo '<span class="badge light text-white bg-warning rounded-circle"> ' . $e->getMessage();
                                    } ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div id="DZ_W_Notification1" class="widget-media dlab-scroll p-3" style="height:380px;">
                                        <ul class="timeline">
                                            <?php if (!empty($entry)) {
                                                foreach ($entry as $entrada) { ?>
                                                    <li>
                                                        <div class="timeline-panel">
                                                            <div class="media me-2">
                                                                <img alt="image" width="50" src="../../../../assets/img/img_user/<?= $entrada["foto"] ?>">
                                                            </div>
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><?= $entrada['nombre'] ?></h6>
                                                                <small class="d-block"><?= $entrada['fecha_ingre'] ?></small>
                                                                <small class="d-block"><?= $entrada['hora_ingre'] ?></small>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <li>
                                                    <div class="timeline-panel">

                                                        <div class="media-body">
                                                            <h6 class="mb-1">No hay ingreso de usuarios</h6>

                                                        </div>
                                                    </div>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <a class="all-notification" href="../index-admin.php#ingreso">Ver todas<i class="ti-arrow-end"></i></a>
                                </div>
                            </li>
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
                            <li><a href="./index.php">Lista Categorias</a></li>
                            <li><a href="./crear.php">Crear Categorias</a></li>
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
                            <li><a href="../producto/index.php">Lista Productos</a></li>
                            <li><a href="../producto/crear.php">Crear Productos</a></li>
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
                <div class="row">
                    <!-- CONTENIDO TABLA DE USUARIOS -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                <h2 class="modal-title" id="exampleModalLabel">Crear Categoria</h2>
                                    <table id="example3" class="display text-center" style="min-width: 845px">
                                        <form action="" method="post" > 
                                            <div class="modal-body">
                                                <label for="categoria">Nombre De la categoria </label>
                                                <input id="categoria" type="text" class="form-control" name="categoria" placeholder="Ingresa una categoria">
                                            </div>
                                            <div class="modal-footer">
                                            <button type="submit" name="registro" value="formu" class="btn_ing btn btn-margin col-4 mx-auto" style="background-color: #0097B2; color: #ffff;">CREAR CATEGORIA</button> 
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



<!-- <?php
require_once("../../../../db/conexion.php");
$db = new database();
$conexion = $db->conectar();
session_start();
?>

<?php
$stm = $conexion->prepare("SELECT * FROM categoria");
$stm->execute();
$categoria = $stm->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
if (isset($_GET['id_categoria'])) {
    $txtid = $_GET['id_categoria'];

    $stm = $conexion->prepare("DELETE FROM categoria WHERE id_categoria = :id_categoria");
    $stm->bindParam(":id_categoria", $txtid, PDO::PARAM_INT);
    $stm->execute();

    // Redirige después de realizar la eliminación
    header("location: index.php");
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de la categorias </title>
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
                <th scope="col">ID categoria </th>
                <th scope="col">Categoria del producto </th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categoria as $catego) { ?>
                <tr>
                    <td scope="row"><?php echo $catego['id_categoria']; ?></td>
                    <td><?php echo $catego['categoria']; ?></td>
                    <td>
                        <a href="editar.php?id_categoria=<?php echo $catego['id_categoria']; ?> " class="btn btn-success">Editar</a>
                        <a href="index.php?id_categoria=<?php echo $catego['id_categoria']; ?>" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        
    </table>
    <a href="crear.php" class="btn btn-success btn-margin">Crear una categoria</a>
    <a href="../../../../views/models/admin/index-admin.php" class="btn btn-primary btn-margin">Volver</a></div>

</body>
</html> -->


<!-- <?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

$categoria = '';
$old_categoria = '';

if (isset($_GET['id_categoria'])) {
    // Obtener la categoría existente si se proporciona un ID
    $id_categoria = $_GET['id_categoria'];
    $stmt = $conectar->prepare("SELECT * FROM categoria WHERE id_categoria = :id_categoria");
    $stmt->bindParam(':id_categoria', $id_categoria);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $old_categoria = $result['categoria'];
    }
}

if ((isset($_POST["registro"])) && ($_POST["registro"] == "formu")) {
    $categoria = $_POST['categoria'];

    $validar = $conectar->prepare("SELECT * FROM categoria WHERE categoria = :categoria");
    $validar->bindParam(':categoria', $categoria);
    $validar->execute();
    $filaa1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if ($categoria == "") {
        echo '<script> alert ("EXISTEN DATOS VACÍOS");</script>';
        echo '<script> window.location="index.php"</script>';
    } elseif (count($filaa1) > 0) {
        echo '<script> alert ("La categoría ya existe");</script>';
        echo '<script> window.location="index.php"</script>';
    } else {
        // Actualización
        $updatesql = $conectar->prepare("UPDATE categoria SET categoria = :categoria WHERE id_categoria = :id_categoria");
        $updatesql->bindParam(':categoria', $categoria);
        $updatesql->bindParam(':id_categoria', $id_categoria);
        $updatesql->execute();

        echo '<script>alert("Actualización Exitosa");</script>';
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
    <title>Formulario de actualización de categorías</title>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" times="" ></span>
                </button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <label for="categoria">Nombre De la Categoría</label>
                    <input id="categoria" type="text" class="form-control" name="categoria" value="<?php echo $old_categoria; ?>" placeholder="Ingresa una categoría" required>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value="Actualizar Categoría">
                    <input type="hidden" name="registro" value="formu">
                    <a href="index.php" class="btn btn-primary btn-margin">Volver</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> -->

<!-- <?php  
    require_once("../../../../db/conexion.php");
    $db = new database();
    $conectar = $db->conectar();
    session_start();

?>


<?php

if ((isset($_POST["registro"])) && ($_POST["registro"] == "formu")) {
    $categoria = $_POST['categoria'];

    $validar = $conectar->prepare("SELECT * FROM categoria WHERE categoria = '$categoria'");
    $validar->execute();
    $filaa1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if ($categoria == "") {
        echo '<script> alert ("EXISTEN DATOS VACÍOS");</script>';
        echo '<script> window.location="index.php"</script>';
    } 
     else {
        $insertsql = $conectar->prepare("INSERT INTO categoria (categoria) VALUES ( '$categoria');");
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
    <title>Formulario de creación de categorias </title>
</head>
<body>
<div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear Una categoria </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden=true" times="" ></span>
                </button>
            </div>
            <form action="" method="post" > 
                <div class="modal-body">
                    <label for="categoria">Nombre De la categoria </label>
                    <input id="categoria" type="text" class="form-control" name="categoria" placeholder="Ingresa una categoria">
                </div>
                <div class="modal-footer">
                <input type="submit" class="btn btn-success" value="Crear categoria ">
                <input type="hidden" name="registro" value="formu">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> -->

