<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");

if ($_SESSION["rol"] != "1") {
    header("Location: index.php");
    exit();
}

$conexion = conexionBD($host, $user, $password, $bbdd);
$usuario_id = $_GET["usuario_id"];
$rally_id = $_GET["rally_id"];

$consulta = "DELETE FROM registros WHERE usuario_id = ? AND rally_id = ?";
$resultado = $conexion->prepare($consulta);
$resultado->execute([$usuario_id, $rally_id]);

header("Location: admin.php?r=" . $rally_id);
?>
