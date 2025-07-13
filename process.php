<?php
session_start();
include 'db.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'register') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (!empty($username) && !empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('Registro exitoso'); window.location='index.php';</script>";
            } else {
                echo "<script>alert('Error al registrar usuario'); window.location='index.php';</script>";
            }
        } else {
            echo "<script>alert('Por favor, complete todos los campos'); window.location='index.php';</script>";
        }
    }

    if ($action == 'login') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (!empty($username) && !empty($password)) {
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    // ✅ Guardar ID y username en sesión
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];

                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "<script>alert('Contraseña incorrecta'); window.location='index.php';</script>";
                }
            } else {
                echo "<script>alert('Usuario no encontrado'); window.location='index.php';</script>";
            }
        } else {
            echo "<script>alert('Por favor, complete todos los campos'); window.location='index.php';</script>";
        }
    }
}
?>