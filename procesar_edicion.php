<?php
require_once "./includes/accounts_sqlite.class.php";

if (!isset($_POST['id'], $_POST['newpass'])) {
    die("❌ Faltan datos.");
}

$id = $_POST['id'];
$newpass = trim($_POST['newpass']);

if ($newpass === '') {
    die("❌ La contraseña no puede estar vacía.");
}

$hash = hash('sha256', $newpass);

$manager = new AccountManagerSQL();
$ok = $manager->changePassword($id, $hash);

if ($ok) {
    echo "✅ Contraseña actualizada correctamente.<br>Redirigiendo...";
    echo "<meta http-equiv='refresh' content='2;url=users.php'>";
} else {
    echo "❌ No se pudo actualizar la contraseña.";
}
?>
