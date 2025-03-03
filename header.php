<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">MTA Server Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>					
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="configDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Ajustes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="configDropdown">
						<li><a class="dropdown-item" href="ftp_manager.php">FTP</a></li>
						<li><a class="dropdown-item" href="estadisticas.php">Estadisticas</a></li>
						<li><a class="dropdown-item" href="mysql.php">Base de Datos</a></li>
						<li><a class="dropdown-item" href="settings.php">CSS</a></li>
						<li><a class="dropdown-item" href="bot_discord.php">BOT Discord</a></li>
                        <li><a class="dropdown-item" href="edit_config.php?file=mtaserver.conf">mtaserver.conf</a></
                        <li><a class="dropdown-item" href="edit_config.php?file=local.conf">local.conf</a></li>
                        <li><a class="dropdown-item" href="edit_config.php?file=editor.conf">editor.conf</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Agregar Bootstrap JS para el menú desplegable -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
