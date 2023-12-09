<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- icono -->
  <link type="text/css" rel="shortcut icon" href="img/logo.png" />
  <title>Archivo CSV</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/cargando.css">
  <link rel="stylesheet" type="text/css" href="css/cssGenerales.css">
</head>

<body style="font-family: 'Times New Roman', Times, serif;">

  <div class="cargando">
    <div class="loader-outter"></div>
    <div class="loader-inner"></div>
  </div>


  <nav class="navbar navbar-expand-lg navbar-light navbar-dark fixed-top" style="background-color:#0097B2 !important;">
    <ul class="navbar-nav mr-auto collapse navbar-collapse">
      <li class="nav-item active">
        <a href="index.php">
          <img src="img/logo.png" alt="Web Developer Urian Viera" width="110">
        </a>
      </li>
    </ul>
    <div class="my-2 my-lg-0">
      <h5 class="navbar-brand">TOLI-CAMP</h5>
    </div>
  </nav>

  <div class="container">

    <h3 class="text-center">Importar archivo SVC(Excel) a tabla de Productos</h3>
    <hr>
    <br>

    <div class="">
      <div class="container">
        <h4 class="text-center">¡Sugerencias!</h4><br>
        <p class="text-center">Al importar el archivo CSV/Excel, es importante omitir la columna correspondiente al campo "id_producto" durante el proceso de carga. Esta columna contiene los identificadores únicos de los productos, sin embargo, la tabla de destino ya cuenta con un ID autoincremental asignado. <br><br> Por favor, asegúrate de que la carga del archivo se realice excluyendo esta columna específica ("id_producto"), tal como se muestra en la "imagen 1" adjunta como referencia. La omisión de esta columna evitará duplicidades y permitirá que la base de datos asigne los IDs automáticamente durante la importación. <br><br>Una vez completada la importación sin el campo "id_producto", la "imagen 2" muestra un ejemplo de cómo debería lucir la tabla resultante. <br> ¡Gracias por tu colaboración!</p>

        <div class="row text-center">
          <div class="col">
            <label for="">Imagen 1:</label><br>
            <img src="./img/suge_excel.jpg" alt="Sugerencia del archivo excel">
          </div>
          <div class="col">
            <label for="">Imagen 2:</label><br>
            <img src="./img/suge_bien.jpg" alt="Sugerencia del archivo excel">
          </div>
        </div>
      </div>
      <br><br>

      <div class="">
        <form action="./recibe_excel.php" method="POST" enctype="multipart/form-data" />
        <div class="file-input text-center">
          <!-- el name="produc" es el que va en las siguientes lineas del archivo recibe_excel... $tipo = $_FILES['produc']['type']; -->
          <input type="file" name="produc" id="file-input" class="file-input__input" />
          <label class="file-input__label" for="file-input">
            <i class="zmdi zmdi-upload zmdi-hc-2x"></i>
            <span>Elegir Archivo Excel</span></label>
        </div>
        <div class="text-center mt-5">
          <input type="submit" name="subir" class="btn-enviar" value="Subir Excel" />
        </div>
        </form>
      </div>


      <div class="text-center mt-5">
        <?php
        header("Content-Type: text/html;charset=utf-8");
        include('config.php');
        $sqlClientes = ("SELECT * FROM productos ORDER BY id_producto ASC");
        $queryData   = mysqli_query($con, $sqlClientes);
        $total_client = mysqli_num_rows($queryData);
        ?>

        <h6 class="text-center">
          Lista de Productos <strong>(<?php echo $total_client; ?>)</strong>
        </h6>

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>NOMBRE</th>
              <th>DESCRIP</th>
              <th>PRECIO COMPRA</th>
              <th>DISPONIBLES</th>
              <th>ID_CATEGORIA</th>
              <th>CANTIDAD</th>
              <th>ID_EMBALAJE</th>
              <th>FOTO</th>
              <th>PRECIO VENTA</th>
              <th>DOCUMENTO</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            while ($data = mysqli_fetch_array($queryData)) { ?>
              <tr>
                <th scope="row"><?php echo $i++; ?></th>
                <td><?php echo $data['nom_produc']; ?></td>
                <td><?php echo $data['descrip']; ?></td>
                <td><?php echo $data['precio_compra']; ?></td>
                <td><?php echo $data['disponibles']; ?></td>
                <td><?php echo $data['id_categoria']; ?></td>
                <td><?php echo $data['cantidad']; ?></td>
                <td><?php echo $data['id_embala']; ?></td>
                <td><?php echo $data['foto']; ?></td>
                <td><?php echo $data['precio_ven']; ?></td>
                <td><?php echo $data['documento']; ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

      </div>
    </div>

  </div>


  <script src="js/jquery.min.js"></script>
  <script src="'js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $(window).load(function() {
        $(".cargando").fadeOut(1000);
      });
    });
  </script>

</body>

</html>