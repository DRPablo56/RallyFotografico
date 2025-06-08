<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");

if ($_SESSION["rol"] != "1") {
    header("Location: index.php");
    exit();
}

$conexion = conexionBD($host, $user, $password, $bbdd);
$rally_id = $_POST["rally_id"];
$titulo = $_POST["titulo"];
$descripcion = $_POST["descripcion"];
$estado = $_POST["estado"];
$lim_fotos = $_POST["lim_fotos"];

$consulta = "UPDATE rallys SET titulo = ?, descripcion = ?, estado = ?, lim_fotos = ? WHERE id = ?";
$update = $conexion->prepare($consulta);
$update->execute([$titulo, $descripcion, $estado, $lim_fotos, $rally_id]);

header("Location: admin.php?r=" . $rally_id);
?>
