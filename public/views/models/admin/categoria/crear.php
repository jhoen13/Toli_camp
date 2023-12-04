<?php  
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
            <form action="" method="post" > <!-- Añade enctype="multipart/form-data" para el formulario -->
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
</html>

