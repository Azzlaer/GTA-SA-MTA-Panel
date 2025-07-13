<?php
session_start();
include('mysql.php');

if (!isset($_SESSION['user_id'])) exit;

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$whatsapp = $_POST['whatsapp'];

$stmt = $conn->prepare("UPDATE users SET nombre=?, correo=?, whatsapp=? WHERE id=?");
$stmt->bind_param("sssi", $nombre, $correo, $whatsapp, $_SESSION['user_id']);
$stmt->execute();

header("Location: mi_cuenta.php?msg=datos_actualizados");
