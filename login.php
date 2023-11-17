<?php
session_start();

if (isset($_SESSION["usuario"])) {
    header("location:index.php");
}

require_once("conexion.php");

$conexion = new Conexion();

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
    <title>Ingresar - Supermarket</title>
</head>

<body class="bg-light">
    <div class="container col-md-6 col-sm-12 my-2 p-5 text-light">
        <form action="login.php" method="post" class="rounded-5 bg-dark row m-auto justify-content-center align-content-center">
            <h1 class="display-3 text-center text-light bg-success mt-5 super">Iniciar sesión <a href="index.php"><img src="img/logo.png" alt="" height="70" width="70" class="img-fluid"></a></h1>
            <?php

            if ($_POST) {
                if (isset($_POST["correo"])) {
                    $correo  = $_POST["correo"];
                }
                if (isset($_POST["clave"])) {
                    $clave  = md5($_POST["clave"]);
                }
                $sql = "SELECT * FROM `usuario` WHERE clave = '$clave' AND correo = '$correo'";
                $logueo = $conexion->consultar($sql);

                if ($logueo) {
                    $_SESSION['usuario'] = $logueo["username"];
                    $_SESSION['id'] = $logueo["id"];
                    setcookie("activo", uniqid("usuario_", true));
                    header("location:index.php");
                } else {
                    echo '<div class="alert alert-danger">El correo o la contraseña no corresponden. Por favor, reviselos e intente ingresar de nuevo.</div>';
                }
            }

            ?>
            <div class="mt-5 mb-1 mx-1 col-6">
                <label for="correo" class="form-label">Correo</label>
                <input required type="email" name="correo" class="form-control" placeholder="Correo electrónico">
            </div>
            <div class="m-1 col-6">
                <label for="clave" class="form-label">Contraseña</label>
                <input required type="password" name="clave" class="form-control" placeholder="Contraseña">
            </div>
            <div class="mt-2 text-center">
                <input type="submit" value="Ingresar" class="btn btn-lg btn-outline-success">
            </div>
            <hr class="border border-light border-1 mx-5 mt-5">
            <p class="mb-3 ps-4">¿No tienes una cuenta? <a href="registro.php" class="text-decoration-none fw-bolder">Registrate.</a></p>
        </form>
    </div>
</body>

</html>