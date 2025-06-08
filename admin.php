<?php
session_start();
require_once("utiles/funciones.php");
require_once("utiles/variables.php");
$conexion = conexionBD($host, $user, $password, $bbdd);
// Comprobar si el usuario ha iniciado sesión y tiene el rol adecuado
if ($_GET["r"] == "" || $_SESSION["rol"] != "1") {
    header("Location: index.php");
} else {
    $id = $_REQUEST["r"];
    // Obtener datos del rally
    $consulta = "SELECT titulo, descripcion, estado, lim_fotos FROM rallys WHERE id = $id";
    $resultado = resultadoConsulta($conexion, $consulta);
    $rally = $resultado->fetch(PDO::FETCH_OBJ);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Rally Fotográfico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Configuración del Rally</h2>
        <form action="modificar_rally.php" method="POST" class="mb-5">
            <input type="hidden" name="rally_id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $rally->titulo; ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo $rally->descripcion; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-control" id="estado" name="estado">
                    <option value="Activo" <?php echo $rally->estado == 'Activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="Inactivo" <?php echo $rally->estado == 'Inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                    <option value="Finalizado" <?php echo $rally->estado == 'Finalizado' ? 'selected' : ''; ?>>Finalizado</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="lim_fotos" class="form-label">Límite de fotos</label>
                <input type="number" class="form-control" id="lim_fotos" name="lim_fotos" value="<?php echo $rally->lim_fotos; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
        <h2>Participantes del Rally</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $consulta = "SELECT u.id, u.nombre, u.email 
                            FROM usuarios u 
                            INNER JOIN registros r ON u.id = r.usuario_id 
                            WHERE r.rally_id = $id";
                $resultado = resultadoConsulta($conexion, $consulta);
                while ($fila = $resultado->fetch(PDO::FETCH_OBJ)) {
                    echo "<tr>";
                    echo "<td>" . $fila->id . "</td>";
                    echo "<td>" . $fila->nombre . "</td>";
                    echo "<td>" . $fila->email . "</td>";
                    echo "<td>
                            <a href='eliminar_participante.php?usuario_id=" . $fila->id . "&rally_id=" . $id . "' 
                               class='btn btn-danger btn-sm'>Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <h2 class="mt-5">Fotografías Pendientes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $consulta = "SELECT id, titulo, descripcion, url, estado 
                            FROM fotografias 
                            WHERE rally_id = $id AND estado != 'Aprobada'";
                $resultado = resultadoConsulta($conexion, $consulta);
                while ($fila = $resultado->fetch(PDO::FETCH_OBJ)) {
                    echo "<tr>";
                    echo "<td>" . $fila->id . "</td>";
                    echo "<td>" . $fila->titulo . "</td>";
                    echo "<td>" . $fila->descripcion . "</td>";
                    echo "<td>" . $fila->estado . "</td>";
                    echo "<td><img src='img/" . $fila->url . "' style='max-width: 100px;'></td>";
                    echo "<td>
                            <a href='cambiar_estado_foto.php?foto_id=" . $fila->id . "&estado=Aprobada&rally_id=" . $id . "' 
                               class='btn btn-success btn-sm me-2'>Aprobar</a>
                            <a href='cambiar_estado_foto.php?foto_id=" . $fila->id . "&estado=Denegada&rally_id=" . $id . "' 
                               class='btn btn-danger btn-sm'>Denegar</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="rally.php?r=<?php echo $id; ?>" class="btn btn-primary">Volver al Rally</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>