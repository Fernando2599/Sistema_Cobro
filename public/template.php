<!doctype html>
<html lang="en">
<head>
    <title>Inicio</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/css/img_current.css">
    <link rel="stylesheet" href="public/css/alertas.css">
    <script src="public/js/mensajes.js"></script>
</head>

<body>
    <div id="alert-container" class="alert-container"></div>

    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="nav navbar-nav">
        <a class="nav-item nav-link active" href="#" aria-current="page">
            Sistema de cobro <span class="visually-hidden">(current)</span>
        </a>

            
            
            <?php if (isset($_SESSION['rol_id'])): ?>
                <?php if ($_SESSION['rol_id'] == '1'): ?>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'paginas' && ($_GET['accion'] ?? '') === 'inicio' ? 'active' : '' ?>" href="?controlador=paginas&accion=inicio"><i class="bi bi-house"></i> Inicio</a>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'ventas' && ($_GET['accion'] ?? '') === 'crear' ? 'active' : '' ?>" href="?controlador=ventas&accion=crear"><i class="bi-currency-dollar"></i> Venta</a>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'cortes' && ($_GET['accion'] ?? '') === 'crear' ? 'active' : '' ?>" href="?controlador=cortes&accion=crear"><i class="bi bi-scissors"></i> Corte</a>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'ventas' && ($_GET['accion'] ?? '') === 'informacion' ? 'active' : '' ?>" href="?controlador=ventas&accion=informacion"><i class="bi bi-cash-stack"></i> Información de Venta</a>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'depositos' && ($_GET['accion'] ?? '') === 'crear' ? 'active' : '' ?>" href="?controlador=depositos&accion=crear"><i class="bi bi-bank"></i> Depositos</a>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'depositos' && ($_GET['accion'] ?? '') === 'reportes' ? 'active' : '' ?>" href="?controlador=depositos&accion=reportes"><i class="bi bi-file-earmark-text"></i> Reportes</a>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'iniciar' && ($_GET['accion'] ?? '') === 'logout' ? 'active' : '' ?>" href="?controlador=iniciar&accion=logout"><i class="bi bi-box-arrow-right"></i> Salir</a>
                <?php elseif ($_SESSION['rol_id'] == '2'): ?>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'paginas' && ($_GET['accion'] ?? '') === 'inicio' ? 'active' : '' ?>" href="?controlador=paginas&accion=inicio"><i class="bi bi-house"></i> Inicio</a>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'user' && ($_GET['accion'] ?? '') === 'inicio' ? 'active' : '' ?>" href="?controlador=user&accion=inicio"><i class="bi bi-person-badge"></i> Perfiles</a>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'clientes' && ($_GET['accion'] ?? '') === 'inicio' ? 'active' : '' ?>" href="?controlador=clientes&accion=inicio"><i class="bi bi-people-fill"></i> Clientes</a>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'ventas' && ($_GET['accion'] ?? '') === 'informacion' ? 'active' : '' ?>" href="?controlador=ventas&accion=informacion"><i class="bi bi-cash-stack"></i> Información de Venta</a>
                    <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'iniciar' && ($_GET['accion'] ?? '') === 'logout' ? 'active' : '' ?>" href="?controlador=iniciar&accion=logout"><i class="bi bi-box-arrow-right"></i> Salir</a>
                <?php endif; ?>
            <?php else: ?>
                <a class="nav-item nav-link <?= ($_GET['controlador'] ?? '') === 'iniciar' && ($_GET['accion'] ?? '') === 'login' ? 'active' : '' ?>" href="?controlador=iniciar&accion=login"><i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php include_once("ruteador.php"); ?>
            </div>            
        </div>
    </div>

    <header>
        <!-- place navbar here -->
    </header>
    <main></main>
    <footer>
        <!-- place footer here -->
    </footer>

    <!-- Bootstrap JavaScript Libraries -->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"
    ></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"
    ></script>
</body>
</html>
