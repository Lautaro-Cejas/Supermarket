<?php
session_start();

include_once("conexion.php");

$conexion = new Conexion();

if (isset($_SESSION['id'])) {
    $id_usuario = $_SESSION['id'];
} else {
    $id_usuario = NULL;
}

$id_sesion = session_id();

$productos = $conexion->seleccionar("SELECT * FROM producto");

$estaEnCarrito = $conexion->seleccionar("SELECT * FROM compra WHERE id_usuario = '$id_usuario'");

if ($_POST) {
    if (isset($_POST["busqueda"])) {
        $b = $_POST["busqueda"];
    }
    $sql = "SELECT * FROM producto WHERE nombre LIKE '%$b%' OR categoria LIKE '%$b%'";
    $productos = $conexion->seleccionar($sql);
}

if ($_GET) {
    if (isset($_GET["agregar"])) {
        $id_producto = $_GET["agregar"];

        $checkearProducto = $conexion->consultar("SELECT id_producto FROM compra WHERE id_producto = '$id_producto'");
        if (empty($checkearProducto)) {
            $estado = "En proceso";

            $sqlAct = "UPDATE producto SET stock = stock - 1 WHERE id = '$id_producto'";
            $actualizarStock = $conexion->ejecutar($sqlAct);

            $sqlIns = "INSERT INTO `compra` (`id`, `sesion`, `id_usuario`, `id_producto`, `cantidad`,`estado`) VALUES (NULL, '$id_sesion', '$id_usuario', '$id_producto', 1, '$estado')";
            $agregarCompra = $conexion->ejecutar($sqlIns);
        } else {
            $sqlAct = "UPDATE producto SET stock = stock - 1 WHERE id = '$id_producto'";
            $actualizarStock = $conexion->ejecutar($sqlAct);
            $actualizarCantidad = $conexion->ejecutar("UPDATE compra SET cantidad = cantidad + 1 WHERE id_producto = '$id_producto'");
        }
        header("location:productos.php");
    } else {
        echo "<script>alert('Esta no es una dirección válida.')</script>";
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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="scss/custom.css">
    <title>Productos - Supermarket</title>
</head>

<body class="bg-light">
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
                                <a class="nav-link active" href="productos.php">Productos</a>
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
                <a class="nav-link" href="carrito.php">
                    <button type="button" class="btn btn-primary text-light"><img src="img/carro.png" class="img-fluid" alt="carrito" width="30">(<?php echo (count($estaEnCarrito)) ?>)</button>
                </a>
            </div>
        </nav>
    </header>

    <main class="container">
        <header class="my-3">
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/banner.png" class="d-block w-100 rounded-5" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carro-1.png" class="d-block w-100 rounded-5" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carro-2.png" class="d-block w-100 rounded-5" alt="...">
                    </div>
                </div>
            </div>
        </header>
        <main class="row bg-light mb-5 rounded-5 border">
            <div class="container py-5 ms-3">
                <h1 class="display-5 fw-bold">Productos</h1>
                <hr class="border border-dark border-1 me-4">
                <p class="col-md-8 fs-4 text-info">Busca lo que queres!</p>
                <form class="d-flex m-auto" role="search" method="post" action="productos.php">
                    <input class="form-control" name="busqueda" type="search" placeholder="Estoy buscando..." aria-label="Search">
                    <button class="btn btn-outline-success mx-4" type="submit">Buscar</button>
                </form>
                <?php if (isset($productos)) { ?>
                    <div class="container row row-cols-3">
                        <?php foreach ($productos as $producto) { ?>
                            <div class="col-md-3 col-sm-6 mb-5 mx-auto my-1 border">
                                <div class="card border-light rounded-5">
                                    <img src="data:image/png;base64,<?php echo base64_encode($producto["img"]) ?>" class="card-img-top img-fluid" alt="imgCard">
                                    <div class="card-body text-bg-dark">
                                        <h5 class="card-title fw-bolder text-center text-uppercase"><?php echo $producto["nombre"] ?></h5>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item text-success text-center">$<?php echo $producto["precio"] . " por unidad" ?></li>
                                        <li class="list-group-item text-bg-warning"><?php echo $producto["categoria"] ?></li>
                                        <?php if ($producto["stock"] >= 1) { ?>
                                            <li class="list-group-item text-info">En stock</li>
                                        <?php } else { ?>
                                            <li class="list-group-item text-danger">No disponible</li>
                                        <?php } ?>
                                    </ul>
                                    <div class="card-body mx-auto text-center">
                                        <?php if (isset($_SESSION["usuario"]) && $producto["stock"] >= 1) { ?>
                                            <a href="?agregar=<?php echo $producto["id"] ?>" class="card-link"><button class="btn btn-success btn-lg">Agregar al carrito</button></a>
                                            <?php foreach ($estaEnCarrito as $carrito) {
                                                if ($carrito["id_producto"] == $producto["id"]) { ?>
                                                    <button class="mt-1 btn-lg btn btn-outline-primary disabled">En el carrito</button>
                                            <?php }
                                            } ?>
                                        <?php } else { ?>
                                            <div class="alert alert-danger">Debe ingresar para comprar</div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } elseif ($productos == NULL) { ?>
                    <div class="row mt-5">
                        <h1 class="display-5 text-muted">No hay productos</h1>
                    </div>
                <?php } ?>
            </div>
        </main>
    </main>

    <script src="js/bootstrap.js"></script>
</body>

</html>