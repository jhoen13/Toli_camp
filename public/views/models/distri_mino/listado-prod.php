<?php
session_start();
require_once("../../../db/conexion.php");
$db = new Database();
$con = $db->conectar();

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['document'])) {
    $documentoUsuario = $_SESSION['document'];
    $mensajeBienvenida = "Bienvenido, $documentoUsuario!";

    $documento = $_SESSION['document'];

    // Corrige la consulta SQL
    $sql = $con->prepare("SELECT * FROM usuarios AS u
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

// Obtener los productos del usuario logueado
$sql_productos_usuario = $con->prepare("SELECT * FROM productos WHERE documento = ?");
$sql_productos_usuario->execute([$documentoUsuario]);
$productos_usuario = $sql_productos_usuario->fetchAll(PDO::FETCH_ASSOC);

// Realiza una consulta para obtener los nombres de las categorías
$stmCategorias = $con->prepare("SELECT id_categoria, categoria FROM categoria");
$stmCategorias->execute();
$categorias = $stmCategorias->fetchAll(PDO::FETCH_ASSOC);
$categoriasMap = [];
foreach ($categorias as $categoria) {
    $categoriasMap[$categoria['id_categoria']] = $categoria['categoria'];
}

// Nueva consulta para obtener los valores de "embalaje"
$stmEmbalaje = $con->prepare("SELECT id_embala, embalaje FROM embalaje");
$stmEmbalaje->execute();
$embalajes = $stmEmbalaje->fetchAll(PDO::FETCH_ASSOC);
$embalajesMap = [];
foreach ($embalajes as $embalaje) {
    $embalajesMap[$embalaje['id_embala']] = $embalaje['embalaje'];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Listado de Productos</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .button-container {
            margin-top: 20px;
        }

        .button {
            padding: 10px;
            margin-right: 10px;
        }

        .add-button {
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-button,
        .delete-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Listado de Productos del Minorista</h2>
        <!-- Mostrar mensaje de bienvenida -->
        <p><?php echo $mensajeBienvenida; ?></p>

        <!-- Botones para agregar, editar y eliminar -->
        <div class="button-container">
            <button class="button add-button" onclick="agregarProducto()">Agregar Producto</button>
        </div>

        <!-- Tabla de productos -->
        <table>
            <thead>
                <tr>
                <tr>
                    <th scope="col">ID producto</th>
                    <th scope="col">Documento <br> Proveedor </th>
                    <th scope="col">Nombre del Producto</th>
                    <th scope="col">Descripcion</th>
                    <th scope="col">Precio Compra</th>
                    <th scope="col">Precio Venta</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Embalaje</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Codigo De barras</th>
                    
                    </tr>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos_usuario as $producto) : ?>
                    <tr>
                        <td><?php echo $producto['id_producto']; ?></td>
                        <td scope="row"><?php echo $producto['documento']; ?></td>
                        <td><?php echo $producto['nom_produc']; ?></td>
                        <td scope="row"><?php echo $producto['descrip']; ?></td>
                        <td>$ <?php echo $producto['precio_compra']; ?></td>
                        <td>$ <?php echo $producto['precio_ven']; ?></td>
                        <td><?php echo $categoriasMap[$producto['id_categoria']]; ?></td>
                        <td scope="row"><?php echo $producto['cantidad']; ?></td> 
                        <td scope="row"><?php echo $embalajesMap[$producto['id_embala']]; ?></td>
                        <td><img src="../../../../assets/img/img_produc/<?= $producto["foto"] ?>" alt="" style="width: 75px;"></td>
                        <td>
                            <img src="barcode.php?text=<?php echo $producto['barcode']?>&size=40&codetype=Code128&print=true" />
                        </td> 
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <div class="button-container">
                <button class="button" onclick="volver()">Volver</button>
            </div>
        </table>
    </div>

    <!-- Funciones JavaScript para las acciones -->
    <script>
        function agregarProducto() {
            // Redirigir a la página para agregar producto
            window.location.href = "crear.php";
        }
        function volver() {
            // Redirigir a la página anterior
            // window.history.back();
            window.location.href = "index-vende.php";
        }
    </script>
</body>

</html>
