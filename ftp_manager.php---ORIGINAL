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
$current_dir = isset($_GET['dir']) ? $_GET['dir'] : "/";
$file_list = ftp_rawlist($ftp_conn, $current_dir);

// Subir archivo
if (isset($_POST['upload']) && isset($_FILES['file'])) {
    $target_file = $current_dir . "/" . basename($_FILES['file']['name']);
    if (ftp_put($ftp_conn, $target_file, $_FILES['file']['tmp_name'], FTP_BINARY)) {
        echo "<div class='alert alert-success'>Archivo subido correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al subir el archivo.</div>";
    }
}

// Crear carpeta
if (isset($_POST['create_folder']) && !empty($_POST['folder_name'])) {
    $new_folder = $current_dir . "/" . $_POST['folder_name'];
    if (ftp_mkdir($ftp_conn, $new_folder)) {
        echo "<div class='alert alert-success'>Carpeta creada correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al crear la carpeta.</div>";
    }
}

// Eliminar archivo o carpeta
if (isset($_POST['delete_file'])) {
    $delete_path = $_POST['delete_file'];
    if (ftp_delete($ftp_conn, $delete_path)) {
        echo "<div class='alert alert-success'>Archivo eliminado.</div>";
    } elseif (ftp_rmdir($ftp_conn, $delete_path)) {
        echo "<div class='alert alert-success'>Carpeta eliminada.</div>";
    } else {
        echo "<div class='alert alert-danger'>No se pudo eliminar.</div>";
    }
}

ftp_close($ftp_conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor FTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <h2>Gestor de Archivos FTP</h2>
        <p>Directorio actual: <strong><?= $current_dir; ?></strong></p>
        <a href="ftp_manager.php?dir=/" class="btn btn-primary">Ir a raíz</a>
        <form method="post" enctype="multipart/form-data" class="mt-3">
            <input type="file" name="file" required>
            <button type="submit" name="upload" class="btn btn-primary">Subir Archivo</button>
        </form>
        <form method="post" class="mt-3">
            <input type="text" name="folder_name" placeholder="Nombre de la carpeta" required>
            <button type="submit" name="create_folder" class="btn btn-success">Crear Carpeta</button>
        </form>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($file_list as $file) {
                    $file_parts = preg_split('/\s+/', $file, 9);
                    $file_name = $file_parts[8];
                    $is_dir = substr($file_parts[0], 0, 1) === 'd';
                    ?>
                    <tr>
                        <td>
                            <?php if ($is_dir) { ?>
                                <a href="ftp_manager.php?dir=<?= urlencode($current_dir . '/' . $file_name); ?>">
                                    📁 <?= $file_name; ?>
                                </a>
                            <?php } else { ?>
                                <?= $file_name; ?>
                            <?php } ?>
                        </td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="delete_file" value="<?= $current_dir . '/' . $file_name; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                            <?php if (!$is_dir) { ?>
                                <a href="ftp_edit.php?file=<?= urlencode($current_dir . '/' . $file_name); ?>" class="btn btn-warning btn-sm">Editar</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
