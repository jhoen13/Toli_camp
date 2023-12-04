<?php
// Habilitar la visualización de errores PHP
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); // Iniciar la sesión

// Se importa el archivo de conexión a la base de datos
require_once("../../../../db/conexion.php");

// Se instancia la clase Database para la conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Asegúrate de que la sesión esté iniciada y que 'document' esté definida
if (isset($_SESSION['document'])) {
    $documento = $_SESSION['document'];

    // Corrige la consulta SQL
    $sql = $con->prepare("SELECT * FROM usuarios AS u
        JOIN roles AS r ON u.id_rol = r.id_rol
        WHERE u.documento = :documento");
    $sql->bindParam(":documento", $documento, PDO::PARAM_STR);
    $sql->execute();
    $usua = $sql->fetch();
} else {
    // Manejar el caso en el que la sesión no esté iniciada o 'document' no esté definida
    echo "La sesión no está iniciada o falta 'documento'";
    exit(); // Agrega un exit() para detener la ejecución del script en este punto
}

// Validación de sesión (código comentado)
// require_once "../../auth/validationSession.php";

// Cierre de sesión al presionar 'btncerrar'
if (isset($_POST['btncerrar'])) {
    session_destroy();
    header("Location:../../../../../index.html");
}

// Obtiene el documento de la sesión del usuario
$document = $_SESSION['document'];

// Consulta SQL para obtener la información del usuario logueado
$user = $con->prepare("SELECT * FROM usuarios WHERE documento = '$document'");
$user->execute();
$respuesta = $user->fetch(PDO::FETCH_ASSOC);

// Consulta SQL para obtener la información del tipo de documento
$stm = $con->prepare("SELECT * FROM tipdocu");
$stm->execute();
$documento = $stm->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
if (isset($_GET['id_tipdocu'])) {
    $txtid = $_GET['id_tipdocu'];

    $stm = $con->prepare("DELETE FROM tipdocu WHERE id_tipdocu = :id_tipdocu");
    $stm->bindParam(":id_tipdocu", $txtid, PDO::PARAM_INT);
    $stm->execute();

    header("location: index.php");
}


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
    <link rel="shortcut icon" type="image/png" href="../../../../assets/img/logo.png">
    <!-- Datatable -->
    <link href="../../../../../vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="../../../../../vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="../../../../../css/style.css" rel="stylesheet">
</head>
<!-- <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de documentos</title>
    Agrega los estilos de Bootstrap
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
</head> -->
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
                        Administrador <?php echo $respuesta['nombre'] ?>
                    </div>
                </div>
                <!-- INICIO PERFIL -->
                <li class="nav-item dropdown  header-profile">
                                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                    <img src="../../../../assets/img/img_user/profile-img.jpg" width="100" alt="imgagen del usuario">
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
                        <a class="has-arrow " href="./index-admin.php" aria-expanded="false">
                            <i class="fas fa-home"></i>
                            <span class="nav-text">HOME</span>
                        </a>
                    </li>
                    <!-- MODULO PARA VER PERFIL -->
                    <li>
                        <a class="has-arrow " href="./perfil.php" aria-expanded="false">
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
                            <li><a href="./usuarios/index.php">Listar Usuarios</a></li>
                            <li><a href="./usuarios/crear.php">Crear Usuarios</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE CATEGORIAS -->
                    <li>
                        <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-folder"></i>
                            <span class="nav-text">CATEGORIAS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./categoria/index.php">Lista Categorias</a></li>
                            <li><a href="./categoria/crear.php">crear Categorias</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE DOCUMENTOS -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-file"></i>
                            <span class="nav-text">DOCUMENTOS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./documentos/index.php">Lista Documentos</a></li>
                            <li><a href="./documentos/crear.php">crear Documentos</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE EMBALAJE -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-box"></i>
                            <span class="nav-text">EMBALAJE</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./embalaje/index.php">Listar Embalaje</a></li>
                            <li><a href="./embalaje/crear.php">crear Embalaje</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE GENEROS -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-venus-mars"></i>
                            <span class="nav-text">GENEROS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./genero/index.php">Listar Generos</a></li>
                            <li><a href="./genero/crear.php">crear Generos</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE PRODUCTOS -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="nav-text">PRODUCTOS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./producto/producto.php">Listar Productos</a></li>
                            <li><a href="./producto/crear.php">crear Productos</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE ROLES -->
                    <li>
                        <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-users-cog"></i>
                            <span class="nav-text">ROLES</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./roles/index.php">Listar Roles</a></li>
                            <li><a href="./roles/crear.php">crear Roles</a></li>
                        </ul>
                    </li>
                    <!-- MODULO DE ESTADISTICAS -->
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                            <i class="fas fa-chart-line"></i>
                            <span class="nav-text">ESTADISTICAS</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="index.html">Partidas</a></li>
                            <li><a href="index.html">Usuarios Bloqueados</a></li>
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
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID documento</th>
                        <th scope="col">Tipo de documentos</th>
                        <th colspan="2">Acciones</th>
                        
                </thead>
                <tbody>
                    <?php foreach ($documento as $docume) { ?>
                        <tr>
                            <td scope="row"><?php echo $docume['id_tipdocu']; ?></td>
                            <td><?php echo $docume['tipdocu']; ?></td>
                            <td>
                                <a href="eliminar.php?id_tipdocu=<?php echo $docume['id_tipdocu']; ?>" class="btn btn-danger btn-margin">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                
            </table>
            <a href="crear.php" class="btn btn-success btn-margin">Crear un tipo de documento</a>
            <a href="../../../../views/models/admin/index-admin.php" class="btn btn-primary btn-margin">Volver</a>
        </div>

</body>
</html>
