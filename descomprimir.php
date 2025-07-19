<?php
include("header.php");
 ?>

<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}


$backupDir = "Z:/Servidores/mta_backup";
$targetDir = "Z:/Servidores/mta";

$zipFiles = glob($backupDir . '/*.zip');
?>

<div class="container mt-4">
  <h2>📦 Restaurar Backups del Servidor MTA</h2>
  <p>Selecciona un archivo para descomprimirlo en <code><?= $targetDir ?></code></p>

  <?php if (empty($zipFiles)) : ?>
    <div class="alert alert-warning">⚠️ No hay archivos ZIP en el directorio de backup.</div>
  <?php else : ?>
    <ul class="list-group">
      <?php foreach ($zipFiles as $zipFile): 
        $fileName = basename($zipFile);
      ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span>📁 <?= $fileName ?></span>
          <button class="btn btn-success btn-sm decompress-btn" data-file="<?= $fileName ?>">🛠️ Descomprimir</button>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<!-- Barra de progreso -->
<div id="progressContainer" style="display:none;" class="container mt-4">
  <h4>⏳ Progreso de descompresión</h4>
  <div class="progress">
    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">0%</div>
  </div>
  <p id="statusText" class="mt-2">Preparando...</p>
</div>

<script>
document.querySelectorAll('.decompress-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const file = this.getAttribute('data-file');

    document.getElementById('progressContainer').style.display = 'block';
    const progressBar = document.getElementById('progressBar');
    const statusText = document.getElementById('statusText');
    progressBar.style.width = '0%';
    progressBar.innerText = '0%';
    statusText.innerText = 'Iniciando descompresión...';

    fetch('descomprimir_action.php?file=' + encodeURIComponent(file))
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          let percent = 0;
          const interval = setInterval(() => {
            percent += 10;
            progressBar.style.width = percent + '%';
            progressBar.innerText = percent + '%';
            statusText.innerText = 'Descomprimiendo...';
            if (percent >= 100) {
              clearInterval(interval);
              statusText.innerText = '✅ ¡Archivo descomprimido exitosamente!';
            }
          }, 200);
        } else {
          progressBar.classList.remove('progress-bar-animated');
          statusText.innerText = '❌ Error: ' + data.message;
        }
      })
      .catch(err => {
        progressBar.classList.remove('progress-bar-animated');
        statusText.innerText = '❌ Error al conectar con el servidor.';
      });
  });
});
</script>
