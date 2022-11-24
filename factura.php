<?php

session_start();

error_reporting(E_ERROR | E_PARSE);

#OBTENER DATOS DEL PAGO
date_default_timezone_set("America/Argentina/Jujuy");
$fecha = date('d-m-Y h:i a');
$id_pago = $_GET["payment_id"];
$estado = $_GET["status"];
$tipoPago = $_GET["payment_type"];
$id_orden = $_GET["merchant_order_id"];
#----------------------

include_once("conexion.php");
$con = new Conexion();

if (isset($_SESSION['id'])) {
    $id_usuario = $_SESSION['id'];
} else {
    $id_usuario = NULL;
}

$sql = "SELECT compra.*, producto.id AS idproducto, producto.nombre, producto.categoria, producto.precio FROM compra INNER JOIN producto ON compra.id_producto = producto.id WHERE compra.id_usuario = '$id_usuario'";
$compras = $con->seleccionar($sql);
$comprador = $con->consultar("SELECT * FROM usuario WHERE id = '$id_usuario'");

$total = 0;
$cantidad = 0;
foreach ($compras as $compra) {
    $total += $compra["cantidad"] * $compra["precio"];
    $cantidad += $compra["cantidad"];
}
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/sj/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/sj/css/styles.css">
    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/sj/scss/custom.css">
    <title>Factura - Supermercado Joaquin</title>
</head>

<body>
    <header class="container">
        <div class="row m-1 p-1">
            <header class="bg-success rounded-5">
                <h1 class="text-center super text-light">Supermercado <b class="joaquin text-primary">Joaquin</b><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/sj/img/logo.png" alt="logo" width="50"></h1>
            </header>
            <main class="row col-12 border rounded-5 m-1 p-3">
                <h1 class="fw-bolder text-end">Datos del pago</h1>
                <hr>
                <div class="col-6">
                    <p><b class="text-info">Orden: </b><?php echo $id_orden ?></p>
                    <p><b class="text-info">Fecha y hora: </b><?php echo $fecha ?></p>
                    <p><b class="text-info">Comprador: </b><?php echo $_SESSION["usuario"] ?></p>
                </div>
                <div class="col-6">
                    <p><b class="text-info">ID de pago: </b><?php echo $id_pago ?></p>
                    <p><b class="text-info">Estado del pago: </b><?php echo $estado ?></p>
                    <p><b class="text-info">Tipo de pago: </b><?php echo $tipoPago ?></p>
                </div>
            </main>
            <footer class="row col-12 border text-bg-warning rounded-5 m-1 p-3">
                <h1 class="fw-bolder text-end">Datos del comprador</h1>
                <hr>
                <p><b class="text-info">Nombre completo: </b><?php echo $comprador["nombre"] ?></p>
                <p><b class="text-info">Nombre de usuario: </b><?php echo $comprador["username"] ?></p>
                <p><b class="text-info">Correo: </b><?php echo $comprador["correo"] ?></p>
                <p><b class="text-info">Teléfono/celular: </b><?php echo $comprador["telefono"] ?></p>
                <div class="container mt-1">
                    <table class="table table-striped-columns">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Producto</th>
                                <th scope="col">Categoria</th>
                                <th scope="col">Precio unitario</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Subtotal</th>
                            </tr>
                        </thead>
                        <?php foreach ($compras as $compra) { ?>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <?php echo $compra["idproducto"] ?>
                                    </th>
                                    <td>
                                        <?php echo $compra["nombre"] ?>
                                    </td>
                                    <td>
                                        <?php echo $compra["categoria"] ?>
                                    </td>
                                    <td>
                                        <?php echo $compra["precio"] ?>
                                    </td>
                                    <td>
                                        <?php echo $compra["cantidad"] ?>
                                    </td>
                                    <td>
                                        <?php echo $compra["cantidad"] * $compra["precio"] ?>
                                    </td>
                                </tr>
                            </tbody>
                        <?php } ?>
                    </table>
                    <div class="container mt-1 text-end">
                        <h1>Cantidad de productos: <?php $cantidad ?></h1>
                        <h1>Total: <?php $total ?></h1>
                    </div>
                </div>
            </footer>
        </div>
    </header>
</body>

</html>
<?php
$html = ob_get_clean();

require_once ("pdf/dompdf/autoload.inc.php");

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemotableEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');

$dompdf->render();
$dompdf->stream("factura_".$id_orden.".pdf", array("Attachment" => false));

?>