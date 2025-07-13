<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include 'db.php';

function getServerInfo()
{
    $data = [];
    $data['Sistema Operativo'] = php_uname();
    $data['Cantidad de Procesadores'] = getenv("NUMBER_OF_PROCESSORS") ?: "No disponible";

    // Obtener memoria total en GB para Windows
    $total_memory = shell_exec("wmic computersystem get TotalPhysicalMemory");
    preg_match('/[0-9]+/', $total_memory, $matches);
    if (isset($matches[0])) {
        $total_memory = round((float)$matches[0] / 1024 / 1024 / 1024, 2);
        $data['Memoria RAM Total'] = $total_memory . ' GB';
    } else {
        $data['Memoria RAM Total'] = 'No disponible';
    }

    // Obtener memoria libre en KB y convertir a GB para Windows
    $free_memory = shell_exec("wmic OS get FreePhysicalMemory");
    preg_match('/[0-9]+/', $free_memory, $matches);
    if (isset($matches[0])) {
        $free_memory = round((float)$matches[0] / 1024 / 1024, 2);
        $used_memory = $total_memory - $free_memory;
        $data['Memoria Usada'] = $used_memory . ' GB';
    } else {
        $data['Memoria Usada'] = 'No disponible';
    }

    // Obtener cantidad de procesos abiertos
    $process_count = shell_exec("wmic process get Name | find /c /v \"\"");
    $data['Cantidad de Procesos Abiertos'] = trim($process_count) ?: "No disponible";

    $disk_total = round(disk_total_space("C:") / 1024 / 1024 / 1024, 2) . ' GB';
    $disk_free = round(disk_free_space("C:") / 1024 / 1024 / 1024, 2) . ' GB';
    $data['Espacio en Disco'] = "$disk_free / $disk_total";

    global $conn;
    $result = $conn->query("SELECT VERSION() AS mysql_version");
    $row = $result->fetch_assoc();
    $data['Versión MySQL'] = $row['mysql_version'];

    $data['Versión Apache'] = $_SERVER['SERVER_SOFTWARE'];

    return $data;
}

$serverInfo = getServerInfo();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas del Servidor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Estadísticas del Servidor</h2>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
        <tr>
            <th>Descripción</th>
            <th>Información</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($serverInfo as $key => $value): ?>
            <tr>
                <td><?php echo htmlspecialchars($key); ?></td>
                <td><?php echo htmlspecialchars($value); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-center">
        <a href="dashboard.php" class="btn btn-primary">Volver al Dashboard</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>