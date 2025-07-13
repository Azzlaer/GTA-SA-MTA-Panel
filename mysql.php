<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "mta";
$password = "yi[JHe*r4Kton*!M";
$dbname = "latinbat_mta";
$port = 3306;

// Verificar estado del servicio MySQL
$mysqlOnline = false;
$mysqli = @new mysqli($servername, $username, $password, $dbname, $port);
if ($mysqli->connect_errno) {
    $mysqlStatus = "Offline";
    $mysqlStatusClass = "bg-danger";
} else {
    $mysqlOnline = true;
    $mysqlStatus = "Online";
    $mysqlStatusClass = "bg-success";
    $mysqli->close();
}

// Verificar si el puerto 3306 está abierto
$portStatus = fsockopen($servername, $port, $errno, $errstr, 2) ? "Online" : "Offline";
$portStatusClass = ($portStatus === "Online") ? "bg-success" : "bg-danger";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <h2>Estado del Servidor MySQL</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>MySQL</td>
                    <td><span class="badge <?= $mysqlStatusClass; ?>"> <?= $mysqlStatus; ?> </span></td>
                </tr>
                <tr>
                    <td>Puerto 3306</td>
                    <td><span class="badge <?= $portStatusClass; ?>"> <?= $portStatus; ?> </span></td>
                </tr>
            </tbody>
        </table>

        <h2 class="mt-5">Credenciales de MySQL</h2>
        <form>
            <div class="mb-3">
                <label class="form-label">Servidor:</label>
                <input type="text" class="form-control" value="<?= $servername; ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input type="text" class="form-control" value="<?= $username; ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <input type="text" class="form-control" value="<?= $password; ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Base de Datos:</label>
                <input type="text" class="form-control" value="<?= $dbname; ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Puerto:</label>
                <input type="text" class="form-control" value="<?= $port; ?>" readonly>
            </div>
        </form>
    </div>
</body>
<?php include 'footer.php';  // cierra body, html, y scripts globales ?>
</html>