<?php
session_start();
require_once("../../../db/conexion.php");
$db = new Database();
$con = $db->conectar();

// Verificar si el usuario está logueado
if (!isset($_SESSION['document'])) {
    header("Location: login.php"); // Redirigir a la página de login si no está logueado
    exit;
}

// Obtener el documento del usuario logueado
$user = $_SESSION['document'];

// Obtener la fecha actual en formato 'Y-m-d'
$fecha_venta = date('Y-m-d');

// Función para generar un ID único de venta
function generarIDVenta()
{
    $random_number = mt_rand(100000, 999999); // Generar un número aleatorio de 6 dígitos
    return date('Ymd') . $random_number; // Concatenar el número aleatorio con la fecha actual
}

if (isset($_POST['agregar']) && isset($_POST['producto']) && isset($_POST['cantidad'])) {
    $id_producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];

    // Obtener información del producto de la base de datos
    $sql_producto = $con->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $sql_producto->execute([$id_producto]);
    $producto = $sql_producto->fetch(PDO::FETCH_ASSOC);

    // Verificar si la cantidad de producto está disponible
    if ($producto['cantidad'] < $cantidad) {
        echo '<script>alert("Cantidad no disponible");</script>';
    } else {
        // Resto de la lógica para agregar el producto al carrito
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = array();
        }

        // Verificar si el producto ya está en el carrito
        if (isset($_SESSION['carrito'][$id_producto])) {
            // Si el producto ya está en el carrito, actualizamos la cantidad
            $_SESSION['carrito'][$id_producto]['cantidad'] += $cantidad;
        } else {
            // Si el producto no está en el carrito, lo agregamos
            $_SESSION['carrito'][$id_producto] = array(
                'id_producto' => $id_producto,
                'nom_produc' => $producto['nom_produc'],
                'descrip' => $producto['descrip'],
                'precio_compra' => $producto['precio_compra'],
                'id_categoria' => $producto['id_categoria'],
                'cantidad' => $cantidad,
                'id_embala' => $producto['id_embala'],
                'foto' => $producto['foto'],
                'precio_ven' => $producto['precio_ven'],
                'documento' => $producto['documento'],
            );

            // Descuento de la cantidad en la base de datos
            $sql_descuento = $con->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id_producto = ?");
            $sql_descuento->execute([$cantidad, $id_producto]);
        }
    }
}

// Limpiar el carrito
if (isset($_POST['limpiar_carrito'])) {
    unset($_SESSION['carrito']);
    header("Location: ventas.php"); // Redirigir nuevamente a la página de ventas
    exit;
}

// Terminar la venta
if (isset($_POST['terminar_venta']) && isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    // Obtener el ID de la venta manualmente ingresado por el usuario
    $id_venta = $_POST['id_venta'];

    // Calcular el total de la venta y guardar los detalles en la tabla detalle_venta
    $total_venta = 0;
    foreach ($_SESSION['carrito'] as $id_producto => $producto) {
        $subtotal = $producto['precio_ven'] * $producto['cantidad'];
        $total_venta += $subtotal;

        // Verificar si la cantidad de producto está disponible
        $sql_producto = $con->prepare("SELECT cantidad FROM productos WHERE id_producto = ?");
        $sql_producto->execute([$id_producto]);
        $producto_info = $sql_producto->fetch(PDO::FETCH_ASSOC);

        if ($producto_info['cantidad'] < $producto['cantidad']) {
            echo '<script>alert("Cantidad no disponible");</script>';
            header("Location: ventas.php");
            exit;
        }

        // Guardar el detalle de la venta en la tabla detalle_venta
        $sql_insert_detalle = $con->prepare("INSERT INTO det_venta (id_venta, id_producto, cantidad, sub_tot) VALUES (?, ?, ?, ?)");
        $sql_insert_detalle->execute([$id_venta, $id_producto, $producto['cantidad'], $subtotal]);

        // Descontar la cantidad del producto del inventario
        $sql_update_inventario = $con->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id_producto = ?");
        $sql_update_inventario->execute([$producto['cantidad'], $id_producto]);
    }

    // Guardar la información de la venta en la tabla venta
    $sql_insert_venta = $con->prepare("INSERT INTO ventas (id_venta, fecha, tot_ven, docu_ven, docu_clien) VALUES (?, ?, ?, ?, ?)");
    $sql_insert_venta->execute([$id_venta, $fecha_venta, $total_venta, $user, $user]);

    // Vaciar el carrito después de completar la venta
    unset($_SESSION['carrito']);

    // Redirigir a una página de confirmación o listado de ventas
    header("Location: ventas.php?venta_completada=true");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Ventas | Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="Shortcut Icon" type="image/x-icon" href="assets/icons/book.ico" />
    <link rel="stylesheet" href="css/sweet-alert.css">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../../css/listar.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="js/jquery-1.11.2.min.js"><\/script>')
    </script>
    <script src="js/modernizr.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/main.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-lateral {
            background-color: #343a40;
            color: #fff;
            /* Ajusta otros estilos según tus necesidades */
        }

        .navbar-lateral a {
            color: #fff;
        }

        .container-fluid {
            padding: 20px;
        }

        .breadcrumb {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
        }

        .container-flat-form {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-info,
        .btn-danger,
        .btn-success {
            margin-right: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        tfoot {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="navbar-lateral full-reset">
        <div class="visible-xs font-movile-menu mobile-menu-button"></div>
        <div class="full-reset container-menu-movile custom-scroll-containers">
            <div class="logo full-reset all-tittles">
            </div>
            <div class="full-reset" style="padding: 10px 0; color:#fff;">
                <p class="text-center" style="padding-top: 15px;">Menu</p>
            </div>
            <div class="dropdown-menu-button">&nbsp;&nbsp; Compras</div>
            <ul class="list-unstyled">
                <li><a href="compras.php">&nbsp;&nbsp; Nueva compra</a></li>
                <li><a href="miscompras.php">&nbsp;&nbsp; Mis compras</a></li>
            </ul>
            </li>
            <li>
                <div class="dropdown-menu-button">&nbsp;&nbsp; Ventas</div>
                <ul class="list-unstyled">
                    <li><a href="ventas.php">&nbsp;&nbsp; Nueva venta</a></li>
                    <li><a href="misventas.php">&nbsp;&nbsp; Mis ventas</a></li>
                </ul>
            </li>
            <li><a href="inventario.php">&nbsp;&nbsp; Inventario</a></li>
            <li><a href="reporte.php">&nbsp;&nbsp; Reportes</a></li>
            </ul>
        </div>
    </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9 col-md-8 content-page-container full-reset custom-scroll-containers">
                <div class="container">
                    <br>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 lead">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Nueva venta</li>
                                <li class="breadcrumb-item"><a href="listaven.php">Listado de ventas</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="container-flat-form">
                        <div class="title-flat-form title-flat-blue">Nueva venta</div>
                        <form action="ventas.php" method="post" class="row">
                            <div class="col-12 col-md-8">
                                <div class="form-group">
                                    <label for="producto">Producto</label>
                                    <select class="form-control" name="producto" id="producto" required>
                                        <option value="" disabled selected>SELECCIONE PRODUCTO</option>
                                        <?php
                                        $sql_products = $con->prepare("SELECT * FROM productos");
                                        $sql_products->execute();
                                        foreach ($sql_products as $fila) {
                                        ?>
                                            <option value="<?php echo ($fila['id_producto']) ?>"><?php echo ($fila['nom_produc']) ?></option>
                                        <?php
                                        };
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="cantidad">Cantidad</label>
                                    <input class="form-control" name="cantidad" type="number" min="1" value="1" placeholder="Cantidad" required>
                                </div>

                                <?php if (isset($error_msg)) { ?>
                                    <div class="container mt-4">
                                        <div class="row">
                                            <div class="col">
                                                <div class="alert alert-danger" role="alert">
                                                    <?php echo $error_msg; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="form-group">
                                    <button type="submit" name="agregar" class="btn btn-info">Agregar al carrito</button>
                                    <button type="submit" name="limpiar_carrito" class="btn btn-danger">Limpiar Carrito</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="container-flat-form">
                        <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) { ?>
                            <h2>Carrito de compras</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Descripcion</th>
                                        <th>Categoria</th>
                                        <th>Cantidad a comprar</th>
                                        <th>Tipo de embalaje</th>
                                        <th>Precio de la venta</th>
                                        <th>Documento</th>
                                        <th>subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_venta = 0;
                                    foreach ($_SESSION['carrito'] as $id_producto => $producto) {
                                        $subtotal = $producto['precio_compra'] * $producto['cantidad'];
                                        $total_venta += $subtotal;
                                    ?>
                                        <tr>
                                            <td><?php echo $producto['nom_produc']; ?></td>
                                            <td><?php echo $producto['descrip']; ?></td>
                                            <td><?php echo $producto['id_categoria']; ?></td>
                                            <td><?php echo $producto['cantidad']; ?></td>
                                            <td><?php echo $producto['id_embala']; ?></td>
                                            <td><?php echo $producto['precio_ven']; ?></td>
                                            <td><?php echo $subtotal; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total</strong></td>
                                        <td><?php echo $total_venta; ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
