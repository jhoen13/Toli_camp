<?php
session_start();
require_once("../../../db/conexion.php");
$db = new Database();
$con = $db->conectar();

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['document'])) {
    $nombreUsuario = $_SESSION['document'];
    $mensajeBienvenida = "Bienvenido, $nombreUsuario!";
} else {
    // Redirigir a la página de inicio de sesión si no ha iniciado sesión
    header("Location: index.php");
    exit();
}

// Obtener todas las categorías
$sql_categorias = $con->query("SELECT * FROM categoria");
$categorias = $sql_categorias->fetchAll(PDO::FETCH_ASSOC);

// Inicializar el filtro de categoría
$id_categoria = null;

// Verificar si se envió el formulario de filtro
if (isset($_POST['filtrar_categoria'])) {
    $id_categoria = $_POST['categoria'];
}

// Filtrar productos por categoría si se selecciona una categoría
if ($id_categoria !== null && $id_categoria !== '') {
    $sql_productos = $con->prepare("SELECT * FROM productos WHERE id_categoria = ?");
    $sql_productos->execute([$id_categoria]);
    $productos = $sql_productos->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si la categoría seleccionada tiene productos
    if (empty($productos)) {
        echo '<p style="text-align: center; color: red; font-weight: bold;">Categoría vacía</p>';
    }
} else {
    // Obtener todos los productos si no hay filtro
    $sql_productos = $con->query("SELECT * FROM productos");
    $productos = $sql_productos->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Tienda Virtual</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
            body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #333;
            overflow: hidden;
        }

        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        h2 {
            text-align: center;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        select {
            padding: 8px;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 15px;
            width: 200px;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product-info {
            text-align: center;
            margin-top: 10px;
        }

        .buy-button {
            background-color: #28a745;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .empty-category {
            text-align: center;
            font-weight: bold;
            color: red;
        }

        /* Estilos para el botón fijo */
        .fixed-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .fixed-button:hover {
            background-color: #0056b3;
        }

        /* Estilos para el botón de redirección */
        .redirect-button {
            background-color: #17a2b8;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .redirect-button:hover {
            background-color: #138496;
        }
    </style>
</head>

<body>
    <nav>
        <a href="#inicio">Inicio</a>
        <a href="clientes.php">Clientes</a>
        <a href="crearproduc.php">Productos</a>
        <a href="ventas.php">Ventas</a>
        <a href="compras.php">Compra</a>
    </nav>
    <div clas  s="container">
        <h2>Filtrar Productos por Categoría</h2>

        <!-- Mostrar mensaje de bienvenida -->
        <p><?php echo $mensajeBienvenida; ?></p>

        <form method="post" action="">
            <label for="categoria">Seleccionar Categoría:</label>
            <select name="categoria" id="categoria">
                <option value="">Todas las Categorías</option>
                <?php foreach ($categorias as $categoria) : ?>
                    <option value="<?php echo $categoria['id_categoria']; ?>" <?php echo ($id_categoria == $categoria['id_categoria']) ? 'selected' : ''; ?>><?php echo $categoria['categoria']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="filtrar_categoria">Buscar</button>
        </form>

        <h3>Lista de Productos</h3>

        <?php if (!empty($productos)) : ?>
            <div class="product-container">
                <?php foreach ($productos as $producto) : ?>
                    <div class="product-card">
                        <?php
                        // Construir la ruta relativa de la imagen
                        $imagen = $producto['foto'];
                        $ruta_relativa = '../../../assets/img/img_produc/' . $imagen;
                        

                        if (file_exists($ruta_relativa)) {
                        ?>
                            <img src="<?php echo $ruta_relativa; ?>" alt="<?php echo $producto['nom_produc']; ?>">
                        <?php } else { ?>
                            <p style="color: red;">Imagen no disponible. Ruta: <?php echo $ruta_relativa; ?></p>
                        <?php } ?>

                        <div class="product-info">
                            <h4><?php echo $producto['nom_produc']; ?></h4>
                            <p><?php echo $producto['descrip']; ?></p>
                            <p>Precio: $<?php echo $producto['precio_ven']; ?></p>
                            <p>Código de Barras: <?php echo $producto['codigo_barras']; ?></p>
                            <a href="ventas.php?id=<?php echo $producto['id_producto']; ?>" class="buy-button">Comprar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <?php if ($id_categoria !== null && $id_categoria !== '') : ?>
                <p class="empty-category">No hay productos en esta categoría</p>
            <?php else : ?>
                <p class="empty-category">No hay productos disponibles</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Botón fijo para cerrar sesión -->
    <button class="fixed-button" onclick="cerrarSesion()">Cerrar Sesión</button>

    <!-- Botón de redirección -->
    <button class="redirect-button" onclick="redireccionar()">Ir a Otra Página</button>

    <button class="redirect-button" onclick="redire()">Editar datos</button>


    <script>
        // Función para cerrar la sesión
        function cerrarSesion() {
            // Realiza cualquier lógica necesaria para cerrar la sesión, por ejemplo, limpiar variables de sesión
            // Después redirige a la página de inicio de sesión
            window.location.href = "index.html";
        }

        // Función para redireccionar
        function redireccionar() {
            // Cambia la URL a la que deseas redirigir
            window.location.href = "reportecompras.php";
        }

        function redire() {
            // Cambia la URL a la que deseas redirigir
            window.location.href = "perfil.php";
        }
    </script>
</body>

</html>

</body>
</html>
