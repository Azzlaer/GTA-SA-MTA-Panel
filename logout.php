<?php
session_start();

// Elimina todas las variables de sesión
$_SESSION = [];

// Destruye la sesión completamente
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

session_destroy();

// Redirecciona al login (index.php)
header("Location: index.php?logout=1");
exit();
