<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");

if ($_SESSION["rol"] != "1") {
    header("Location: index.php");
    exit();
}

$conexion = conexionBD($host, $user, $password, $bbdd);
$foto_id = $_GET["foto_id"];
$estado = $_GET["estado"];
$rally_id = $_GET["rally_id"];

$consulta = "UPDATE fotografias SET estado = ? WHERE id = ?";
$update = $conexion->prepare($consulta);
$update->execute([$estado, $foto_id]);

header("Location: admin.php?r=" . $rally_id);
?>
