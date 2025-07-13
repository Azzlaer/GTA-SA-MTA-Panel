<?php

session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}


?>



<?php


$host = "localhost";
$dbname = "latinbat_mta";  // Cambia esto por el nombre real de tu DB
$user = "mta";         // Cambia según tu config
$pass = "yi[JHe*r4Kton*!M";             // Cambia según tu config


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ DB Error: " . $e->getMessage());
}

// Mensaje global
$msg = '';

// Eliminar usuario
if (isset($_POST['delete_user'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    $msg = "✅ Usuario eliminado.";
}

// Cambiar contraseña
if (isset($_POST['update_password'])) {
    $newpass = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$newpass, $_POST['id']]);
    $msg = "🔐 Contraseña actualizada.";
}

// Modificar datos generales
if (isset($_POST['update_info'])) {
    $stmt = $pdo->prepare("UPDATE users SET username = ?, nombre = ?, correo = ?, whatsapp = ? WHERE id = ?");
    $stmt->execute([
        trim($_POST['username']),
        trim($_POST['nombre']),
        trim($_POST['correo']),
        trim($_POST['whatsapp']),
        $_POST['id']
    ]);
    $msg = "📝 Datos actualizados.";
}

// Cargar usuarios
$stmt = $pdo->query("SELECT id, username, nombre, correo, whatsapp, created_at FROM users ORDER BY id ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: #fff; }
        .table td, .table th { color: #fff; vertical-align: middle; }
        input[type="text"], input[type="email"], input[type="password"] {
            background-color: #1e1e1e; color: #fff; border: 1px solid #555;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">👥 Gestión de Usuarios del Panel</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <table class="table table-dark table-striped table-bordered align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>WhatsApp</th>
                <th>Creado</th>
                <th>Contraseña</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <td><?= $u['id'] ?></td>
                    <td><input type="text" name="username" value="<?= htmlspecialchars($u['username']) ?>" class="form-control form-control-sm" required></td>
                    <td><input type="text" name="nombre" value="<?= htmlspecialchars($u['nombre']) ?>" class="form-control form-control-sm"></td>
                    <td><input type="email" name="correo" value="<?= htmlspecialchars($u['correo']) ?>" class="form-control form-control-sm"></td>
                    <td><input type="text" name="whatsapp" value="<?= htmlspecialchars($u['whatsapp']) ?>" class="form-control form-control-sm"></td>
                    <td><?= $u['created_at'] ?></td>
                    <td>
                        <input type="password" name="new_password" class="form-control form-control-sm" placeholder="Nueva contraseña">
                    </td>
                    <td>
                        <button type="submit" name="update_info" class="btn btn-sm btn-success mb-1 w-100">Modificar</button>
                        <button type="submit" name="update_password" class="btn btn-sm btn-warning mb-1 w-100">Cambiar Pass</button>
                        <button type="submit" name="delete_user" class="btn btn-sm btn-danger w-100" onclick="return confirm('¿Eliminar este usuario?');">Eliminar</button>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-outline-light mt-3">← Volver al panel</a>
</div>

</body>
</html>
