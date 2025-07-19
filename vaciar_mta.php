<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$directorio = 'Z:/Servidores/mta';
$mensaje = "";

function eliminarContenidoDirectorio($ruta) {
    if (!is_dir($ruta)) return;

    $archivos = scandir($ruta);
    foreach ($archivos as $archivo) {
        if ($archivo === '.' || $archivo === '..') continue;

        $rutaCompleta = $ruta . DIRECTORY_SEPARATOR . $archivo;

        if (is_dir($rutaCompleta)) {
            eliminarContenidoDirectorio($rutaCompleta); // Elimina recursivamente subdirectorios
            rmdir($rutaCompleta); // Luego elimina la carpeta vacía
        } else {
            unlink($rutaCompleta); // Elimina archivo
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
    eliminarContenidoDirectorio($directorio);
    $mensaje = "✅ ¡La carpeta ha sido vaciada correctamente!";
}
?>

<?php include("header.php"); ?>

<div style="max-width: 600px; margin: auto; background: #1f1f1f; color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 15px rgba(255,0,0,0.2); text-align: center;">
    <h2>⚠ Vaciar Carpeta MTA</h2>
    <p style="color: #ff6b6b; font-weight: bold;">
        Esta opción solo úsela si quiere empezar de cero.<br>
        ❌ <strong>¡Perderá todos los archivos sin capacidad de recuperar!</strong>
    </p>

    <?php if ($mensaje): ?>
        <div style="background: #2ecc71; padding: 10px; border-radius: 5px; margin-top: 15px;">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php else: ?>
        <form method="post">
            <input type="hidden" name="confirmar" value="1">
            <button type="submit" style="padding: 10px 20px; background: #e74c3c; border: none; color: white; font-size: 16px; border-radius: 5px; cursor: pointer;">
                🧨 Vaciar Carpeta
            </button>
        </form>
    <?php endif; ?>
</div>


<?php include 'footer.php';  // cierra body, html, y scripts globales ?>
