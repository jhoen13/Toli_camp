<a href="index.php">Atras</a><br><br>

<?php
require('config.php');
$tipo       = $_FILES['produc']['type'];
$tamanio    = $_FILES['produc']['size'];
$archivotmp = $_FILES['produc']['tmp_name'];
$lineas     = file($archivotmp);

$i = 0;

foreach ($lineas as $linea) {
    $cantidad_registros = count($lineas);
    $cantidad_regist_agregados =  ($cantidad_registros - 1);



    if ($i != 0) {
        // $datos = preg_split('/[,;"\s]+/', $linea, -1, PREG_SPLIT_NO_EMPTY);
        // print_r($datos);

        // $datos = explode(",", $linea);
        $datos = str_getcsv($linea, ',', '"');

        $nombre = !empty($datos[0])  ? ($datos[0]) : '';
        $descrip = !empty($datos[1])  ? ($datos[1]) : '';
        $prec_com = !empty($datos[2])  ? ($datos[2]) : '';
        $dispo = !empty($datos[3])  ? ($datos[3]) : '';
        $cate = !empty($datos[4])  ? ($datos[4]) : '';
        $canti = !empty($datos[5])  ? ($datos[5]) : '';
        $embala = !empty($datos[6])  ? ($datos[6]) : '';
        $foto = !empty($datos[7])  ? ($datos[7]) : '';
        $prec_ven = !empty($datos[8])  ? ($datos[8]) : '';
        $docu = !empty($datos[9])  ? ($datos[9]) : '';

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
        mysqli_query($con, $insertar);
    }

    echo '<div>' . $i . "). " . $linea . '</div>';
    $i++;
}


echo '<p style="text-aling:center; color:#333;">Total de Registros: ' . $cantidad_regist_agregados . '</p>';

?>