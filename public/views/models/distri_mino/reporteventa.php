<?php
session_start();
require_once("../../../db/conexion.php");


// Incluye el autoloader de Composer para PhpSpreadsheet
require '../../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    $sql_ventas = $con->prepare("SELECT c.id_venta, c.fecha, c.tot_ven, c.docu_ven, c.docu_clien
    FROM ventas c
    WHERE c.docu_ven = ?");
    $sql_ventas->execute([$idUsuario]);
    $ventas = $sql_ventas->fetchAll(PDO::FETCH_ASSOC);
}

// Función para generar y descargar el archivo Excel
function generateExcel($ventas)
{
    // Crear un nuevo objeto PhpSpreadsheet
    $spreadsheet = new Spreadsheet();
    $hojaActiva = $spreadsheet->getActiveSheet();

    // Agregar encabezados a la hoja
    $hojaActiva->setCellValue('A1', 'ID de Venta');
    $hojaActiva->setCellValue('B1', 'Fecha de la venta');
    $hojaActiva->setCellValue('C1', 'Total de la venta');
    $hojaActiva->setCellValue('D1', 'Documento del vendedor');
    $hojaActiva->setCellValue('E1', 'Documento del cliente');

    // Agregar datos de ventas a la hoja
    $fila = 2;
    foreach ($ventas as $venta) {
        $hojaActiva->setCellValue('A' . $fila, $venta['id_venta']);
        $hojaActiva->setCellValue('B' . $fila, $venta['fecha']);
        $hojaActiva->setCellValue('C' . $fila, $venta['tot_ven']);
        $hojaActiva->setCellValue('D' . $fila, $venta['docu_ven']);
        $hojaActiva->setCellValue('E' . $fila, $venta['docu_clien']);
        $fila++;
    }

    // Configurar el encabezado HTTP para la descarga del archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=historial_ventas.xlsx');
    header('Cache-Control: max-age=0');

    // Crear un objeto Writer para el formato Excel (XLSX)
    $writer = new Xlsx($spreadsheet);

    // Enviar el contenido del documento Excel al navegador
    $writer->save('php://output');
    exit();
}

if (isset($_GET['downloadExcel'])) {
    // Si se solicita la descarga en Excel, llama a la función correspondiente
    generateExcel($ventas);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Historial de ventas</title>
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

        th,
        td {
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
        <h2>Historial de ventas de <?php echo $nombreUsuario; ?></h2>

        <?php if (!empty($ventas)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID de Venta</th>
                        <th>Fecha de la venta</th>
                        <th>Total de la venta</th>
                        <th>Documento del vendedor</th>
                        <th>Documento del cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta) : ?>
                        <tr>
                            <td><?php echo $venta['id_venta']; ?></td>
                            <td><?php echo $venta['fecha']; ?></td>
                            <td>$<?php echo $venta['tot_ven']; ?></td>
                            <td><?php echo $venta['docu_ven']; ?></td>
                            <td><?php echo $venta['docu_clien']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Botones -->
            <a href="#" class="boton" onclick="window.history.back()">Volver</a>
            <a href="?downloadExcel=1" class="boton">Descargar en Excel</a>
        <?php else : ?>
            <p>No hay historial de ventas.</p>
        <?php endif; ?>
    </div>
</body>

</html>
