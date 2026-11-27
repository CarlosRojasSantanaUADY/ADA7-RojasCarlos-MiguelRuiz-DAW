<?php
session_start();
require_once "conexion.php";

$mensaje = "";
$tipo_alerta = "alert-success";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'registro') {
    $nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
    $correo = mysqli_real_escape_string($conexion, trim($_POST['email']));
    $perfil = mysqli_real_escape_string($conexion, $_POST['perfil']);
    $notificaciones = isset($_POST['notificaciones']) ? mysqli_real_escape_string($conexion, $_POST['notificaciones']) : 'diaria';

    $verificar = mysqli_query($conexion, "SELECT id FROM usuarios WHERE correo = '$correo' LIMIT 1");
    if (mysqli_num_rows($verificar) > 0) {
        $tipo_alerta = "alert-danger";
        $mensaje = "Ese correo ya esta registrado. Inicia sesion en el formulario de arriba.";
    } else {
        $sql = "INSERT INTO usuarios (nombre, correo, perfil, notificaciones) 
                VALUES ('$nombre', '$correo', '$perfil', '$notificaciones')";
        
        if (mysqli_query($conexion, $sql)) {
            $_SESSION['usuario_id'] = mysqli_insert_id($conexion);
            $_SESSION['usuario'] = $nombre;
            $_SESSION['correo'] = $correo;
            $_SESSION['perfil'] = $perfil;
            $tipo_alerta = "alert-success";
            $mensaje = "Registro guardado con exito! Bienvenido, $nombre.";
        } else {
            $tipo_alerta = "alert-danger";
            $mensaje = "Error al guardar en el servidor: " . mysqli_error($conexion);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'login') {
    $correo_login = mysqli_real_escape_string($conexion, trim($_POST['correo_login']));
    $sql_buscar = "SELECT id, nombre, correo, perfil FROM usuarios WHERE correo = '$correo_login' LIMIT 1";
    $resultado = mysqli_query($conexion, $sql_buscar);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        $_SESSION['usuario_id'] = $fila['id'];
        $_SESSION['usuario'] = $fila['nombre'];
        $_SESSION['correo'] = $fila['correo'];
        $_SESSION['perfil'] = $fila['perfil'];
        $tipo_alerta = "alert-success";
        $mensaje = "Sesion iniciada! Bienvenido de nuevo, " . $fila['nombre'] . ".";
    } else {
        $tipo_alerta = "alert-danger";
        $mensaje = "No encontramos ese correo. Por favor, registrate en el formulario de abajo.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADA 7 - Acceso y Registro</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <?php include "header.php"; ?>

    <main class="container">
        <div class="card mb-4">
            <div class="card-header bg-dark">
                <h2 class="h4">Iniciar Sesion (Usuarios Registrados)</h2>
            </div>
            <div class="card-body">
                <?php if ($mensaje !== ""): ?>
                    <div class="<?php echo $tipo_alerta; ?> mb-3">
                        <?php echo htmlspecialchars($mensaje); ?>
                    </div>
                <?php endif; ?>

                <p class="mb-3">Ingresa tu correo registrado:</p>
                <form method="POST" action="acceso.php" class="form-inline-login">
                    <input type="hidden" name="accion" value="login">
                    <input type="email" name="correo_login" class="form-control" placeholder="tu@correo.com" required>
                    <button type="submit" class="btn btn-primary btn-auto">Iniciar Sesion</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary">
                <h2 class="h4">Formulario de Registro Integrado</h2>
            </div>
            <div class="card-body">
                <div id="mensaje-global" class="alert-danger d-none mb-3"></div>

                <form id="formulario-registro" method="POST" action="acceso.php" novalidate>
                    <input type="hidden" name="accion" value="registro">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Juan Perez">
                        <div id="error-nombre" class="text-danger"></div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electronico *</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="juan@correo.com">
                        <div id="error-email" class="text-danger"></div>
                    </div>

                    <div class="mb-3">
                        <label for="perfil" class="form-label">Perfil de Acceso *</label>
                        <select class="form-select" id="perfil" name="perfil">
                            <option value="">-- Seleccione una opcion --</option>
                            <option value="cliente">Cliente</option>
                            <option value="tecnico">Tecnico</option>
                            <option value="administrador">Administrador</option>
                        </select>
                        <div id="error-perfil" class="text-danger"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Frecuencia de Notificaciones *</label>
                        <div class="radio-group">
                            <label><input type="radio" name="notificaciones" id="notif-diaria" value="diaria"> Diaria</label>
                            <label><input type="radio" name="notificaciones" id="notif-semanal" value="semanal"> Semanal</label>
                        </div>
                        <div id="error-notificaciones" class="text-danger"></div>
                    </div>

                    <div class="mb-3">
                        <label>
                            <input type="checkbox" id="terminos" name="terminos">
                            Acepto los terminos, condiciones y politicas de privacidad *
                        </label>
                        <div id="error-terminos" class="text-danger"></div>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar Registro</button>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <p>Elaborado por Carlos Rojas Santana y Miguel Jesus Ruiz May | Desarrollo de Aplicaciones Web</p>
    </footer>

    <script src="js/validacion.js"></script>
</body>
</html>
