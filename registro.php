<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");
$errores = [];
$email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : null;
$contrasena = isset($_REQUEST["contrasena"]) ? $_REQUEST["contrasena"] : null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!validarRequerido($email)) {
        $errores[] = "Campo Email obligatorio.";
    }
    if (!validarEmail($email)) {
        $errores[] = "Campo Email no tiene un formato válido";
    }
    if (!validarRequerido($password)) {
        $errores[] = "Campo Contraseña obligatorio.";
    }
    /* Verificar que no existe en la base de datos el mismo email */
    $conexion = conexionBD($host, $user, $password, $bbdd);
    $select = "SELECT COUNT(*) as numero FROM usuarios WHERE email = :email";
    $consulta = $conexion->prepare($select);
    $consulta->execute([
        "email" => $email
    ]);
    $resultado = $consulta->fetch();
    $consulta = null;
    // Comprueba si existe
    if ($resultado["numero"] > 0) {
        $errores[] = "La dirección de email ya esta registrada.";
    }
    if (count($errores) === 0) {
        $insert = "INSERT INTO usuarios (nombre, email, password, rol_id) VALUES
    (:nombre, :email, :password, :rol)";
        $consulta = $conexion->prepare($insert);
        // Ejecuta el nuevo registro en la base de datos
        $consulta->execute([
            "nombre" => explode("@", $email)[0],
            "email" => $email,
            "password" => password_hash($contrasena, PASSWORD_BCRYPT),
            "rol" => 2,
        ]);
        $consulta = null;
        /* Redirección a index.php */
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container text-center p-4">
        <form class="col-4 offset-md-4" method="post">
            <input type="text" name="email" placeholder="E-mail">
            <input type="password" name="contrasena" placeholder="Contraseña">
            <input type="submit" class="btn btn-warning my-2" name="registro" value="Registrarse">
            <?php
                 if (count($errores) > 0) echo "<div class='d-block display invalid-feedback'>$errores[0]</div>"
            ?>
        </form>
    </div>
</body>

</html>