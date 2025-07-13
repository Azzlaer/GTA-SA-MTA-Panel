<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'header.php';  // aquí va el inicio <html>, <head>, <body>, y menú

$uploadDir = "Z:/Servidores/mta/";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['zipfile']) && $_FILES['zipfile']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['zipfile']['tmp_name'];
        $filename = basename($_FILES['zipfile']['name']);
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($tmpName, $destination)) {
            $message = "<div class='alert alert-success'>Archivo subido correctamente: $filename</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error al mover el archivo subido.</div>";
        }
    } elseif (isset($_POST['action'])) {
        $action = $_POST['action'];
        $filename = $_POST['filename'] ?? '';
        $filepath = $uploadDir . $filename;

        if ($action === 'delete_all') {
            function rrmdir($dir) {
                foreach (glob($dir . '/*') as $file) {
                    is_dir($file) ? rrmdir($file) : unlink($file);
                }
                return true;
            }
            rrmdir($uploadDir);
            mkdir($uploadDir);
            $message = "<div class='alert alert-warning'>Todos los archivos eliminados.</div>";
        }

        if ($action === 'extract' && file_exists($filepath)) {
            $ext = pathinfo($filepath, PATHINFO_EXTENSION);
            if ($ext === 'zip') {
                $zip = new ZipArchive();
                if ($zip->open($filepath) === true) {
                    $zip->extractTo($uploadDir);
                    $zip->close();
                    $message = "<div class='alert alert-info'>ZIP extraído correctamente.</div>";
                } else {
                    $message = "<div class='alert alert-danger'>No se pudo abrir el archivo ZIP.</div>";
                }
            } elseif ($ext === 'rar') {
                $message = "<div class='alert alert-warning'>Soporte para RAR no implementado. Usa ZIP.</div>";
            }
        }

        if ($action === 'delete_uploaded' && file_exists($filepath)) {
            unlink($filepath);
            $message = "<div class='alert alert-danger'>Archivo subido eliminado.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subida de Archivos MTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { background-color: #121212; color: #fff; }
        .progress { height: 25px; }
        .hidden { display: none; }
    </style>
</head>
<body class="p-4">
    <div class="container" style="max-width: 600px">
	<h2 class="mb-4">Sube los archivos de tu servidor MTA comprimido en ZIP y Extraelo</h2>
        <h2 class="mb-4">📦 Subida de archivos MTA (ZIP)</h2>

        <?= $message ?>

        <form id="uploadForm" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <input class="form-control bg-dark text-white" type="file" name="zipfile" accept=".zip,.rar" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Subir Archivo</button>
        </form>

        <div class="progress mt-3 hidden" id="uploadProgress">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%"></div>
        </div>

        <?php
        $lastFile = null;
        foreach (scandir($uploadDir) as $file) {
            if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['zip', 'rar'])) {
                $lastFile = $file;
            }
        }
        if ($lastFile): ?>
            <hr>
            <h5 class="mt-4">🛠️ Acciones para: <?= htmlspecialchars($lastFile) ?></h5>
            <form method="post" class="d-grid gap-2">
                <input type="hidden" name="filename" value="<?= htmlspecialchars($lastFile) ?>">
                <button class="btn btn-info" name="action" value="extract">📂 Extraer Archivo</button>
                <button class="btn btn-danger" name="action" value="delete_uploaded">🗑️ Eliminar Archivo Subido</button>
                <button class="btn btn-warning" name="action" value="delete_all">💣 Eliminar Todo en el Directorio</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        const form = document.getElementById("uploadForm");
        const progressBar = document.querySelector(".progress-bar");
        const progressWrap = document.getElementById("uploadProgress");

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressWrap.classList.remove("hidden");
                    progressBar.style.width = percent + "%";
                    progressBar.textContent = percent + "%";
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    location.reload();
                }
            };

            xhr.send(formData);
        });
    </script>
</body>
<?php include 'footer.php';  // cierra body, html, y scripts globales ?>
</html>
