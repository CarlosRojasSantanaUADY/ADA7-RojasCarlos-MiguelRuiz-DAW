<?php 
session_start();
require_once "conexion.php";

$mensaje = "";
$tipo_alerta = "alert-success";
$es_staff = (isset($_SESSION['perfil']) && ($_SESSION['perfil'] == 'administrador' || $_SESSION['perfil'] == 'tecnico'));

// CREATE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_orden'])) {
    if (!isset($_SESSION['usuario_id'])) {
        $tipo_alerta = "alert-danger";
        $mensaje = "Debes iniciar sesion para registrar una orden.";
    } else {
        $usuario_id = (int) $_SESSION['usuario_id'];
        $cliente_nombre = mysqli_real_escape_string($conexion, $_SESSION['usuario']);
        $equipo = mysqli_real_escape_string($conexion, trim($_POST['equipo']));
        $falla = mysqli_real_escape_string($conexion, trim($_POST['falla']));
        $costo = $es_staff ? (float) $_POST['costo_estimado'] : 0.00;

        if ($equipo !== "" && $falla !== "") {
            $sql = "INSERT INTO ordenes_reparacion (usuario_id, cliente_nombre, equipo, falla, estado, costo_estimado)
                    VALUES ($usuario_id, '$cliente_nombre', '$equipo', '$falla', 'Recibido', $costo)";
            if (mysqli_query($conexion, $sql)) {
                $mensaje = "Orden de reparacion registrada correctamente.";
            } else {
                $tipo_alerta = "alert-danger";
                $mensaje = "Error al crear la orden: " . mysqli_error($conexion);
            }
        } else {
            $tipo_alerta = "alert-danger";
            $mensaje = "Completa el equipo y la descripcion de la falla.";
        }
    }
}

// UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_orden']) && isset($_SESSION['usuario_id'])) {
    $id_orden = (int) $_POST['id_orden'];
    $equipo = mysqli_real_escape_string($conexion, trim($_POST['equipo']));
    $falla = mysqli_real_escape_string($conexion, trim($_POST['falla']));

    if ($es_staff) {
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        $costo = (float) $_POST['costo_estimado'];
        $sql = "UPDATE ordenes_reparacion SET equipo = '$equipo', falla = '$falla', estado = '$estado', costo_estimado = $costo WHERE id = $id_orden";
    } else {
        $uid = (int) $_SESSION['usuario_id'];
        $sql = "UPDATE ordenes_reparacion SET equipo = '$equipo', falla = '$falla' WHERE id = $id_orden AND usuario_id = $uid";
    }

    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Orden #$id_orden actualizada correctamente.";
    } else {
        $tipo_alerta = "alert-danger";
        $mensaje = "Error al actualizar: " . mysqli_error($conexion);
    }
}

// DELETE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_orden']) && isset($_SESSION['usuario_id'])) {
    $id_orden = (int) $_POST['id_orden'];
    if ($es_staff) {
        $sql = "DELETE FROM ordenes_reparacion WHERE id = $id_orden";
    } else {
        $uid = (int) $_SESSION['usuario_id'];
        $sql = "DELETE FROM ordenes_reparacion WHERE id = $id_orden AND usuario_id = $uid";
    }

    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Orden #$id_orden eliminada del sistema.";
    } else {
        $tipo_alerta = "alert-danger";
        $mensaje = "Error al eliminar: " . mysqli_error($conexion);
    }
}

// READ
$orden_editar = null;
if (isset($_GET['editar']) && isset($_SESSION['usuario_id'])) {
    $id_edit = (int) $_GET['editar'];
    $filtro_edit = $es_staff ? "id = $id_edit" : "id = $id_edit AND usuario_id = " . (int) $_SESSION['usuario_id'];
    $res_edit = mysqli_query($conexion, "SELECT * FROM ordenes_reparacion WHERE $filtro_edit LIMIT 1");
    $orden_editar = mysqli_fetch_assoc($res_edit);
}

if (!isset($_SESSION['usuario_id'])) {
    $resultado_ordenes = null;
} elseif ($es_staff) {
    $resultado_ordenes = mysqli_query($conexion, "SELECT * FROM ordenes_reparacion ORDER BY id DESC");
} else {
    $uid = (int) $_SESSION['usuario_id'];
    $resultado_ordenes = mysqli_query($conexion, "SELECT * FROM ordenes_reparacion WHERE usuario_id = $uid ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADA 7 - Ordenes de Reparacion</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <?php include "header.php"; ?>

    <main class="container">
        <h1 class="display-5">Gestion de Ordenes de Reparacion</h1>
        <?php if ($mensaje !== ""): ?>
            <div class="<?php echo $tipo_alerta; ?> mb-3">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['usuario_id'])): ?>
            <div class="alert-warning">
                Debes <a href="acceso.php">iniciar sesion o registrarte</a> para ver tus ordenes o registrar un equipo.
            </div>
        <?php else: ?>

            <?php if ($orden_editar): ?>
                <div class="card mb-4">
                    <div class="card-header bg-dark">
                        <h2 class="h5">Modificar Orden #<?php echo $orden_editar['id']; ?> (Cliente: <?php echo htmlspecialchars($orden_editar['cliente_nombre']); ?>)</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="ordenes.php">
                            <input type="hidden" name="id_orden" value="<?php echo $orden_editar['id']; ?>">

                            <div class="mb-3">
                                <label class="form-label">Equipo:</label>
                                <input type="text" name="equipo" class="form-control" value="<?php echo htmlspecialchars($orden_editar['equipo']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripcion de la Falla:</label>
                                <input type="text" name="falla" class="form-control" value="<?php echo htmlspecialchars($orden_editar['falla']); ?>" required>
                            </div>

                            <?php if ($es_staff): ?>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Estado de Reparacion:</label>
                                        <select name="estado" class="form-select">
                                            <?php
                                            $estados = ["Recibido", "En diagnostico", "En reparacion", "Listo para entrega", "Entregado"];
                                            foreach ($estados as $est) {
                                                $sel = ($orden_editar['estado'] == $est) ? "selected" : "";
                                                echo "<option value='$est' $sel>$est</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Costo Estimado (MXN):</label>
                                        <input type="number" step="0.01" name="costo_estimado" class="form-control" value="<?php echo $orden_editar['costo_estimado']; ?>" required>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="acciones-form">
                                <button type="submit" name="actualizar_orden" class="btn btn-success btn-auto">Guardar Cambios</button>
                                <a href="ordenes.php" class="btn btn-secondary btn-auto">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary">
                        <h2 class="h5">Registrar Nueva Orden de Reparacion</h2>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Registrando orden a nombre de: <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong> (Perfil: <?php echo htmlspecialchars($_SESSION['perfil']); ?>)</p>
                        <form method="POST" action="ordenes.php">
                            <div class="mb-3">
                                <label class="form-label">Equipo (Marca y Modelo) *</label>
                                <input type="text" name="equipo" class="form-control" placeholder="Ej. Laptop HP Pavilion 14" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripcion de la Falla *</label>
                                <input type="text" name="falla" class="form-control" placeholder="Ej. Pantalla parpadea y no carga bateria" required>
                            </div>
                            <?php if ($es_staff): ?>
                                <div class="mb-3">
                                    <label class="form-label">Costo Estimado (MXN)</label>
                                    <input type="number" step="0.01" name="costo_estimado" class="form-control" value="0.00" min="0">
                                </div>
                            <?php endif; ?>
                            <button type="submit" name="crear_orden" class="btn btn-primary btn-auto">Crear Orden</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header bg-dark">
                    <h2 class="h5"><?php echo $es_staff ? "Todas las Ordenes del Taller" : "Mis Ordenes de Reparacion"; ?></h2>
                </div>
                <div class="card-body tabla-responsiva">
                    <table class="tabla-ordenes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Equipo</th>
                                <th>Falla</th>
                                <th>Estado</th>
                                <th>Costo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado_ordenes && mysqli_num_rows($resultado_ordenes) > 0): ?>
                                <?php while ($fila = mysqli_fetch_assoc($resultado_ordenes)): ?>
                                    <tr>
                                        <td>#<?php echo $fila['id']; ?></td>
                                        <td><?php echo htmlspecialchars($fila['cliente_nombre']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($fila['equipo']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($fila['falla']); ?></td>
                                        <td><span class="badge-estado"><?php echo htmlspecialchars($fila['estado']); ?></span></td>
                                        <td>$<?php echo number_format($fila['costo_estimado'], 2); ?></td>
                                        <td class="celda-acciones">
                                            <a href="ordenes.php?editar=<?php echo $fila['id']; ?>" class="btn-tabla btn-editar">Editar</a>
                                            <form method="POST" action="ordenes.php" onsubmit="return confirm('Seguro que deseas eliminar esta orden?');">
                                                <input type="hidden" name="id_orden" value="<?php echo $fila['id']; ?>">
                                                <button type="submit" name="eliminar_orden" class="btn-tabla btn-eliminar">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center;">No hay ordenes registradas para mostrar.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php endif; ?>
    </main>

    <footer>
        <p>Elaborado por Carlos Rojas Santana y Miguel Jesus Ruiz May | Desarrollo de Aplicaciones Web</p>
    </footer>

</body>
</html> 
