<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");
$conexion = conexionBD($host, $user, $password, $bbdd);
if ($_GET["f"] == "") {
    // Si no se recibe el id de la foto redirigir a inicio
    header("Location: index.php");
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
    $fotoID = $_REQUEST["f"];
    //Comprobar si el usuario ya ha votado, utilizar usuarioID si ha iniciado sesión
    if (isset($_SESSION["usuarioID"])) {
        $usuarioID = $_SESSION["usuarioID"];
        $consulta = "SELECT COUNT(*) as num FROM votos WHERE foto_id = :fotoID AND usuario_id = :usuarioID";
        $consulta = $conexion->prepare($consulta);
        $consulta->execute([
            "fotoID" => $fotoID,
            "usuarioID" => $usuarioID,
        ]);
    } else {
        // Si no ha iniciado sesión, utilizar la IP
        $consulta = "SELECT COUNT(*) as num FROM votos WHERE foto_id = :fotoID AND ip = :ip";
        $consulta = $conexion->prepare($consulta);
        $consulta->execute([
            "fotoID" => $fotoID,
            "ip" => $ip
        ]);
    }
    $resultado = $consulta->fetch();
    if ($resultado["num"] > 0) {
        // Si ya ha votado, mostrar error
?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error - Rally Fotográfico</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>

        <body>
            <div class="container mt-5">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Error al votar</h4>
                    <p>No puedes votar una foto más de una vez</p>
                </div>
                <a href="rally.php?r=<?php echo $_SESSION["r"]; ?>" class="btn btn-primary">Volver al Rally</a>
            </div>
        </body>

        </html>
<?php
    } else {
        // Si no ha votado, insertar el voto
        $consulta = "INSERT INTO votos (foto_id, usuario_id, ip) VALUES (:fotoID, :usuarioID, :ip)";
        $consulta = $conexion->prepare($consulta);
        $consulta->execute([
            "fotoID" => $fotoID,
            "usuarioID" => isset($usuarioID) ? $usuarioID : null,
            "ip" => $ip
        ]);
        // Redirigir a rally
        header("Location: rally.php?r=" . $_SESSION["r"]);
    }
}
