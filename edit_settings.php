<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<?php
$settingsFile = 'Z:\Servidores\mta\mods\deathmatch\settings.xml';

if (!file_exists($settingsFile)) {
    // Crear archivo con valores por defecto si no existe
    $defaultXML = <<<XML
<settings>
    <serverName value="MiServidor MTA RP" />
    <maxPlayers value="64" />
    <gameMode value="Roleplay" />
    <timeLimit value="30" />
    <enableVoiceChat value="true" />
</settings>
XML;
    file_put_contents($settingsFile, $defaultXML);
}

$xml = simplexml_load_file($settingsFile);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Actualizar valores desde el formulario
    $xml->serverName['value'] = $_POST['serverName'];
    $xml->maxPlayers['value'] = intval($_POST['maxPlayers']);
    $xml->gameMode['value'] = $_POST['gameMode'];
    $xml->timeLimit['value'] = intval($_POST['timeLimit']);
    $xml->enableVoiceChat['value'] = isset($_POST['enableVoiceChat']) ? 'true' : 'false';

    $xml->asXML($settingsFile);
    $msg = "Configuración guardada correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Configuración MTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-dark text-light p-4">
    <div class="container" style="max-width: 600px;">
        <h2 class="mb-4">Configuración del servidor MTA</h2>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <form method="post" class="mb-5">
            <div class="mb-3">
                <label for="serverName" class="form-label">Nombre del Servidor</label>
                <input type="text" class="form-control" id="serverName" name="serverName" required
                    value="<?= htmlspecialchars($xml->serverName['value']) ?>" />
            </div>

            <div class="mb-3">
                <label for="maxPlayers" class="form-label">Máximo de jugadores</label>
                <input type="number" class="form-control" id="maxPlayers" name="maxPlayers" min="1" max="128" required
                    value="<?= (int)$xml->maxPlayers['value'] ?>" />
            </div>

            <div class="mb-3">
                <label for="gameMode" class="form-label">Modo de juego</label>
                <select class="form-select" id="gameMode" name="gameMode">
                    <?php
                    $modes = ['Roleplay', 'Freeroam', 'Deathmatch', 'Racing'];
                    foreach ($modes as $mode) {
                        $selected = ((string)$xml->gameMode['value'] === $mode) ? 'selected' : '';
                        echo "<option value=\"$mode\" $selected>$mode</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="timeLimit" class="form-label">Tiempo límite (minutos)</label>
                <input type="number" class="form-control" id="timeLimit" name="timeLimit" min="1" max="120" required
                    value="<?= (int)$xml->timeLimit['value'] ?>" />
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="enableVoiceChat" name="enableVoiceChat"
                    <?= ((string)$xml->enableVoiceChat['value'] === 'true') ? 'checked' : '' ?>>
                <label class="form-check-label" for="enableVoiceChat">Activar Chat de Voz</label>
            </div>

            <button type="submit" class="btn btn-primary">Guardar configuración</button>
			
        </form>
    </div>
	    <div class="text-center">
        <a href="dashboard.php" class="btn btn-primary">Volver al Dashboard</a>
    </div>
</body>
</html>
