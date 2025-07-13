<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$configFile = "Z:/MyProjects/MTA_BOT/config_discord.ini";
$pythonScript = "Z:/MyProjects/MTA_BOT/mta_log_monitor.py";

// Función para verificar si el proceso Python está corriendo
function isPythonProcessRunning($scriptPath) {
    $scriptName = basename($scriptPath);
    $scriptName = strtolower($scriptName);

    exec('wmic process where "name=\'python.exe\'" get ProcessId,CommandLine /FORMAT:LIST', $output);

    $outputString = implode("\n", array_map('strtolower', $output));
    return (strpos($outputString, $scriptName) !== false);
}

// Iniciar proceso Python
function startPythonProcess($scriptPath) {
    $command = "cmd /c start \"MTA Log Monitor\" /B python \"$scriptPath\" > NUL 2>&1";
    pclose(popen($command, "r"));
}

// Detener proceso Python
function stopPythonProcess($scriptPath) {
    $scriptName = basename($scriptPath);
    $scriptName = strtolower($scriptName);

    exec('wmic process where "name=\'python.exe\'" get ProcessId,CommandLine /FORMAT:LIST', $output);
    $pidsToKill = [];

    foreach ($output as $line) {
        if (stripos($line, 'commandline=') === 0) {
            $cmdLine = strtolower(substr($line, strlen('commandline=')));
            if (strpos($cmdLine, $scriptName) !== false) {
                $key = array_search($line, $output);
                if (isset($output[$key + 1]) && strpos($output[$key + 1], 'processid=') === 0) {
                    $pid = (int)substr($output[$key + 1], strlen('processid='));
                    $pidsToKill[] = $pid;
                }
            }
        }
    }

    foreach ($pidsToKill as $pid) {
        exec("taskkill /PID $pid /F");
    }
}

// Manejo POST para iniciar/detener y guardar configuración
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_config'])) {
        // Guardar configuración modificada en config_discord.ini
        $newConfig = [];
        foreach ($_POST as $key => $value) {
            if ($key !== 'save_config') {
                $newConfig[$key] = $value;
            }
        }
        $content = "[SETTINGS]\n";
        foreach ($newConfig as $k => $v) {
            $vEscaped = str_replace(["\\", "\""], ["\\\\", "\\\""], $v);
            $content .= "$k = \"$vEscaped\"\n";
        }
        file_put_contents($configFile, $content);
        $message = "<div class='alert alert-success'>Configuración guardada correctamente.</div>";
    } elseif (isset($_POST['action'])) {
        if ($_POST['action'] === 'start') {
            if (!isPythonProcessRunning($pythonScript)) {
                startPythonProcess($pythonScript);
                $message = "<div class='alert alert-success'>Script iniciado correctamente.</div>";
            } else {
                $message = "<div class='alert alert-info'>El script ya está en ejecución.</div>";
            }
        } elseif ($_POST['action'] === 'stop') {
            if (isPythonProcessRunning($pythonScript)) {
                stopPythonProcess($pythonScript);
                $message = "<div class='alert alert-warning'>Script detenido correctamente.</div>";
            } else {
                $message = "<div class='alert alert-info'>El script no está en ejecución.</div>";
            }
        }
    }
}

// Cargar configuración
if (!file_exists($configFile)) {
    die("<div class='alert alert-danger'>Error: config_discord.ini no encontrado. Verifique la ubicación.</div>");
}
$content = file_get_contents($configFile);
if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
    file_put_contents($configFile, substr($content, 3));
}
$config = @parse_ini_file($configFile);
if ($config === false) {
    die("<div class='alert alert-danger'>Error: No se pudo leer config_discord.ini. Asegúrese de que tenga la sintaxis correcta y esté en formato UTF-8 sin BOM.</div>");
}

$isRunning = isPythonProcessRunning($pythonScript);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión del Bot de Discord</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container my-4" style="max-width: 900px;">
        <h2 class="mb-4 text-center">Gestión del Bot de Discord</h2>

        <?php if ($message) echo $message; ?>

        <div class="row g-4">

            <!-- Card Configuración -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm bg-dark text-light h-100">
                    <div class="card-header"><strong>Editar Configuración</strong></div>
                    <div class="card-body">
                        <form method="post">
                            <?php foreach ($config as $key => $value): ?>
                                <div class="mb-3">
                                    <label class="form-label text-capitalize" for="input-<?=htmlspecialchars($key)?>">
                                        <?=htmlspecialchars($key)?>:
                                    </label>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-light"
                                           id="input-<?=htmlspecialchars($key)?>" name="<?=htmlspecialchars($key)?>"
                                           value="<?=htmlspecialchars($value)?>" />
                                </div>
                            <?php endforeach; ?>
                            <button type="submit" name="save_config" class="btn btn-primary w-100">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card Control Monitor -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm bg-dark text-light h-100 d-flex flex-column">
                    <div class="card-header"><strong>Control del Monitor de Logs</strong> <small>(mta_log_monitor.py)</small></div>
                    <div class="card-body d-flex flex-column justify-content-between flex-grow-1">
                        <p>Estado actual:
                            <?php if ($isRunning): ?>
                                <span class="badge bg-success">En ejecución</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Detenido</span>
                            <?php endif; ?>
                        </p>

                        <div class="d-grid gap-2 mt-auto">
                            <form method="post" class="mb-2">
                                <input type="hidden" name="action" value="start" />
                                <button type="submit" class="btn btn-success" <?php if ($isRunning) echo "disabled"; ?>>Iniciar Monitor</button>
                            </form>

                            <form method="post">
                                <input type="hidden" name="action" value="stop" />
                                <button type="submit" class="btn btn-danger" <?php if (!$isRunning) echo "disabled"; ?>>Detener Monitor</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
