<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");
$conexion = conexionBD($host, $user, $password, $bbdd);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["rally_id"];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $foto = $_FILES["fotografia"]["tmp_name"];
} else {
    // Si no se ha enviado el formulario, redirigir a la página de inicio
    header("Location: index.php");
}
if (isset($_SESSION["usuarioID"])) {
    $usuarioID = $_SESSION["usuarioID"];
    $consulta = "SELECT COUNT(*) AS num FROM registros WHERE rally_id = $id AND usuario_id = $usuarioID";
    $resultado = resultadoConsulta($conexion, $consulta);
    $fila = $resultado->fetch(PDO::FETCH_OBJ);
    if ($fila->num > 0) {
        try {
            $tipoImagen = strtolower(pathinfo($_FILES["fotografia"]["name"],PATHINFO_EXTENSION));
            $url = uniqid('', true) . "." . $tipoImagen;
            $consulta = "insert into fotografias (titulo, descripcion, url, autor_id, rally_id, estado) values (:titulo, :descripcion, :url, :usuarioID, :id, :estado)";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute([
                "titulo" => $titulo,
                "descripcion" => $descripcion,
                "url" => $url,
                "usuarioID" => $usuarioID,
                "id" => $id,
                "estado" => "Pendiente"
            ]);
            move_uploaded_file($foto, "img/" . $url);
            header("Location: rally.php?r=$id");
        } catch (PDOException $e) {
            header("Location: rally.php?r=$id");
        }
    } else {
        // Si el usuario no está registrado en el rally, redirigir a la página de inicio
        header("Location: index.php");
    }
    } else {
        // Si no hay sesión iniciada, redirigir a la página de inicio
        header("Location: index.php");
    }
?>