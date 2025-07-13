<?php
function getDatabaseConnection() {
    $path = 'Z:/Servidores/mta/mods/deathmatch/internal.db'; // Ruta real a tu base de datos
    try {
        $pdo = new PDO("sqlite:" . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("❌ Error al conectar con la base de datos: " . $e->getMessage());
    }
}
?>
