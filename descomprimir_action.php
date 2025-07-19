<?php
$backupDir = "Z:/Servidores/mta_backup";
$targetDir = "Z:/Servidores/mta";

if (!isset($_GET['file'])) {
  echo json_encode(['success' => false, 'message' => 'Archivo no especificado']);
  exit;
}

$file = basename($_GET['file']);
$zipPath = $backupDir . "/" . $file;

if (!file_exists($zipPath)) {
  echo json_encode(['success' => false, 'message' => 'Archivo no encontrado']);
  exit;
}

$zip = new ZipArchive();
if ($zip->open($zipPath) === TRUE) {
  $zip->extractTo($targetDir);
  $zip->close();
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'No se pudo abrir el archivo ZIP']);
}
