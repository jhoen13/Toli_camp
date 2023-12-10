<?php
session_start();
require_once("../../db/conexion.php");
$db = new Database();
$con = $db->conectar();

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $contra = $_POST['cont'];
    $conta = $_POST['conta'];

    if ($contra == "" || $conta == "") {
        echo "<script>alert('Ambos campos son obligatorios.');</script>";
        echo '<script>window.location="login.php"</script>';
    } elseif ($contra != $conta) {
        echo "<script>alert('Las contraseñas no coinciden.');</script>";
        echo '<script>window.location="login.php"</script>';
    } else {
        if (!isset($_SESSION['documento']) || empty($_SESSION['documento'])) {
            echo "<script>alert('La sesión no está iniciada, Redirigiendo...');</script>";
            echo '<script>window.location="login.php"</script>';
            exit();
        }

        $docu = $_SESSION['documento'];
        $encriptar = password_hash($contra, PASSWORD_BCRYPT, ["cost" => 15]);
        $conteudo = $con->prepare("UPDATE usuarios SET password = '$encriptar' WHERE documento = '$docu'");
        $conteudo->execute();
        $ff = $conteudo->fetch();
        echo "<script>alert('¡Tu contraseña ha sido cambiada!');</script>";
        echo '<script>window.location="login.php"</script>';
    }
}

?>

<?php
if ((isset($_POST["MM_vali"])) && ($_POST["MM_vali"] == "form2")) 
{
    $documento= $_POST["doc"];
    $usuario= $_POST["usuario"];

    $sql= $con -> prepare ("SELECT * from usuarios where documento='$documento' and correo_electronico='$usuario'");
    $sql-> execute();
    $fila = $sql-> fetch();

    if($fila)
    {
        $_SESSION['documento']=$fila['documento'];

?>
   <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NUEVA CONTRASEÑA</title>
    
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/stylelo.css">
    <link href="../../assets/img/logo.png" rel="icon">
    
</head>

<body>
    <div class="container mt-5">
        <button type="submit" class="btn btn-re btn-xl sharp" style="padding: 5px 10px; font-size: 12px;"> 
            <a href="rectificacion.html" style="color: #28503C;" class="d-flex align-items-center">
                <i class="fas fa-arrow-left mr-2 fa-2x"></i> 
            </a>
        </button>
        <h2>RECUPERAR CONTRASEÑA</h2><br>
            <form method="POST" name="form1" autocomplete="off">

                <div class="form-group">
                    <label for="cont">Nueva Contraseña</label>
                    <input type="password" name="cont" class="form-control" placeholder="Digite la nueva contraseña">
                </div>

                <div class="form-group">
                    <label for="conta">Confirmar Contraseña</label>
                    <input type="password" name="conta" class="form-control" placeholder="Confirme la contraseña">
                </div>
                <div style="  text-align: center;">
                    <input type="submit" name="inicio" value="Validar" class="btn_ing">
                    <input type="hidden" name="MM_update" value="form1">
                </div>

            </form>
        </div>
    </div>

    <!-- Add Bootstrap JS link -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php 
    }
    else{   
        echo '<script>javascript:alert("No se ha podido encontrar los datos");</script>';
        echo '<script>window.location="rectificacion.html"</script>';
        }
}
?>