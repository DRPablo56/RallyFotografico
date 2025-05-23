<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");
$conexion = conexionBD($host, $user, $password, $bbdd);
// Recibir el rally que se quiere ver, si no existe redirigir a inicio
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
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rally Fotográfico</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Rally Fotográfico</a>
            <div class="d-flex">
                <?php
                if (isset($_SESSION["nombre"])):
                ?>
                    <span class="text-light me-2">Bienvenido <?php echo $_SESSION["nombre"] ?></span>
                    <a href="cerrar.php">
                        <button class="btn btn-danger me-2" type="button">Cerrar Sesión</button>
                    </a>
                <?php
                else:
                ?>
                    <a href="login.php">
                        <button class="btn btn-warning me-2" type="button">Iniciar Sesión</button>
                    </a>
                    <a href="registro.php">
                        <button class="btn btn-warning" type="button">Crear cuenta</button>
                    </a>
                <?php
                endif;
                ?>
            </div>
        </div>
    </nav>
    <main class="container min-vh-100">
    <?php
    if (isset($_SESSION["nombre"])):
        $usuarioID = $_SESSION["usuarioID"];
        $consulta = "SELECT COUNT(*) AS num FROM registros WHERE rally_id = $id AND usuario_id = $usuarioID";
        $resultado = resultadoConsulta($conexion, $consulta);
        $fila = $resultado->fetch(PDO::FETCH_OBJ);
        if ($fila->num != 0):
    ?>
            <!-- Sección Publicar -->
            <div class="bg-light py-5 container-fluid">
                <h2 class="text-center mb-4">Publicar una fotografía</h2>
                <form action="publicar.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="rally_id" value="<?php echo $id; ?>">
                    <input type="text" name="titulo" placeholder="Título" class="form-control mb-3" required>
                    <input type="text" name="descripcion" placeholder="Descripción" class="form-control mb-3" required>
                    <input type="file" name="fotografia" class="form-control mb-3" required>
                    <input type="submit" class="btn btn-primary" value="Publicar">
                </form>
            </div>
        <?php
        else:
        ?>
            <!-- Sección Apuntarse -->
            <div class="bg-light py-5 container-fluid d-flex flex-column align-items-center">
                <h2 class="text-center mb-4">Apúntate para participar</h2>
                <p class="text-center">Para participar en el rally, debes apuntarte.</p>
                <a href="apuntar.php?r=<?php echo $id; ?>" class="btn btn-primary mx-auto">Apuntarse</a>
            </div>
        <?php
        endif;
    else:
        ?>
    <?php
    endif;
    ?>
    <!-- Seción Fotografías -->

    <div class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">Vota una fotografía</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $consulta = "select * from fotografias where rally_id = $id";
                $resultado = resultadoConsulta($conexion, $consulta);
                while ($fila = $resultado->fetch(PDO::FETCH_OBJ)) {
                    if ($fila->estado == "Aprobada") {
                        echo '<div class="col">';
                        echo '<div class="card h-100">';
                        echo '<div class="card-body d-flex flex-column">';
                        echo '<h5 class="card-title">' . $fila->titulo . '</h5>';
                        echo '<p class="card-text">' . $fila->descripcion . '</p>';
                        echo '<img src="img/' . $fila->url . '" class="card-img-top py-2" alt="Imagen de ' . $fila->titulo . '">';
                        echo '<a href="#" class="btn btn-primary mt-auto">Votar</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    </main>
    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-1">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Rally Fotográfico</h5>
                    <p>Una plataforma para amantes de la fotografía</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>