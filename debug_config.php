<?php
$configFile = __DIR__ . "/config.ini";

if (!file_exists($configFile)) {
    die("<div style='color: red;'>❌ Error: El archivo config.ini no se encuentra en la ruta especificada.</div>");
}

$content = file_get_contents($configFile);
echo "<h3>Contenido de config.ini:</h3><pre>" . htmlspecialchars($content) . "</pre>";

echo "<h3>Detectando BOM:</h3>";
if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
    echo "<div style='color: orange;'>⚠️ El archivo tiene BOM. PHP no puede leerlo correctamente.</div>";
} else {
    echo "<div style='color: green;'>✅ No se detectó BOM en el archivo.</div>";
}

$config = parse_ini_file($configFile, true);
if ($config === false) {
    die("<div style='color: red;'>❌ Error: No se pudo leer config.ini. Puede haber un problema de formato o permisos.</div>");
}

echo "<h3>Resultado de parse_ini_file:</h3><pre>";
print_r($config);
echo "</pre>";
?>
