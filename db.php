<?php
$servername = "localhost";
$username = "mta";
$password = "yi[JHe*r4Kton*!M";
$dbname = "latinbat_mta";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
