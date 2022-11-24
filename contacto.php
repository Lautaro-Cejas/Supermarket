<?php
session_start();

include_once("conexion.php");

if (isset($_SESSION['id'])) {
    $id_usuario = $_SESSION['id'];
} else {
    $id_usuario = NULL;
}

$conexion = new Conexion();
$estaEnCarrito = $conexion->seleccionar("SELECT * FROM compra WHERE id_usuario = '$id_usuario'");
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
    <title>Contacto - Supermercado Joaquin</title>
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
                                <a class="nav-link" href="productos.php">Productos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="contacto.php">Contacto</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="atencion.php">Atencion al cliente</a>
                            </li>
                        </ul>
                    </div>
                    <div class="offcanvas-bottom">
                        <ul class="navbar-nav flex-grow-1 px-3">
                            <li class="nav-item m-auto"> Supermercado Joaquin © 2022 | Todos los derechos reservados.
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="navbar-brand mx-auto p-3 bg-success rounded-2" href="index.php">
                    <p class="super h3"><img src="img/logo.png" alt="logo" class="img-fluid m-auto" width="50"> Supermercado <b class="text-primary joaquin">Joaquin</b></p>
                </a>
                <a class="nav-link" href="carrito.php">
                    <button type="button" class="btn btn-primary"><img src="img/carro.png" class="img-fluid" alt="carrito" width="30">(<?php echo(count($estaEnCarrito)) ?>)</button>
                </a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="row rounded-5 bg-light my-5 border ">
            <div class="container-fluid py-5 m-auto">
                <h1 class="display-5 fw-bold ms-3">Nuestras redes</h1>
                <hr class="border border-dark border-1 me-4">
                <b class="fs-1 text-info">Podes encontrarnos en:</b>
                <div class="row justify-content-center align-items-center">
                    <div class="col-6">
                        <p class="col-md-4 fs-4 mt-2">Facebook <img class="img-fluid" src="img/facebook.png" alt="fbContactos">
                        </p>
                        <a href="#"><button class="btn btn-outline-info btn-xs" type="button">Supermercado
                                Joaquin</button>
                        </a>
                    </div>
                    <div class="col-6">
                        <p class="col-md-4 fs-4 mt-2">Instagram <img class="img-fluid" src="img/instagram.png" alt="igContactos"></p>
                        <a href="#"> <button class="btn btn-outline-primary btn-xs" type="button">super_joa</button>
                        </a>
                    </div>
                    <div class="col-6">
                        <p class="col-md-4 fs-4 mt-2">Telefono <img class="img-fluid" src="img/whatsapp.png" alt="telContactos">
                        </p>
                        <a href="#"><button class="btn btn-outline-success btn-xs" type="button">388 - 6230921</button>
                        </a>
                    </div>
                    <div class="col-6">
                        <p class="col-md-4 fs-4 mt-2">Twitter <img class="img-fluid" src="img/twitter.png" alt="twContactos">
                        </p>
                        <a href="#"><button class="btn btn-outline-info btn-xs" type="button">@Super_Joaquin</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/bootstrap.js"></script>
</body>

</html>