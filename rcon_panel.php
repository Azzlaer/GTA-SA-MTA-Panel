<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include 'header.php'
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Panel RCON MTA</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    body {
        background-color: #121212;
        color: #e0e0e0;
        font-family: monospace, Consolas, monospace;
        padding: 20px;
    }
    #console {
        background-color: #000;
        color: #0f0;
        height: 300px;
        overflow-y: scroll;
        padding: 10px;
        border-radius: 5px;
        white-space: pre-wrap;
        font-size: 14px;
    }
</style>
</head>
<body>

<h3>Panel RCON MTA - Enviar comandos</h3>

<form id="cmdForm" class="mb-3">
    <div class="row g-2">
        <div class="col-md-4">
            <select id="command" class="form-select" required>
                <option value="">Selecciona comando</option>
                <option value="say">say (mensaje a todos)</option>
                <option value="kick">kick (jugador)</option>
                <option value="ban">ban (jugador)</option>
                <option value="restart">restart</option>
                <option value="status">status</option>
                <!-- agrega más comandos que uses -->
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" id="param" class="form-control" placeholder="Parámetro (opcional)" />
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Ejecutar</button>
        </div>
    </div>
</form>

<div id="console"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#cmdForm').submit(function(e) {
    e.preventDefault();
    const command = $('#command').val();
    const param = $('#param').val();

    if (!command) {
        alert('Selecciona un comando');
        return;
    }

    $.post('rcon_exec.php', {command, param}, function(data) {
        if (data.error) {
            $('#console').append(`\n[ERROR] ${data.error}\n`);
        } else {
            $('#console').append(`\n> ${command} ${param}\n${data.response}\n`);
        }
        $('#console').scrollTop($('#console')[0].scrollHeight);
    }, 'json').fail(() => {
        $('#console').append('\n[ERROR] No se pudo conectar al servidor.\n');
    });
});
</script>
    <div class="text-center">
        <a href="dashboard.php" class="btn btn-primary">Volver al Dashboard</a>
    </div>
</body>
<?php include 'footer.php';  // cierra body, html, y scripts globales ?>
</html>
