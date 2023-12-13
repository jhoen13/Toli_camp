<?php
require_once("../../../db/conexion.php");
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

if ((isset($_POST["registro"])) && ($_POST["registro"] == "formu")) {
    $nom_produc = $_POST['nom_produc'];
    $descrip = $_POST['descrip'];
    $precio_compra = $_POST['precio_compra'];
    $id_categoria = $_POST['id_categoria'];
    $cantidad = $_POST['cantidad'];
    $id_embala = $_POST['id_embala'];
    if (isset($_POST['nom_produc']) && isset($_POST['codigo_barras'])) {
        $nom_produc = trim($_POST['nom_produc']);
        $codigo_barras = trim($_POST['codigo_barras']);
    }

    // Verifica si se ha enviado un archivo
    if (!empty($_FILES['foto']['name'])) {
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombre = "producto_" . time();
        $foto_nombre = $nombre . "." . $extension;
        $ruta_destino = "../../../assets/img/img_produc/$foto_nombre";

        // Mueve el archivo a la ruta de destino
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
    } else {
        // Si no se envió un archivo, muestra un mensaje de error y redirige
        echo '<script>alert("No se ha seleccionado una imagen"); window.location="producto.php"</script>';
        exit(); // Detiene la ejecución del script
    }

    $precio_ven = $_POST['precio_ven'];
    $documento = $_POST['documento'];
    $codigo_barras = $_POST['codigo_barras']; // Nuevo campo de código de barras

    $validar = $conectar->prepare("SELECT * FROM productos WHERE nom_produc = '$nom_produc'");
    $validar->execute();
    $filaa1 = $validar->fetchAll(PDO::FETCH_ASSOC);

    if ($nom_produc == "") {
        echo '<script> alert ("EXISTEN DATOS VACÍOS");</script>';
        echo '<script> window.location="producto.php"</script>';
    } else {
        $insertsql = $conectar->prepare("INSERT INTO productos( nom_produc, descrip, precio_compra,id_categoria,cantidad,id_embala,foto,precio_ven,documento,codigo_barras) VALUES ('$nom_produc','$descrip', '$precio_compra','$id_categoria','$cantidad','$id_embala','$foto_nombre','$precio_ven','$documento','$codigo_barras');");
        $insertsql->execute();
        echo '<script>alert("Registro Exitoso");</script>';
        echo '<script> window.location="index-vende.php"</script>';
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
    <title>Formulario de creación de productos</title>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear Un Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <label for="nom_produc">Nombre Del Producto</label>
                    <input id="nom_produc" type="text" class="form-control" name="nom_produc" placeholder="Ingresa un producto">
                </div>
                <div class="modal-body">
                    <label for="descrip">Descripcion Del Producto</label>
                    <input id="descrip" type="text" class="form-control" name="descrip" placeholder="Ingresa una descripcion del producto">
                </div>
                <div class="modal-body">
                    <label for="precio_compra">Precio</label>
                    <input id="precio_compra" type="number" class="form-control" name="precio_compra" placeholder="Ingresa Un Precio Del Producto">
                </div>
                <div class="modal-body">
                    <label for="categoria">Categoría De Los Productos</label>
                    <select id="categoria" class="form-control" name="id_categoria">
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['categoria']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="modal-body">
                    <label for="cantidad">Cantidad Del Producto</label>
                    <input id="cantidad" type="number" class="form-control" name="cantidad" placeholder="Ingresa una cantidad">
                </div>
                <div class="modal-body">
                    <label for="embalaje">Tipo de embalaje</label>
                    <select id="embalaje" class="form-control" name="id_embala">
                        <?php foreach ($embalaje as $embalajes) { ?>
                            <option value="<?php echo $embalajes['id_embala']; ?>"><?php echo $embalajes['embalaje']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>
                </div>
                <div class="modal-body">
                    <label for="precio_ven">Precio venta</label>
                    <input id="precio_ven" type="number" class="form-control" name="precio_ven" placeholder="Precio venta">
                </div>
                <div class="modal-body">
                    <label for="documento">Documento</label>
                    <select id="documento" class="form-control" name="documento">
                        <?php foreach ($documentos as $doc) { ?>
                            <option value="<?php echo $doc['documento']; ?>"><?php echo $doc['documento']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="modal-body">
                    <label for="codigo_barras">Código de Barras</label>
                    <input id="codigo_barras" type="text" class="form-control" name="codigo_barras" placeholder="Ingresa el código de barras">
                </div>
                <input type="submit" class="btn btn-success" value="Crear producto">
                <input type="hidden" name="registro" value="formu">
                <div class="modal-footer"><a href="index-vende.php" class="btn btn-primary btn-margin">Volver</a>
                </div>
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
