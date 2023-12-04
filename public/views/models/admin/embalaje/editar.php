<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

$embalaje = '';
$old_embalaje = '';

if (isset($_GET['id_embala']) && is_numeric($_GET['id_embala']) && $_GET['id_embala'] > 0) {
    $id_embala = $_GET['id_embala'];
    $stmt = $conectar->prepare("SELECT * FROM embalaje WHERE id_embala = :id_embala");
    $stmt->bindParam(':id_embala', $id_embala);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $old_embalaje = $result['embalaje'];
    } else {
        echo '<script> alert ("ID de embalaje no válido");</script>';
        echo '<script> window.location="index.php"</script>';
    }
}

if ((isset($_POST["registro"])) && ($_POST["registro"] == "formu")) {
    $embalaje = $_POST['embalaje'];

    $validar = $conectar->prepare("SELECT * FROM embalaje WHERE embalaje = :embalaje");
    $validar->bindParam(':embalaje', $embalaje);
    $validar->execute();
    $filaa1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if (empty($embalaje)) {
        echo '<script> alert ("EXISTEN DATOS VACÍOS");</script>';
        echo '<script> window.location="index.php"</script>';
    } elseif (count($filaa1) > 0) {
        echo '<script> alert ("el embalaje ya existe");</script>';
        echo '<script> window.location="index.php"</script>';
    } else {
        // Actualización
        $updatesql = $conectar->prepare("UPDATE embalaje SET embalaje = :embalaje WHERE id_embala = :id_embala");
        $updatesql->bindParam(':embalaje', $embalaje);
        $updatesql->bindParam(':id_embala', $id_embala);
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
    <title>Formulario de actualización de embalaje</title>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar embalaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <label for="embalaje">Nombre Del embalaje</label>
                    <input id="embalaje" type="text" class="form-control" name="embalaje" value="<?php echo $old_embalaje; ?>" placeholder="Ingresa un embalaje" required>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value="Actualizar embalaje">
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
