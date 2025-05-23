<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");
$conexion = conexionBD($host, $user, $password, $bbdd);
// Recibir el rally que se quiere apuntar, si no existe redirigir a inicio
if ($_GET["r"] == "") {
    header("Location: index.php");
} else {
    $id = $_REQUEST["r"];
    $consulta = "select estado from rallys where id = $id";
    $resultado = resultadoConsulta($conexion, $consulta);
    $fila = $resultado->fetch(PDO::FETCH_OBJ);
    if ($fila->estado != "activo") {
        header("Location: index.php");
    }
    if (isset($_SESSION["usuarioID"])) {
    $usuarioID = $_SESSION["usuarioID"];
    $consulta = "insert into registros (rally_id, usuario_id) values ($id, $usuarioID)";
    $resultado = resultadoConsulta($conexion, $consulta);
    header("Location: rally.php?r=$id");
    } else {
        header("Location: index.php");
    }
}
?>