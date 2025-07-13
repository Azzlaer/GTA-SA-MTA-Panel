<?php
session_start();

// Verifica sesión
if (!isset($_SESSION['username'])) {
    die("Acceso denegado: No ha iniciado sesión.");
}

// Incluir clase de cuentas
require_once "./includes/accounts_sqlite.class.php";

// Verificar si viene por POST y tiene ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    die("Acceso denegado");
}

$id = intval($_POST['id']);

try {
    // Inicializar clase de cuentas
    $manager = new AccountManagerSQL();

    // Obtener conexión
    if (method_exists($manager, 'getConnection')) {
        $db = $manager->getConnection();
    } elseif (property_exists($manager, 'db')) {
        $db = $manager->db;
    } else {
        throw new Exception("No se pudo acceder a la base de datos.");
    }

    // Iniciar transacción
    $db->beginTransaction();

    // Eliminar registros relacionados
    $stmt = $db->prepare("DELETE FROM userdata WHERE userid = ?");
    $stmt->execute([$id]);

    $stmt = $db->prepare("DELETE FROM serialusage WHERE userid = ?");
    $stmt->execute([$id]);

    // Eliminar cuenta
    $stmt = $db->prepare("DELETE FROM accounts WHERE id = ?");
    $stmt->execute([$id]);

    $db->commit();

    // Redirigir con mensaje de éxito
    header("Location: users.php?msg=Usuario eliminado correctamente");
    exit();

} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    die("Error al eliminar usuario: " . $e->getMessage());
}
?>
