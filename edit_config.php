<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['file'])) {
    die("Archivo no especificado.");
}

$basePath = "C:\\Games\\mta\\mods\\deathmatch\\";
$allowedFiles = ["mtaserver.conf", "local.conf", "editor.conf"];
$fileName = basename($_GET['file']);

if (!in_array($fileName, $allowedFiles)) {
    die("Archivo no permitido.");
}

$filePath = $basePath . $fileName;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents($filePath, $_POST['content']);
    $message = "<div class='alert alert-success'>Archivo guardado correctamente.</div>";
}

$content = file_exists($filePath) ? file_get_contents($filePath) : "No se pudo cargar el archivo.";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Configuración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mt-4">
        <h2>Editando: <?php echo htmlspecialchars($fileName); ?></h2>
        <?php if (isset($message)) echo $message; ?>
        <form method="post">
            <div class="mb-3">
                <textarea class="form-control" name="content" rows="15"><?php echo htmlspecialchars($content); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="dashboard.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</body>
</html>
