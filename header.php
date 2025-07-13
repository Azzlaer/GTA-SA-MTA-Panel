<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MTA Server Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-dark text-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php">🚀 MTA Server Manager
      <?php if (isset($_SESSION['username'])): ?>
        <span class="badge bg-secondary ms-2">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
      <?php endif; ?>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse mt-2 mt-lg-0" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">🏠 Dashboard</a>
        </li>

        <!-- Menú general -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="generalDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            ⚙️ Herramientas
          </a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="generalDropdown">
            <li><a class="dropdown-item" href="ftp_manager.php">📁 FTP Manager</a></li>
			<li><a class="dropdown-item" href="register.php">👥 Account Manager</a></li>
            <li><a class="dropdown-item" href="gestionar_usuarios.php">👥 User Manager</a></li>
            <li><a class="dropdown-item" href="informacion.php">📄 Información</a></li>
            <li><a class="dropdown-item" href="estadisticas.php">📊 Estadísticas</a></li>
            <li><a class="dropdown-item" href="mysql.php">🗃️ Base de Datos</a></li>
            <li><a class="dropdown-item" href="settings.php">🎨 CSS</a></li>
            <li><a class="dropdown-item" href="bot_discord.php">🤖 BOT Discord</a></li>
          </ul>
        </li>

        <!-- Menú MTA -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="mtaDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            🧩 MTA Tools
          </a>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="mtaDropdown">
            <li><a class="dropdown-item" href="server_install.php">Install Server</a></li>
			<li><a class="dropdown-item" href="roles.php">👤 Roles</a></li>
			<li><a class="dropdown-item" href="users.php">👤 Usuarios</a></li>
            <li><a class="dropdown-item" href="crear_cuenta.php">➕ Crear Cuenta</a></li>
            <li><a class="dropdown-item" href="banlist.php">🚫 Baneados</a></li>
            <li><a class="dropdown-item" href="edit_settings.php">⚙️ Configuración</a></li>
            <li><a class="dropdown-item" href="rcon_panel.php">🖥️ RCON Panel</a></li>
            <li><a class="dropdown-item" href="edit_config.php?file=mtaserver.conf">🧾 mtaserver.conf</a></li>
            <li><a class="dropdown-item" href="edit_config.php?file=local.conf">📄 local.conf</a></li>
            <li><a class="dropdown-item" href="edit_config.php?file=editor.conf">📑 editor.conf</a></li>
            <li><a class="dropdown-item" href="acl_editor.php">🔐 ACL Editor</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link text-danger fw-bold" href="logout.php">🔓 Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
