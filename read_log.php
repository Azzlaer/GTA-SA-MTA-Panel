<?php
$logFile = "Z:\\Servidores\\mta\\mods\\deathmatch\\logs\\server.log";

if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -50); // Muestra las últimas 50 líneas
    
    echo "<pre style='color: #fff;'>";
    foreach ($lastLines as $line) {
        echo htmlspecialchars($line) . "<br>";
    }
    echo "</pre>";
} else {
    echo "<pre style='color: red;'>El archivo de log no existe.</pre>";
}
?>
