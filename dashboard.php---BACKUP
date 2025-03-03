<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

function isProcessRunning($processName) {
    $output = shell_exec('tasklist /FI "IMAGENAME eq ' . $processName . '"');
    return strpos($output, $processName) !== false;
}

if (isset($_POST['start_server'])) {
    pclose(popen("start /B C:\\Games\\mta\\MTA Server.exe", "r"));
}

if (isset($_POST['stop_server'])) {
    shell_exec("taskkill /F /IM \"MTA Server.exe\"");
}

$server_status = isProcessRunning("MTA Server.exe") ? "Online" : "Offline";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<?php
include 'header.php'
?>

<body>
    <div class="container mt-5">
        <h1 class="text-center">MTA Server Dashboard</h1>
        <div class="card mt-3">
            <div class="card-header">Servidor</div>
            <div class="card-body">
                <p><strong>Estado del Servidor:</strong> <span id="server-status" class="badge bg-<?php echo $server_status == 'Online' ? 'success' : 'danger'; ?>"> <?php echo $server_status; ?> </span></p>
                <form method="post">
                    <button id="startServer" class="btn btn-success">Iniciar Servidor</button>
<div id="serverMessage" class="mt-2"></div>

<script>
    $(document).ready(function() {
        $("#startServer").click(function() {
            $.post("start_server.php", function(response) {
                $("#serverMessage").html(response);
                checkServerStatus();
            });
        });
    });
</script>

                    <button type="submit" name="stop_server" class="btn btn-danger">Detener Servidor</button>
                    <button type="button" onclick="checkServerStatus()" class="btn btn-primary">Actualizar Estado</button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">Consola del Servidor</div>
            <div class="card-body">
                <button class="btn btn-primary" id="start-log">Iniciar Visualizador</button>
                <button class="btn btn-danger" id="stop-log">Detener Visualizador</button>
                <div class="mt-3 p-2 border bg-dark text-light" style="height: 300px; overflow-y: scroll;" id="log-viewer"></div>
            </div>
        </div>
    </div>

    <script>
        function checkServerStatus() {
            $.get("check_status.php", function(data) {
                $("#server-status").text(data.trim());
                $("#server-status").removeClass("bg-success bg-danger").addClass(data.trim() === "Online" ? "bg-success" : "bg-danger");
            });
        }

        let logInterval;
        $("#start-log").click(function() {
            logInterval = setInterval(function() {
                $("#log-viewer").load("read_log.php");
            }, 2000);
        });

        $("#stop-log").click(function() {
            clearInterval(logInterval);
        });
    </script>
</body>
</html>
