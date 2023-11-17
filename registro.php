<?php

session_start();

if (isset($_SESSION["usuario"])) {
    header("location:index.php");
}

require_once("conexion.php");

$conexion = new Conexion();

if ($_POST) {
    if (isset($_POST["nombre"])) {
        $nombre = $_POST["nombre"];
    }
    if (isset($_POST["tel"])) {
        if ($_POST["tel"] == 0) {
            $telefono = "-";
        } else {
            $telefono = $_POST["tel"];
        }
    }
    if (isset($_POST["correo"])) {
        $correo = $_POST["correo"];

        $sql = "SELECT `correo` FROM `usuario` WHERE `correo` = '$correo'";
        $verificacion = $conexion->consultar($sql);

        if ($verificacion) {
            echo "<script>alert('Este correo ya existe. Ingrese uno válido, por favor.')</script>";
        }
    }
    if (isset($_POST["usuario"])) {
        $usuario = $_POST["usuario"];

        $sql2 = "SELECT `username` FROM `usuario` WHERE `username` = '$usuario'";
        $verificacion = $conexion->consultar($sql2);

        if ($verificacion) {
            echo "<script>alert('Este nombre de usuario ya está en uso. Ingrese uno válido, por favor.')</script>";
        }
    }
    if (isset($_POST["clave"]) && isset($_POST["repetir"])) {
        $repetir = $_POST["repetir"];
        if ($repetir == $_POST["clave"]) {
            $clave = md5($_POST["clave"]);
        } else {
            echo "<script>alert('Las contraseñas no son iguales.')</script>";
        }
    }

    $sql3 = "INSERT INTO `usuario` (`id`, `nombre`, `username`, `telefono`,`correo`, `clave`) VALUES (NULL, '$nombre', '$usuario', '$telefono', '$correo', '$clave')";
    $insertar = $conexion->ejecutar($sql3);

    if ($insertar) {
        header("location:login.php");
    } else {
        echo "<script>alert('Ha ocurrido un error al registrarte.')</script>";
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
    <title>Registro - Supermarket</title>
</head>

<body class="bg-light">
    <div class="container col-md-6 col-sm-12 my-2 p-5 text-light">
        <form action="registro.php" method="post" class="rounded-5 bg-dark row m-auto justify-content-center align-content-center">
            <h1 class="display-3 text-center text-light bg-success my-5 super">Registrarse<a href="index.php"><img src="img/logo.png" alt="" height="70" width="70" class="img-fluid"></a></h1>
            <div class="m-1 col-8">
                <label for="nombre" class="form-label">Nombre real</label>
                <input required type="text" name="nombre" class="form-control">
            </div>
            <div class="m-1 col-8">
                <label for="correo" class="form-label">Correo</label>
                <input required type="email" id="email" autocomplete="off" name="correo" class="border border-2 form-control" placeholder="Correo electrónico">
            </div>
            <div class="m-1 col-8">
                <label for="usuario" class="form-label">Usuario</label>
                <input required type="text" name="usuario" autocomplete="off" class="form-control" placeholder="Nombre de usuario">
                <small class="text-secondary">Debe tener un nombre de usuario único.</small>
            </div>
            <div class="m-1 col-8">
                <label for="tel" class="form-label">Teléfono</label>
                <input type="text" name="tel" id="celular" autocomplete="off" class="form-control" placeholder="Número de teléfono">
            </div>
            <div class="m-1 col-8">
                <label for="clave" class="form-label">Contraseña</label>
                <input required type="password" autocomplete="off" name="clave" class="form-control" placeholder="Contraseña">
                <small class="text-secondary">Hasta 32 caracteres. No colocar caracteres especiales.</small>
            </div>
            <div class="m-1 col-8">
                <label for="repetir" class="form-label">Repetir contraseña</label>
                <input required type="password" name="repetir" autocomplete="off" class="form-control">
            </div>
            <div class="mt-2 text-center">
                <input type="submit" value="Registrarme" class="btn btn-lg btn-outline-success">
            </div>
            <hr class="border border-light border-1 mx-5 mt-5">
            <p class="mb-3 ps-4">¿Ya tienes una cuenta? <a href="login.php" class="text-decoration-none fw-bolder">Iniciar sesión.</a></p>
        </form>
    </div>

    <script src="js/validarCorreo.js"></script>
    <script src="js/validarTelefono.js"></script>
</body>

</html>