<?php
require_once "./includes/accounts_sqlite.class.php";

if (!isset($_POST['id']) || !isset($_POST['name'])) {
    die("❌ Parámetros inválidos.");
}

$id = $_POST['id'];
$username = $_POST['name'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Contraseña</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            width: 400px;
        }

        h2 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #cccccc;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            background-color: #2a2a2a;
            border: none;
            border-radius: 6px;
            color: #ffffff;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .volver {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #00bcd4;
            text-decoration: none;
        }

        .volver:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>🔐 Cambiar contraseña</h2>

    <form method="post" action="procesar_edicion.php">
        <label>Usuario:</label>
        <input type="text" value="<?= htmlspecialchars($username) ?>" disabled>
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Nueva contraseña:</label>
        <input type="password" name="newpass" required>

        <button type="submit" class="btn">Guardar</button>
    </form>

    <a href="users.php" class="volver">⬅ Volver a usuarios</a>
</div>

</body>
</html>
