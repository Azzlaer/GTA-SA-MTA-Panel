<?php
session_start();
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$config = include 'config_rcon.php';

$command = $_POST['command'] ?? '';
$param = $_POST['param'] ?? '';

if (empty($command)) {
    echo json_encode(['error' => 'Comando vacío']);
    exit;
}

// Construir el comando completo (ejemplo)
$fullCommand = trim($command . ' ' . $param);

$socket = @fsockopen($config['host'], $config['port'], $errno, $errstr, 3);
if (!$socket) {
    echo json_encode(['error' => "Error al conectar: $errstr ($errno)"]);
    exit;
}

// Enviar contraseña para autenticar (ajustar según protocolo MTA)
fwrite($socket, "password {$config['password']}\n");

// Leer respuesta de autenticación (simple)
$response = fread($socket, 1024);

// Enviar comando
fwrite($socket, $fullCommand . "\n");

// Leer respuesta del comando (esperar 1 seg)
sleep(1);
$response .= fread($socket, 4096);

fclose($socket);

echo json_encode(['response' => trim($response)]);
