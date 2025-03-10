<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$configFile = __DIR__ . "/config_discord.ini";
$logFile = __DIR__ . "/mta_log_monitor.log";

// Verificar si config_discord.ini existe
if (!file_exists($configFile)) {
    die("<div class='alert alert-danger'>Error: config_discord.ini no encontrado. Verifique la ubicación.</div>");
}

// Asegurar que el archivo config_discord.ini está en UTF-8 sin BOM
$content = file_get_contents($configFile);
if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
    file_put_contents($configFile, substr($content, 3));
}

// Verificar si config_discord.ini tiene formato correcto
$config = @parse_ini_file($configFile);
if ($config === false) {
    die("<div class='alert alert-danger'>Error: No se pudo leer config_discord.ini. Asegúrese de que tenga la sintaxis correcta y esté en formato UTF-8 sin BOM.</div>");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión del Bot de Discord</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mt-4">
        <h2>Gestión del Bot de Discord</h2>
        <?php if (isset($message)) echo $message; ?>

        <div class="card mb-3">
            <div class="card-header">Editar Configuración</div>
            <div class="card-body">
                <form method="post">
                    <?php foreach ($config as $key => $value) { ?>
                        <div class="mb-3">
                            <label class="form-label"><?php echo strtoupper($key); ?>:</label>
                            <input type="text" class="form-control" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
                        </div>
                    <?php } ?>
                    <button type="submit" name="save_config" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
