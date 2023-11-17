<?php
session_start();

include_once("conexion.php");
$con = new Conexion();

require 'vendor/autoload.php';

#Entorno Mercado 

MercadoPago\SDK::setAccessToken('TEST-989398288403429-111611-c41a8cd65dad3f95d6afaebcf00e21c6-219930396');
$preference = new MercadoPago\Preference();

if (isset($_SESSION['id'])) {
    $id_usuario = $_SESSION['id'];
} else {
    $id_usuario = NULL;
}

$sqlCompra = "SELECT producto.*,compra.cantidad FROM producto INNER JOIN compra ON compra.id_producto = producto.id WHERE compra.id_usuario = '$id_usuario'";
$productosComprados =  $con->seleccionar($sqlCompra);

$carro = array();

foreach ($productosComprados as $pro) {
    $item = new MercadoPago\Item();
    $item->id = $pro["id"];
    $item->title = $pro["nombre"];
    $item->quantity = $pro["cantidad"];
    $item->unit_price = $pro["precio"];
    $item->currecy_id = "ARS";
    array_push($carro, $item);
    unset($item);
}

$preference->items = $carro;

$preference->auto_return = "approved";
$preference->binary_mode = true;
$preference->back_urls = array(
    "success" => "http://localhost/sj/factura.php",
    "failure" => "http://localhost/sj/error.php"
);

$preference->save();

#---------------


$con = new Conexion();
$sql = "SELECT compra.*, producto.precio, producto.categoria, producto.nombre FROM compra INNER JOIN producto ON compra.id_producto = producto.id WHERE compra.id_usuario = '$id_usuario'";
$compras = $con->seleccionar($sql);

$total = 0;

foreach ($compras as $compra) {
    $total += $compra["precio"] * $compra["cantidad"];
}

if ($_GET) {
    if (isset($_GET["cancelar"])) {
        $id_compra = $_GET["cancelar"];
        $conCompra = $con->consultar("SELECT * FROM compra WHERE id = '$id_compra'");
        $id_producto = $conCompra["id_producto"];

        $checkearCantidad = $con->consultar("SELECT cantidad FROM compra WHERE id = '$id_compra'");
        if ($checkearCantidad["cantidad"] == 1) {
            $renovarStock = $con->ejecutar("UPDATE producto SET stock = stock+1 WHERE id = '$id_producto'");
            $borrarCompra = $con->ejecutar("DELETE FROM compra WHERE id = '$id_compra'");
        } else {
            $renovarStock = $con->ejecutar("UPDATE producto SET stock = stock+1 WHERE id = '$id_producto'");
            $renovarCantidad = $con->ejecutar("UPDATE compra SET cantidad = cantidad-1 WHERE id = '$id_compra'");
        }
        header("location:carrito.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <link rel="stylesheet" href="scss/custom.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Carrito - Supermarket</title>
</head>

<body class="bg-light">
    <script src="js/bootstrap.js"></script>
    <header>
        <nav class="navbar navbar-dark bg-success sticky-top">
            <div class="container-fluid">
                <button class="navbar-toggler bg-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <?php
                            if (!isset($_SESSION["usuario"])) { ?>
                                <li class="nav-item mx-auto">
                                    <a href="login.php">
                                        <button type="button" class="btn btn-outline-success">Ingresar</button></a>
                                    <a href="registro.php">
                                        <button type="button" class="btn btn-outline-primary">Registrarse</button>
                                    </a>
                                </li>
                            <?php
                            } else { ?>
                                <h1 class="text-warning text-center"><?php echo "Bienvenid@, " . $_SESSION["usuario"] ?></h1>
                                <li class="nav-item mx-auto">
                                    <a href="cerrar.php">
                                        <button type="button" class="btn btn-outline-danger">Cerrar sesión</button>
                                    </a>
                                </li>
                            <?php
                            } ?>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="index.php">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="productos.php">Productos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="contacto.php">Contacto</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="atencion.php">Atencion al cliente</a>
                            </li>
                        </ul>
                    </div>
                    <div class="offcanvas-bottom">
                        <ul class="navbar-nav flex-grow-1 px-3">
                            <li class="nav-item m-auto"> Supermarket © 2022 | Todos los derechos reservados.
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="navbar-brand mx-auto p-3 bg-success rounded-2" href="index.php">
                    <p class="super h3"><img src="img/logo.png" alt="logo" class="img-fluid m-auto" width="50"> Supermercado <b class="text-primary joaquin">Joaquin</b></p>
                </a>
                <a class="nav-link" href="productos.php">
                    <button type="button" class="btn btn-primary"><img src="img/volver.png" class="img-fluid" alt="volver" width="30"></button>
                </a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="row m-0 bg-light rounded-5 p-3 my-5 border border-dark">
            <div class="mb-0">
                <h1 class="display-5">Carrito</h1>
                <hr class="border border-dark border-1 me-4">
            </div>
            <?php if (!isset($_SESSION["usuario"]) || count($compras) == 0) { ?>
                <h1 class="text-center">No hay productos en el carrito</h1>
            <?php } else { ?>
                <div class="col-md-8 col-sm-6 mt-0">
                    <table class="table table-striped-columns ">
                        <thead class="table-warning">
                            <tr>
                                <th scope="col">Producto</th>
                                <th scope="col">Categoria</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <?php foreach ($compras as $compra) { ?>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <?php echo $compra["nombre"] ?>
                                    </th>
                                    <td>
                                        <?php echo $compra["categoria"] ?>
                                    </td>
                                    <td>
                                        <?php echo $compra["cantidad"] ?>
                                    </td>
                                    <td><a href="?cancelar=<?php echo $compra["id"] ?>" class="btn btn-danger">Cancelar</a>
                                    </td>
                                </tr>
                            </tbody>
                        <?php } ?>
                    </table>
                </div>
                <div class="col-sm-6 col-md-4 text-bg-dark m-0 pb-2 rounded-5">
                    <h3 class="mt-1 p-2 text-bg-light text-center rounded-5">Productos:
                        <?php echo count($compras) ?>
                    </h3>
                    <?php if (count($compras) != 0) { ?>
                        <h2 class="text-secondary text-center">Productos y precio</h2>
                    <?php } ?>
                    <?php foreach ($compras as $compra) { ?>
                        <div class="col-12 align-self-start text-end">
                            <p class="text-warning">
                                <?php echo $compra["nombre"] . " x" . $compra["cantidad"] ?>
                            </p>
                            <h3>
                                <?php echo $compra["precio"] * $compra["cantidad"] . " $" ?>
                            </h3>
                        </div>
                    <?php } ?>
                    <div class="container text-bg-light rounded-5">
                        <div class="row row-cols-1 align-items-end justify-content-end">
                            <div class="col-12 align-self-end text-center">
                                <h1>Total:
                                    <?php echo "$ " . $total ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <?php if (count($compras) !== 0) { ?>
                        <div class="cho-container mt-1 text-center"></div>
                        <script>
                            const mp = new MercadoPago('TEST-9aea9f13-0562-4994-b3a6-524630cbdbe1', {
                                locale: 'es-AR'
                            });

                            mp.checkout({
                                preference: {
                                    id: '<?php echo $preference->id; ?>'
                                },
                                render: {
                                    container: '.cho-container',
                                    label: 'Pagar con Mercado Pago',
                                }
                            });
                        </script>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </main>
</body>

</html>