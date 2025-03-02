<?php
header("Content-Type: text/plain");

$serverPath = "C:\\Games\\mta\\MTA Server.exe";

if (file_exists($serverPath)) {
    $command = "start \"\" \"$serverPath\"";
    pclose(popen($command, "r"));
    echo "<span class='text-success'>Servidor iniciado correctamente.</span>";
} else {
    echo "<span class='text-danger'>Error: No se encontró el ejecutable.</span>";
}
?>
