<?php
// session_start();
require_once("../../../db/conexion.php");
include "../../../controller/validarsesion.php";
$db = new Database();
$conexion = $db->conectar();


if (!isset($_SESSION["carrito"]))
    $_SESSION["carrito"] = [];
$granTotal = 0;

$cliente = $conexion->prepare("SELECT documento, nombre FROM usuarios WHERE id_rol = 2");
$cliente->execute();
$selectcliente = $cliente->fetchAll();

$idventa = $conexion->prepare("SELECT id_compra FROM compras ORDER BY id_compra DESC LIMIT 1");
$idventa->execute();
$id = $idventa->fetch(PDO::FETCH_ASSOC); // Usamos FETCH_ASSOC para obtener un array asociativo

$documento = $_SESSION['document'];
$prods = $conexion->prepare("SELECT * FROM productos WHERE documento = '$documento'");
$prods->execute();
$prodsrows = $prods->fetch();

date_default_timezone_set('America/Bogota');
$fecha_actual = date('Y-m-d');
?>

<?php
if (isset($_POST['boton_volver'])) {
    echo '<script>window.location="./index-vende.php"</script>';
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="../../../assets/img/logo.png">
    <!-- bootstrap sin internet -->
    <link rel="stylesheet" href="../../../assets/css/bootstrap-5.3.0-alpha3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="./compras.css">
    <link rel="stylesheet" href="./jquery-ui.js">
    <title>Crear venta</title>
    <style>
        .custom-combobox {
            position: relative;
            display: inline-block;
        }

        .custom-combobox-toggle {
            position: absolute;
            top: 0;
            bottom: 0;
            margin-left: -1px;
            padding: 0;
        }

        .custom-combobox-input {
            margin: 0;
            padding: 5px 10px;
        }
    </style>
    <script src="./jquery-3.6.0.js"></script>
    <script src="./jquery-ui.js"></script>
    <script>
        $(function() {
            $.widget("custom.combobox", {
                _create: function() {
                    this.wrapper = $("<span>")
                        .addClass("custom-combobox")
                        .insertAfter(this.element);

                    this.element.hide();
                    this._createAutocomplete();
                    this._createShowAllButton();
                },

                _createAutocomplete: function() {
                    var selected = this.element.children(":selected"),
                        value = selected.val() ? selected.text() : "";

                    this.input = $("<input>")
                        .appendTo(this.wrapper)
                        .val(value)
                        .attr("title", "")
                        .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
                        .autocomplete({
                            delay: 0,
                            minLength: 0,
                            source: this._source.bind(this)
                        })
                        .tooltip({
                            classes: {
                                "ui-tooltip": "ui-state-highlight"
                            }
                        });

                    this._on(this.input, {
                        autocompleteselect: function(event, ui) {
                            ui.item.option.selected = true;
                            this._trigger("select", event, {
                                item: ui.item.option
                            });
                        },

                        autocompletechange: "_removeIfInvalid"
                    });
                },

                _createShowAllButton: function() {
                    var input = this.input,
                        wasOpen = false;

                    $("<a>")
                        .attr("tabIndex", -1)
                        .attr("title", "Mostrar todos")
                        .tooltip()
                        .appendTo(this.wrapper)
                        .button({
                            icons: {
                                primary: "ui-icon-triangle-1-s"
                            },
                            text: false
                        })
                        .removeClass("ui-corner-all")
                        .addClass("custom-combobox-toggle ui-corner-right")
                        .on("mousedown", function() {
                            wasOpen = input.autocomplete("widget").is(":visible");
                        })
                        .on("click", function() {
                            input.trigger("focus");

                            // Close if already visible
                            if (wasOpen) {
                                return;
                            }

                            // Pass empty string as value to search for, displaying all results
                            input.autocomplete("search", "");
                        });
                },

                _source: function(request, response) {
                    var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
                    response(this.element.children("option").map(function() {
                        var text = $(this).text();
                        if (this.value && (!request.term || matcher.test(text)))
                            return {
                                label: text,
                                value: text,
                                option: this
                            };
                    }));
                },

                _removeIfInvalid: function(event, ui) {

                    // Selected an item, nothing to do
                    if (ui.item) {
                        return;
                    }

                    // Search for a match (case-insensitive)
                    var value = this.input.val(),
                        valueLowerCase = value.toLowerCase(),
                        valid = false;
                    this.element.children("option").each(function() {
                        if ($(this).text().toLowerCase() === valueLowerCase) {
                            this.selected = valid = true;
                            return false;
                        }
                    });

                    // Found a match, nothing to do
                    if (valid) {
                        return;
                    }

                    // Remove invalid value
                    this.input
                        .val("")
                        .attr("title", value + " didn't match any item")
                        .tooltip("open");
                    this.element.val("");
                    this._delay(function() {
                        this.input.tooltip("close").attr("title", "");
                    }, 2500);
                    this.input.autocomplete("instance").term = "";
                },

                _destroy: function() {
                    this.wrapper.remove();
                    this.element.show();
                }
            });

            $("#combobox").combobox();
        });
    </script>
</head>

<div class="col-xs-12 contenedor1">
    <form class="volver" method="post">
        <button name="boton_volver" class="btn btn-warning btn-atras">Atras</button>
    </form><br>
    <h1 style="text-align:left; color:white; font-weight:700; font-size:43px">NUEVA COMPRA
        <?php if ($id && isset($id['id_compra'])) {
            echo '#' . ($id['id_compra'] + 1);
        } else {
            echo '#1';
        } ?> <b style="font-size:43px; float: right;">
            <?php echo $fecha_actual ?>
        </b>
    </h1><br>
    <?php
    if (isset($_GET["status"])) {
        if ($_GET["status"] === "1") {
    ?>
            <div class="alert alert-success">
                <strong>¡Correcto!</strong> Venta realizada correctamente
            </div>
        <?php
        } else if ($_GET["status"] === "2") {
        ?>
            <div class="alert alert-info">
                <strong>Venta cancelada</strong>
            </div>
        <?php
        } else if ($_GET["status"] === "3") {
        ?>
            <div class="alert alert-info">
                <strong>Ok</strong> Producto quitado de la lista
            </div>
        <?php
        } else if ($_GET["status"] === "4") {
        ?>
            <div class="alert alert-warning">
                <strong>Error:</strong> El producto que buscas no existe
            </div>
        <?php
        } else if ($_GET["status"] === "5") {
        ?>
            <div class="alert alert-danger">
                <strong>Error: </strong>No hay existencias de este producto
            </div>
        <?php
        } else {
        ?>
            <div class="alert alert-danger">
                <strong>Error:</strong> Algo salió mal mientras se realizaba la venta
            </div>
    <?php
        }
    }
    ?>
    <br>
    <form method="post" action="./agregarAlCarrito.php">
        <label for="id_producto">Búsqueda por nombre:</label>
        <div class="ui-widget">
            <select name="id_producto" id="combobox">
                <option value>Select one...</option>
                <?php while ($prodsrows = $prods->fetch()) { ?>
                    <option value="<?= $prodsrows['id_producto'] ?>"><?= $prodsrows['nom_produc'] ?></option>
                <?php } ?>
            </select>
            <input type="submit" class="btn btn-primary">
        </div>
        <br>
    </form>
    <form method="post" action="./agregarAlCarrito.php">
        <input autocomplete="off" autofocus class="form-control" name="id_producto" type="text" id="id_producto" placeholder="Escribe el código del producto">
    </form>

    <br><br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Vendedor</th>
                <th>Nombre del producto</th>
                <th>Descripción</th>
                <th>Precio de venta</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Quitar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION["carrito"] as $indice => $producto) {
                $granTotal += $producto->total;
            ?>
                <tr>
                    <td>
                        <?php echo $producto->id_producto ?>
                    </td>
                    <td>
                        <?php echo $producto->nomvendedor ?>
                    </td>
                    <td>
                        <?php echo $producto->nom_produc ?>
                    </td>
                    <td>
                        <?php echo $producto->descrip ?>
                    </td>
                    <td>
                        $<?php echo $producto->precio_ven ?>
                    </td>
                    <td>
                        <form action="./cambiar_cantidad.php" method="post">
                            <input name="indice" type="hidden" value="<?php echo $indice; ?>">
                            <input min="1" name="cantidad" class="form-control" required type="number" step="1" value="<?php echo $producto->cantidad; ?>">
                        </form>
                    </td>
                    <td>
                        $<?php echo $producto->total ?>
                    </td>
                    <td>
                        <a class="btn btn-danger" href="<?php echo "quitarDelCarrito.php?indice=" . $indice ?>">ELIMINAR</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <form action="./terminarVenta.php" method="POST">
        <div class="form-group">
            <select style="width: 300px;" name="cliente" class="form-select" aria-label="Genero" required>
                <option value="">Seleccione un cliente</option>
                <?php foreach ($selectcliente as $clientes) { ?>
                    <option value="<?php echo $clientes['documento'] ?>"><?php echo $clientes['documento'] ?> <?php echo $clientes['nombre'] ?></option>
                <?php }
                $elegido = $_POST['cliente']; ?>
            </select>
        </div>
        <br>

        <h3 style="color: white; font-weight: 700;">Total:
            $<?php echo $granTotal; ?>
        </h3>
        <input name="total" type="hidden" value="<?php echo $granTotal; ?>">
        <button type="submit" class="btn btn-success">Terminar Compra</button>
        <a href="./cancelarVenta.php" class="btn btn-danger">Cancelar Compra</a>
    </form>
</div>