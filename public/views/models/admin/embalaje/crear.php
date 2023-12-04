<?php  
    require_once("../../../../db/conexion.php");
    $db = new database();
    $conectar = $db->conectar();
    session_start();

?>

<?php

if ((isset($_POST["registro"])) && ($_POST["registro"] == "formu")) {
    $embalaje = $_POST['embalaje'];

    $validar = $conectar->prepare("SELECT * FROM embalaje WHERE embalaje = '$embalaje'");
    $validar->execute();
    $filaa1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if ($embalaje == "") {
        echo '<script> alert ("EXISTEN DATOS VACÍOS");</script>';
        echo '<script> window.location="index.php"</script>';
    } 
     else {
        $insertsql = $conectar->prepare("INSERT INTO embalaje( embalaje) VALUES ( '$embalaje');");
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
    <title>Formulario de creación de roles </title>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear Un embalaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden=true" times="" ></span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data"> <!-- Añade enctype="multipart/form-data" para el formulario -->
                <div class="modal-body">
                    <label for="embalaje">Nombre Del Embalaje</label>
                    <input id="embalaje" type="text" class="form-control" name="embalaje" placeholder="Ingresa un embalaje">
                </div>
               
                <input type="submit" class="btn btn-success" value="Crear embalaje ">
                <input type="hidden" name="registro" value="formu">
                <div class="modal-footer"><a href="../roles/index.php" class="btn btn-primary btn-margin">Volver</a>
                </div>
            </form>
        </div>
    </div>
</div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

