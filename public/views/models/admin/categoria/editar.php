<?php
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
                    <span aria-hidden=true" times="" ></span>
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
</html>
