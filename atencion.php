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

if ($_POST) {
    if (isset($_POST["nombre"])) {
        $nombre = $_POST["nombre"];
    }
    if (isset($_POST["correo"])) {
        if(empty($_POST["correo"])){
            $correo = "-";
        }else {
            $correo = $_POST["correo"];
        }
    }
    if (isset($_POST["asunto"])) {
        $asunto = $_POST["asunto"];
    }
    if (isset($_POST["mensaje"])) {
        $mensaje = $_POST["mensaje"];
    }

    $conexion = new Conexion();
    $enviado = $conexion->ejecutar("INSERT INTO `queja` (`id`,`nombre`,`correo`,`asunto`,`mensaje`) VALUES (NULL, '$nombre','$correo','$asunto','$mensaje')");
    if ($enviado) {
        echo "<script>alert('Se envió su comentario exitosamente!')</script>";
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
    <title>Atención al cliente - Supermercado Joaquin</title>
</head>

<body>
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
                                <a class="nav-link active" href="atencion.php">Atencion al cliente</a>
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
                    <button type="button" class="btn btn-primary"><img src="img/carro.png" class="img-fluid" alt="carrito" width="30">(<?php echo (count($estaEnCarrito)) ?>)</button>
                </a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="row rounded-5 bg-light my-5 border">
            <div class="container-fluid py-5 m-auto">
                <h1 class="display-5 ms-3 fw-bold">Contacto</h1>
                <hr class="border border-dark border-1 me-4">
                <div class="container m-auto p-auto">
                    <form action="atencion.php" method="post" class="col-auto p-5">
                        <h2 class="fw-bold text-end text-info">Informenos sus dudas, problemas, comentarios.</h2>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" required class="form-control" placeholder="Ingrese su nombre completo..." aria-describedby="helpId">
                            <small class="text-secondary">Utilice su nombre real</small>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" name="correo" class="form-control" placeholder="Ingrese su correo electrónico..." aria-describedby="helpId">
                            <small class="text-secondary">Utilice un correo al que tenga acceso</small>
                        </div>
                        <div class="mb-3">
                            <label for="asunto" class="form-label">Asunto</label>
                            <input type="text" name="asunto" required class="form-control" aria-describedby="helpId">
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea name="mensaje" cols="30" rows="5" required class="form-control" placeholder="Escriba su mensaje..."></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-outline-primary btn-lg">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="js/bootstrap.js"></script>
</body>

</html>