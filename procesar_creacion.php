<?php
require_once "./includes/accounts_sqlite.class.php";

$manager = new AccountManagerSQL();

if (isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === '' || $password === '') {
        die("❌ Usuario o contraseña vacíos.");
    }

    // MTA usa SHA-256 para las contraseñas
    $hash = hash('sha256', $password);

    $resultado = $manager->createAccount($username, $hash);

    if ($resultado) {
        echo "✅ Cuenta <strong>$username</strong> creada correctamente.<br>Redirigiendo...";
        echo "<meta http-equiv='refresh' content='2;url=users.php'>";
    } else {
        echo "❌ El usuario ya existe o ocurrió un error.";
    }
} else {
    echo "❌ Faltan datos.";
}
?>
