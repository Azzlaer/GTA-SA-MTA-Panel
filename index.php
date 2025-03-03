<?php
// Leer configuración desde config.ini
$config = parse_ini_file("config_css.ini");
$background_mode = $config['background_mode'] ?? 'image';
$background_file = $config['background_file'] ?? 'background.jpg';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Contenedor del video */
        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            display: <?= ($background_mode == 'video') ? 'block' : 'none'; ?>;
        }

        /* Ajustar video para que cubra toda la pantalla */
        .video-container video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            object-fit: cover;
        }

        /* Imagen de fondo */
        body {
            background: url('<?= $background_file; ?>') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            <?= ($background_mode == 'video') ? 'background: none;' : ''; ?>
        }

        .container-box {
            background: rgba(255, 255, 255, 0.8);
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

<!-- Video de fondo -->
<div class="video-container">
    <?php if ($background_mode == 'video') { ?>
        <video autoplay loop muted playsinline>
            <source src="<?= $background_file; ?>" type="video/mp4">
            Tu navegador no soporta videos en HTML5.
        </video>
    <?php } ?>
</div>

<!-- Formulario de inicio de sesión -->
<div class="container-box">
    <h2 class="mb-4">Bienvenido</h2>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="login">
        <div class="mb-3">
            <label for="login-username" class="form-label">Usuario:</label>
            <input type="text" class="form-control" id="login-username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="login-password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="login-password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
