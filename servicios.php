<?php 
session_start();
require_once "conexion.php";

$mensaje = "";
$tipo_alerta = "alert-success";
$es_staff = (isset($_SESSION['perfil']) && ($_SESSION['perfil'] == 'administrador' || $_SESSION['perfil'] == 'tecnico'));

// CREATE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_servicio'])) {
    if (!$es_staff) {
        $tipo_alerta = "alert-danger";
        $mensaje = "Solo administradores o tecnicos pueden agregar servicios.";
    } else {
        $nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
        $descripcion = mysqli_real_escape_string($conexion, trim($_POST['descripcion']));
        $precio = (float) $_POST['precio'];

        if ($nombre !== "" && $descripcion !== "") {
            $sql = "INSERT INTO servicios (nombre, descripcion, precio) VALUES ('$nombre', '$descripcion', $precio)";
            if (mysqli_query($conexion, $sql)) {
                $mensaje = "Servicio registrado correctamente.";
            } else {
                $tipo_alerta = "alert-danger";
                $mensaje = "Error al guardar: " . mysqli_error($conexion);
            }
        }
    }
}

// UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_servicio']) && $es_staff) {
    $id_servicio = (int) $_POST['id_servicio'];
    $nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
    $descripcion = mysqli_real_escape_string($conexion, trim($_POST['descripcion']));
    $precio = (float) $_POST['precio'];

    $sql = "UPDATE servicios SET nombre = '$nombre', descripcion = '$descripcion', precio = $precio WHERE id = $id_servicio";
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Servicio #$id_servicio actualizado correctamente.";
    } else {
        $tipo_alerta = "alert-danger";
        $mensaje = "Error al actualizar: " . mysqli_error($conexion);
    }
}

// DELETE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_servicio']) && $es_staff) {
    $id_servicio = (int) $_POST['id_servicio'];
    $sql = "DELETE FROM servicios WHERE id = $id_servicio";
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Servicio #$id_servicio eliminado.";
    } else {
        $tipo_alerta = "alert-danger";
        $mensaje = "Error al eliminar: " . mysqli_error($conexion);
    }
}

// READ
$servicio_editar = null;
if (isset($_GET['editar']) && $es_staff) {
    $id_edit = (int) $_GET['editar'];
    $res_edit = mysqli_query($conexion, "SELECT * FROM servicios WHERE id = $id_edit LIMIT 1");
    $servicio_editar = mysqli_fetch_assoc($res_edit);
}

$resultado_servicios = mysqli_query($conexion, "SELECT * FROM servicios ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADA 7 - Catalogo de Servicios</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <?php include "header.php"; ?>

    <main class="container">
        <h1 class="display-5">Catalogo de Servicios TecnoFix</h1>
        <?php if ($mensaje !== ""): ?>
            <div class="<?php echo $tipo_alerta; ?> mb-3">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <?php if ($es_staff): ?>
            <?php if ($servicio_editar): ?>
                <div class="card mb-4">
                    <div class="card-header bg-dark">
                        <h2 class="h5">Modificar Servicio #<?php echo $servicio_editar['id']; ?></h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="servicios.php">
                            <input type="hidden" name="id_servicio" value="<?php echo $servicio_editar['id']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Nombre del Servicio:</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($servicio_editar['nombre']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripcion:</label>
                                <input type="text" name="descripcion" class="form-control" value="<?php echo htmlspecialchars($servicio_editar['descripcion']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Precio Base (MXN):</label>
                                <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $servicio_editar['precio']; ?>" required>
                            </div>
                            <div class="acciones-form">
                                <button type="submit" name="actualizar_servicio" class="btn btn-success btn-auto">Guardar Cambios</button>
                                <a href="servicios.php" class="btn btn-secondary btn-auto">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary">
                        <h2 class="h5">Agregar Nuevo Servicio (Panel Administrativo)</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="servicios.php">
                            <div class="mb-3">
                                <label class="form-label">Nombre del Servicio *</label>
                                <input type="text" name="nombre" class="form-control" placeholder="Ej. Cambio de Pantalla Laptop" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripcion *</label>
                                <input type="text" name="descripcion" class="form-control" placeholder="Ej. Instalacion de display LED nuevo" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Precio Base (MXN) *</label>
                                <input type="number" step="0.01" name="precio" class="form-control" value="0.00" min="0" required>
                            </div>
                            <button type="submit" name="crear_servicio" class="btn btn-primary btn-auto">Guardar Servicio</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-dark">
                <h2 class="h5">Lista de Servicios Disponibles</h2>
            </div>
            <div class="card-body tabla-responsiva">
                <table class="tabla-ordenes">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Servicio</th>
                            <th>Descripcion</th>
                            <th>Precio Base</th>
                            <?php if ($es_staff): ?>
                                <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = mysqli_fetch_assoc($resultado_servicios)): ?>
                            <tr>
                                <td>#<?php echo $fila['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($fila['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($fila['descripcion']); ?></td>
                                <td>$<?php echo number_format($fila['precio'], 2); ?> MXN</td>
                                <?php if ($es_staff): ?>
                                    <td class="celda-acciones">
                                        <a href="servicios.php?editar=<?php echo $fila['id']; ?>" class="btn-tabla btn-editar">Editar</a>
                                        <form method="POST" action="servicios.php" onsubmit="return confirm('Eliminar este servicio?');">
                                            <input type="hidden" name="id_servicio" value="<?php echo $fila['id']; ?>">
                                            <button type="submit" name="eliminar_servicio" class="btn-tabla btn-eliminar">Eliminar</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer>
        <p>Elaborado por Carlos Rojas Santana y Miguel Jesus Ruiz May | Desarrollo de Aplicaciones Web</p>
    </footer>

</body>
</html> 
