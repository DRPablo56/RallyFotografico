<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");
$errores = [];
$email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
$contrasena = isset($_REQUEST["contrasena"]) ? $_REQUEST["contrasena"] : null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexion = conexionBD($host, $user, $password, $bbdd);
    $select = "SELECT id, nombre, password, rol_id AS rol FROM usuarios WHERE email = :email";
    $consulta = $conexion->prepare($select);
    $consulta->execute([
        "email" => $email
    ]);
    $resultado = $consulta->fetch();
    if ($consulta->rowCount() > 0) {
            // Comprobar contraseña
            if (password_verify($contrasena, $resultado["password"])) {
                $_SESSION["rol"] = $resultado["rol"];
                $_SESSION["nombre"] = $resultado["nombre"];
                $_SESSION["usuarioID"] = $resultado["id"];
                header("Location: index.php");
                exit();
            } else {
                // Contraseña incorrecta
                $errores[] = "El email o la contraseña es incorrecta.";
            }
    } else {
        // E-mail incorrecto (cuenta no existe)
        $errores[] = "El email o la contraseña es incorrecta.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container text-center p-4">
        <form class="col-4 offset-md-4" method="post">
            <input type="text" name="email" placeholder="E-mail">
            <input type="password" name="contrasena" placeholder="Contraseña">
            <input type="submit" class="btn btn-warning my-2" name="inicio" value="Iniciar Sesión">
            <?php
                 if (count($errores) > 0) echo "<div class='d-block display invalid-feedback'>$errores[0]</div>"
            ?>
        </form>
    </div>
</body>

</html>