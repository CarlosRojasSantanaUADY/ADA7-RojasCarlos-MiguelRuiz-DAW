<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="container nav-flex">
        <a class="navbar-brand" href="index.php">Sitio Oficial de TecnoFix</a>
        <ul class="navbar-nav">
            <li>
                <a class="nav-link <?php echo ($pagina_actual == 'index.php') ? 'active' : ''; ?>" href="index.php">Inicio</a>
            </li>
            <li>
                <a class="nav-link <?php echo ($pagina_actual == 'servicios.php') ? 'active' : ''; ?>" href="servicios.php">Servicios</a>
            </li>
            <li>
                <a class="nav-link <?php echo ($pagina_actual == 'ordenes.php') ? 'active' : ''; ?>" href="ordenes.php">Ordenes</a>
            </li>
            <li>
                <a class="nav-link <?php echo ($pagina_actual == 'acceso.php') ? 'active' : ''; ?>" href="acceso.php">Acceso</a>
            </li>
            <?php if (isset($_SESSION['usuario'])): ?>
                <li>
                    <a class="nav-link nav-logout" href="logout.php">Cerrar Sesion (<?php echo htmlspecialchars($_SESSION['usuario']); ?>)</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

