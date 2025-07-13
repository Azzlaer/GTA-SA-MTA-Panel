<?php
session_start();

// Configuración de conexión MySQL
$host = "localhost";
$dbname = "latinbat_mta";  // Cambia esto por el nombre real de tu DB
$user = "mta";         // Cambia según tu config
$pass = "yi[JHe*r4Kton*!M";             // Cambia según tu config


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $whatsapp = trim($_POST['whatsapp']);

    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $mensaje = '<div class="alert alert-danger">❌ El nombre de usuario ya existe.</div>';
    } else {
        // Insertar nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO users (username, password, nombre, correo, whatsapp) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $password, $nombre, $correo, $whatsapp])) {
            $mensaje = '<div class="alert alert-success">✅ Cuenta creada correctamente.</div>';
        } else {
            $mensaje = '<div class="alert alert-danger">❌ Ocurrió un error al crear la cuenta.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta de Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
        }
        .form-control, .form-label {
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-light">🛠 Crear cuenta del Panel Web</h2>

    <?= $mensaje ?>

    <form method="POST" class="bg-dark p-4 rounded shadow">
        <div class="mb-3">
            <label class="form-label">Nombre de usuario</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="nombre" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="correo" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">WhatsApp</label>
            <input type="text" name="whatsapp" class="form-control">
        </div>

        <button type="submit" class="btn btn-success w-100">✅ Crear cuenta</button>
        <a href="dashboard.php" class="btn btn-outline-light w-100 mt-2">← Volver al Login</a>
    </form>
</div>

</body>
</html>
