<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Configuración del servidor FTP
$ftp_server = "localhost";
$ftp_user = "mta";
$ftp_pass = "35027595*";
$ftp_conn = ftp_connect($ftp_server) or die("No se pudo conectar al servidor FTP");
$login = ftp_login($ftp_conn, $ftp_user, $ftp_pass);

if (!$login) {
    die("Error de autenticación en el servidor FTP");
}

ftp_pasv($ftp_conn, true);

// Obtener archivo a editar
if (!isset($_GET['file'])) {
    die("No se especificó un archivo.");
}
$file_path = $_GET['file'];
$temp_file = tempnam(sys_get_temp_dir(), 'ftp_edit');

// Descargar archivo temporalmente
if (!ftp_get($ftp_conn, $temp_file, $file_path, FTP_ASCII)) {
    die("No se pudo descargar el archivo para editar.");
}

// Guardar cambios en el archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_content'])) {
    file_put_contents($temp_file, $_POST['file_content']);
    if (ftp_put($ftp_conn, $file_path, $temp_file, FTP_ASCII)) {
        echo "<div class='alert alert-success'>Archivo guardado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al guardar el archivo.</div>";
    }
}

ftp_close($ftp_conn);
$file_content = file_get_contents($temp_file);
unlink($temp_file);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Archivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <h2>Editando: <?= basename($file_path); ?></h2>
        <form method="post">
            <div class="mb-3">
                <textarea class="form-control" name="file_content" rows="15"><?= htmlspecialchars($file_content); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="ftp_manager.php?dir=<?= urlencode(dirname($file_path)); ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
