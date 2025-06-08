<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");

if (!isset($_SESSION["usuarioID"])) {
    header("Location: index.php");
    exit();
}

$conexion = conexionBD($host, $user, $password, $bbdd);
$usuarioID = $_SESSION["usuarioID"];

// Si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    
    $consulta = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute([$nombre, $email, $usuarioID]);
    
    $_SESSION["nombre"] = $nombre;
    header("Location: perfil.php?actualizado=1");
    exit();
}

// Obtener datos actuales del usuario
$consulta = "SELECT nombre, email FROM usuarios WHERE id = ?";
$resultado = $conexion->prepare($consulta);
$resultado->execute([$usuarioID]);
$usuario = $resultado->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Rally Fotográfico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php if(isset($_GET['actualizado'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Perfil actualizado correctamente
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <h2>Mi Perfil</h2>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario->nombre; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario->email; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="index.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
