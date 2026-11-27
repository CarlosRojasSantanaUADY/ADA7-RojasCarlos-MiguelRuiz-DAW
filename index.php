<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADA 7 - Desarrollo de Aplicaciones Web</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <?php include "header.php"; ?>

    <header class="container">
        <div class="p-5">
            <h1 class="display-5">TecnoFix - Gestion de Reparaciones</h1>
            <p class="lead">ADA 7 Aplicación web en línea</p>
            
            <?php if (isset($_SESSION['usuario'])): ?>
                <div class="alert-success">
                    Sesion activa: <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong> 
                    (Perfil: <?php echo htmlspecialchars($_SESSION['perfil']); ?>) | 
                    <a href="ordenes.php">Ir a gestionar ordenes</a>
                </div>
            <?php else: ?>
                <div class="alert-warning">
                    Navegando como invitado. <a href="acceso.php">Inicia sesion o registrate</a> para crear y administrar ordenes.
                </div>
            <?php endif; ?>

            <div class="alert-info">
                <strong>Fecha de consulta:</strong> <span id="fecha-actual">Cargando fecha...</span>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="row">
            <section class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="h5">Calculadora Interactiva (JavaScript)</h3>
                    </div>
                    <div class="card-body bg-dark">
                        <input type="text" id="pantalla-calculadora" class="form-control-lg" readonly placeholder="0">
                        <div class="g-2">
                            <button class="btn btn-danger" onclick="limpiarPantalla()">C</button>
                            <button class="btn btn-secondary" onclick="agregarAPantalla('/')">/</button>
                            <button class="btn btn-secondary" onclick="agregarAPantalla('*')">*</button>
                            <button class="btn btn-secondary" onclick="agregarAPantalla('-')">-</button>

                            <button class="btn btn-light" onclick="agregarAPantalla('7')">7</button>
                            <button class="btn btn-light" onclick="agregarAPantalla('8')">8</button>
                            <button class="btn btn-light" onclick="agregarAPantalla('9')">9</button>
                            <button class="btn btn-secondary" onclick="agregarAPantalla('+')">+</button>

                            <button class="btn btn-light" onclick="agregarAPantalla('4')">4</button>
                            <button class="btn btn-light" onclick="agregarAPantalla('5')">5</button>
                            <button class="btn btn-light" onclick="agregarAPantalla('6')">6</button>
                            <button class="btn btn-success" onclick="calcularResultado()">=</button>

                            <button class="btn btn-light" onclick="agregarAPantalla('1')">1</button>
                            <button class="btn btn-light" onclick="agregarAPantalla('2')">2</button>
                            <button class="btn btn-light" onclick="agregarAPantalla('3')">3</button>
                            <button class="btn btn-light" onclick="agregarAPantalla('0')">0</button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark">
                        <h3 class="h5">Servicios del Taller TecnoFix</h3>
                    </div>
                    <div class="card-body">
                        <p>Sistema de control de soporte tecnico y reparacion de equipos de computo.</p>
                        <ul class="lista-servicios">
                            <li><strong>Diagnostico de Hardware:</strong> Revision de fuentes, placas y componentes.</li>
                            <li><strong>Mantenimiento Preventivo:</strong> Limpieza interna y cambio de pasta termica.</li>
                            <li><strong>Soporte de Software:</strong> Formateo, respaldo y eliminacion de virus.</li>
                        </ul>
                        <div class="mt-3">
                            <a href="ordenes.php" class="btn btn-primary">Ver Ordenes de Reparacion</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>Elaborado por Carlos Rojas Santana y Miguel Jesus Ruiz May | Desarrollo de Aplicaciones Web</p>
    </footer>

    <script src="js/app.js"></script>
    <script src="js/calculadora.js"></script>
</body>
</html>
