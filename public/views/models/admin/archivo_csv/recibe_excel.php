<a href="index.php">Atras</a><br><br>

<?php
require('config.php');

// Verificar si se ha subido un archivo
if ($_FILES['produc']['error'] !== UPLOAD_ERR_OK || empty($_FILES['produc']['tmp_name']) || $_FILES['produc']['size'] === 0) {
    // Mostrar una alerta si no se seleccionó ningún archivo
    // echo '<script>alert("Por favor, selecciona un archivo para subir.");</script>';
    echo '<script>alert("Por favor, selecciona un archivo para subir."); window.location.href = "./index.php";</script>';
} else {
    // Procesar el archivo si se seleccionó correctamente
    $tipo       = $_FILES['produc']['type'];
    $tamanio    = $_FILES['produc']['size'];
    $archivotmp = $_FILES['produc']['tmp_name'];
    $lineas     = file($archivotmp);

    $i = 0;
    foreach ($lineas as $linea) {
        $cantidad_registros = count($lineas);
        $cantidad_regist_agregados = ($cantidad_registros - 1);

        if ($i != 0) {
            $datos = str_getcsv($linea, ',', '"');

            // Asignación de valores
            $nombre = !empty($datos[0]) ? ($datos[0]) : '';
            $descrip = !empty($datos[1]) ? ($datos[1]) : '';
            $prec_com = !empty($datos[2]) ? ($datos[2]) : '';
            $dispo = !empty($datos[3]) ? ($datos[3]) : '';
            $cate = !empty($datos[4]) ? ($datos[4]) : '';
            $canti = !empty($datos[5]) ? ($datos[5]) : '';
            $embala = !empty($datos[6]) ? ($datos[6]) : '';
            $foto = !empty($datos[7]) ? ($datos[7]) : '';
            $prec_ven = !empty($datos[8]) ? ($datos[8]) : '';
            $docu = !empty($datos[9]) ? ($datos[9]) : '';

            // Consulta de inserción
            $insertar = "INSERT INTO productos( 
                nom_produc,
                descrip,
                precio_compra,
                disponibles,
                id_categoria,
                cantidad,
                id_embala,
                foto,
                precio_ven,
                documento
            ) VALUES(
                '$nombre',
                '$descrip',
                '$prec_com',
                '$dispo',
                '$cate',
                '$canti',
                '$embala',
                '$foto',
                '$prec_ven',
                '$docu'
            )";

            // Ejecutar consulta
            mysqli_query($con, $insertar);
        }

        echo '<div>' . $i . "). " . $linea . '</div>';
        $i++;
    }
    // total de registros realizados
    echo '<p style="text-aling:center; color:#333;">Total de Registros: ' . $cantidad_regist_agregados . '</p>';
}
?>