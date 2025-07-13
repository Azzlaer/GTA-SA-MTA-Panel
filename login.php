<?php
session_start();
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = $_POST['username'];
  $pass = $_POST['password'];
  $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
  $stmt->bind_param('s', $user);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($row = $res->fetch_assoc()) {
    if (password_verify($pass, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      header('Location: dashboard.php'); exit;
    }
  }
  $error = "Credenciales inválidas";
}
?>