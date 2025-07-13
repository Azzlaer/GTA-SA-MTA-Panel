<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$mtaserverFile = "Z:\\Servidores\\mta\\mods\\deathmatch\\mtaserver.conf";

if (!file_exists($mtaserverFile)) {
    die("<div class='alert alert-danger'>Error: mtaserver.conf no encontrado. Verifique la ubicación.</div>");
}

// Cargar el contenido del archivo
$xml = simplexml_load_file($mtaserverFile);
if (!$xml) {
    die("<div class='alert alert-danger'>Error al leer mtaserver.conf. Verifique la sintaxis.</div>");
}

// Guardar cambios en mtaserver.conf
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_config'])) {
    foreach ($_POST as $key => $value) {
        if ($key !== 'save_config') {
            if (isset($xml->$key)) {
                $xml->$key = htmlspecialchars($value);
            }
        }
    }
    $xml->asXML($mtaserverFile);
    $message = "<div class='alert alert-success'>Configuración guardada correctamente.</div>";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Configuración del Servidor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mt-4">
        <h2>Editar Configuración del Servidor</h2>
        <?php if (isset($message)) echo $message; ?>
        <div class="card mb-3">
            <div class="card-header">Parámetros del Servidor</div>
            <div class="card-body">
                <form method="post">
                    <?php foreach ($xml->children() as $key => $value) { ?>
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
