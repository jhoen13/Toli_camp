<?php
require_once("../../../../db/conexion.php");
$db = new database();
$conectar = $db->conectar();
session_start();

// Consulta para obtener las categorías
$stmCategorias = $conectar->prepare("SELECT id_categoria, categoria FROM categoria");
$stmCategorias->execute();
$categorias = $stmCategorias->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener los documentos relacionados con los roles de administrador y vendedor
$stmDocumentos = $conectar->prepare("SELECT documento FROM usuarios WHERE id_rol IN ('1 administrador', '3 vendedor')");
$stmDocumentos->execute();
$documentos = $stmDocumentos->fetchAll(PDO::FETCH_ASSOC);

$stmembalaje = $conectar->prepare("SELECT id_embala, embalaje FROM embalaje");
$stmembalaje->execute();
$embalaje = $stmembalaje->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se ha enviado el formulario de actualización
if (isset($_POST["actualizar"]) && $_POST["actualizar"] == "formu") {
    // Obtener los datos del formulario de actualización
    $id_producto = $_POST['id_producto'];
    $nom_produc = $_POST['nom_produc'];
    $descrip = $_POST['descrip'];
    $precio_compra = $_POST['precio_compra'];
    $id_categoria = $_POST['id_categoria'];
    $cantidad = $_POST['cantidad'];
    $id_embala = $_POST['id_embala'];
    $precio_ven = $_POST['precio_ven'];
    $documento = $_POST['documento'];
    $barcode = $_POST['barcode'];

    // Verificar si se ha enviado un archivo
    if (!empty($_FILES['foto']['name'])) {
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombre = "producto_" . time();
        $foto_nombre = $nombre . "." . $extension;
        $ruta_destino = "../../../../assets/img/img_produc/$foto_nombre";

        // Mueve el archivo a la ruta de destino
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
    } else {
        // Si no se envió un archivo, mantener la foto actual
        $foto_nombre = $_POST['foto_actual'];
    }

    // Realizar la actualización en la base de datos
    $actualizarSql = $conectar->prepare("UPDATE productos SET nom_produc = '$nom_produc', descrip = '$descrip', precio_compra = '$precio_compra', id_categoria = '$id_categoria', cantidad = '$cantidad', id_embala = '$id_embala', foto = '$foto_nombre', precio_ven = '$precio_ven', documento = '$documento', barcode = '$barcode' WHERE id_producto = '$id_producto'");
    $actualizarSql->execute();

    // Redirigir a la página de productos después de la actualización
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../../../controller/img/icono.png" type="image/x-icon">
    <title>Formulario de Actualización de Productos</title>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Agrega aquí los campos ocultos para pasar la información actual -->
                    <input type="hidden" name="actualizar" value="formu">
                    <input type="hidden" name="id_producto" value="<?= $usua['id_producto'] ?>">
                    <input type="hidden" name="foto_actual" value="<?= $usua['foto'] ?>">

                    <label for="nom_produc">Nombre Del Producto</label>
                    <input id="nom_produc" type="text" class="form-control" name="nom_produc" value="<?= $usua['nom_produc'] ?>" placeholder="Ingresa un producto">
                </div>
                <!-- Agrega aquí los demás campos del formulario con sus valores actuales -->
                <input type="submit" class="btn btn-success" value="Actualizar Producto">
                <div class="modal-footer"><a href="index-vende.php" class="btn btn-primary btn-margin">Volver</a></div>
            </form>
        </div>
    </div>
</body>
</html>

<script type="text/javascript">
    $(function(){
        $('#registro').click(function(e){

            var valid = this.form.checkValidity();

            if(valid){

                var nombre = $('#nom_produc').val();
                var codigo = $('#codigo_barras').val();        

                e.preventDefault();    

                $.ajax({
                    type: 'POST',
                    data: {nom_produc: nom_produc, codigo_barras: codigo_barras},
                    success: function(data){
                        Swal.fire({
                                'title': '¡Mensaje!',
                                'text': data,
                                'icon': 'success',
                                'showConfirmButton': 'false',
                                'timer': '1500'
                                }).then(function() {
                    window.location = "index.php";
                });
                            
                    } ,
                    
                    error: function(data){
                        Swal.fire({
                                'title': 'Error',
                                'text': data,
                                'icon': 'error'
                                })
                    }
                });

                
            }else{
                
            }

        });
    });
</script>

<script src="../js/sweetalert2.all.js"></script>
<script src="../js/sweetalert2.all.min.js"></script>
