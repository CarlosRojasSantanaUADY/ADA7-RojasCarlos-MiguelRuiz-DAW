<?php
$servidor = "localhost";
$usuario_bd = "root";
$password_bd = "";
$nombre_bd = "tecnofix_db";

$conexion = mysqli_connect($servidor, $usuario_bd, $password_bd, $nombre_bd);

if (!$conexion) {
    die("Error de conexion a la base de datos de TecnoFix: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8mb4");
?>
