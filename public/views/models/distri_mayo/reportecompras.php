<?php
session_start();
require_once("../../../db/conexion.php");

$db = new Database();
$con = $db->conectar();

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['document'])) {
    $idUsuario = $_SESSION['document'];

    // Consultar el nombre del usuario
    $sql_nombre_usuario = $con->prepare("SELECT nombre FROM usuarios WHERE documento = ?");
    $sql_nombre_usuario->execute([$idUsuario]);
    $resultado_nombre = $sql_nombre_usuario->fetch(PDO::FETCH_ASSOC);

    $nombreUsuario = $resultado_nombre['nombre'];

    // Consultar las compras del usuario
    $sql_compras = $con->prepare("SELECT c.id_compra, c.fecha, c.total
                                  FROM compras c
                                  WHERE c.docu_clien = ?");
    $sql_compras->execute([$idUsuario]);
    $compras = $sql_compras->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Historial de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        h2 {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Estilos para los botones */
        .boton {
            display: inline-block;
            padding: 10px;
            margin: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
        }

        .boton:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Historial de Compras de <?php echo $nombreUsuario; ?></h2>

        <?php if (!empty($compras)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID de Compra</th>
                        <th>Fecha de Compra</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compras as $compra) : ?>
                        <tr>
                            <td><?php echo $compra['id_compra']; ?></td>
                            <td><?php echo $compra['fecha']; ?></td>
                            <td>$<?php echo $compra['total']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Botones -->
            <a href="#" class="boton" onclick="window.history.back()">Volver</a>
        <?php else : ?>
            <p>No hay historial de compras.</p>
        <?php endif; ?>
    </div>
</body>

</html>
