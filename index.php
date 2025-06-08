<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");
$conexion = conexionBD($host, $user, $password, $bbdd);
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

<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Rally Fotográfico</a>
            <div class="d-flex">
                <?php
                if (isset($_SESSION["nombre"])):
                ?>
                    <span class="text-light me-2">Bienvenido <?php echo $_SESSION["nombre"] ?></span>
                    <?php
                    if(isset($_SESSION["rol"]) && $_SESSION["rol"] != "1"):
                    ?>
                        <a href="perfil.php">
                            <button class="btn btn-primary me-2" type="button">Mi Perfil</button>
                        </a>
                    <?php
                    endif;
                    ?>
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


    <!-- Sección Portada -->
    <div class="bg-dark text-white py-5">
        <div class="container py-5">
            <h1 class="display-4 fw-bold">Captura el momento</h1>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Participa en un Rally</h5>
                        <p class="card-text">Consulta los rallys que están actualmente activos y entra en ellos.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Encuentra las mejores fotografías</h5>
                        <p class="card-text">A través de la galería podrás encontrar las fotos más votadas por los usuarios.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Crea tus propios Rallys</h5>
                        <p class="card-text">Contacta con el administrador para poder crear tu propio rally en el que la comunidad pueda participar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $consulta = "select COUNT(*) as num from rallys where estado = 'Activo'";
    $resultado = resultadoConsulta($conexion, $consulta);
    $fila = $resultado->fetch(PDO::FETCH_OBJ);
    if ($fila->num != 0):
    ?>
        <!-- Seción Rallys -->

        <div class="bg-light py-5">
            <div class="container">
                <h2 class="text-center mb-4">¡Entra a un Rally!</h2>
                <div class="row g-4">
                    <div class="col-md-6">
                        <?php
                        $consulta = "select * from rallys where estado = 'Activo'";
                        $resultado = resultadoConsulta($conexion, $consulta);
                        while ($fila = $resultado->fetch(PDO::FETCH_OBJ)) {
                            echo '<div class="card">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $fila->titulo . '</h5>';
                            echo '<p class="card-text">' . $fila->descripcion . '</p>';
                            echo '<a href="rally.php?r=' . $fila->id . '" class="btn btn-primary">Entrar</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    <?php
    else:
    ?>
        <div class="bg-light py-5">
            <div class="container">
                <h2 class="text-center mb-4">No hay rallys activos</h2>
                <p class="text-center">Actualmente no hay rallys activos. ¡Vuelve pronto!</p>
            </div>
        </div>
    <?php
    endif;
    ?>
    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-auto">
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