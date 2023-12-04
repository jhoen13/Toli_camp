<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

$genero = '';
$old_genero = '';

if (isset($_GET['id_genero']) && is_numeric($_GET['id_genero']) && $_GET['id_genero'] > 0) {
    $id_genero = $_GET['id_genero'];
    $stmt = $conectar->prepare("SELECT * FROM genero WHERE id_genero = :id_genero");
    $stmt->bindParam(':id_genero', $id_genero);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $old_genero = $result['genero'];
    } else {
        echo '<script> alert ("ID de genero no válido");</script>';
        echo '<script> window.location="index.php"</script>';
    }
}

if ((isset($_POST["registro"])) && ($_POST["registro"] == "formu")) {
    $genero = $_POST['genero'];

    $validar = $conectar->prepare("SELECT * FROM genero WHERE genero = :genero");
    $validar->bindParam(':genero', $genero);
    $validar->execute();
    $filaa1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if (empty($genero)) {
        echo '<script> alert ("EXISTEN DATOS VACÍOS");</script>';
        echo '<script> window.location="index.php"</script>';
    } elseif (count($filaa1) > 0) {
        echo '<script> alert ("el genero ya existe");</script>';
        echo '<script> window.location="index.php"</script>';
    } else {
        // Actualización
        $updatesql = $conectar->prepare("UPDATE genero SET genero = :genero WHERE id_genero = :id_genero");
        $updatesql->bindParam(':genero', $genero);
        $updatesql->bindParam(':id_genero', $id_genero);
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
    <title>Formulario de actualización de genero</title>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar genero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <label for="genero">Nombre Del genero</label>
                    <input id="genero" type="text" class="form-control" name="genero" value="<?php echo $old_genero; ?>" placeholder="Ingresa un genero" required>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value="Actualizar genero">
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
