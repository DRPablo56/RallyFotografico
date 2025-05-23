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
        // Si ya ha votado, redirigir a inicio
        header("Location: rally.php?r=" . $_SESSION["r"]);
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
