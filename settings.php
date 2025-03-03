<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$configFile = "config_css.ini";
$uploadDir = "uploads/";

// Crear carpeta de subida si no existe
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Guardar cambios en config.ini
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['background_mode'])) {
        $newConfig = "[SETTINGS]\n";
        $newConfig .= "background_mode=" . $_POST['background_mode'] . "\n";
        
        if (!empty($_POST['image_url'])) {
            $newConfig .= "background_file=" . $_POST['image_url'] . "\n";
        } elseif (!empty($_POST['selected_file'])) {
            $newConfig .= "background_file=" . $_POST['selected_file'] . "\n";
        }
        
        file_put_contents($configFile, $newConfig);
        header("Location: settings.php");
        exit();
    }
}

// Eliminar archivo
if (isset($_POST['delete_file'])) {
    $fileToDelete = $_POST['delete_file'];
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        header("Location: settings.php");
        exit();
    }
}

// Subir archivo
if (!empty($_FILES['file_upload']['name'])) {
    $fileName = basename($_FILES['file_upload']['name']);
    $targetFile = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'mp4'];
    
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $targetFile)) {
            echo "<div class='alert alert-success'>Archivo subido correctamente: $fileName</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al subir el archivo.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Solo se permiten archivos JPG y MP4.</div>";
    }
}

// Leer configuración actual
$config = parse_ini_file($configFile);
$currentMode = $config['background_mode'] ?? 'image';
$currentFile = $config['background_file'] ?? '';

// Obtener lista de archivos en uploads/
$files = array_diff(scandir($uploadDir), ['.', '..']);
$images = array_filter($files, fn($file) => preg_match('/\.(jpg|jpeg)$/i', $file));
$videos = array_filter($files, fn($file) => preg_match('/\.(mp4)$/i', $file));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <h2>Configuración del fondo</h2>
        <form method="post">
            <div class="form-group">
                <label for="background_mode">Selecciona el fondo:</label>
                <select name="background_mode" class="form-control">
                    <option value="video" <?= ($currentMode == 'video') ? 'selected' : ''; ?>>Video</option>
                    <option value="image" <?= ($currentMode == 'image') ? 'selected' : ''; ?>>Imagen</option>
                </select>
            </div>

            <h2 class="mt-5">Imágenes disponibles</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Vista Previa</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($images as $file) { ?>
                        <tr>
                            <td><input type="radio" name="selected_file" value="uploads/<?= $file; ?>" <?= ($currentFile == "uploads/$file") ? 'checked' : ''; ?>></td>
                            <td><img src="uploads/<?= $file; ?>" width="100" height="50" alt="Vista previa"></td>
                            <td><?= $file; ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete_file" value="uploads/<?= $file; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h2 class="mt-5">Videos disponibles</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Vista Previa</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($videos as $file) { ?>
                        <tr>
                            <td><input type="radio" name="selected_file" value="uploads/<?= $file; ?>" <?= ($currentFile == "uploads/$file") ? 'checked' : ''; ?>></td>
                            <td>
                                <video width="100" height="50" controls>
                                    <source src="uploads/<?= $file; ?>" type="video/mp4">
                                </video>
                            </td>
                            <td><?= $file; ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete_file" value="uploads/<?= $file; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
        </form>

        <h2 class="mt-5">Subir imagen o video</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="file_upload" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success mt-3">Subir Archivo</button>
        </form>
    </div>
</body>
</html>
