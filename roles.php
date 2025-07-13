<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Ruta al archivo acl.xml
$aclFile = "Z:/Servidores/mta/mods/deathmatch/acl.xml";

// Cargar usuarios desde SQLite
require_once './includes/accounts_sqlite.class.php';
$manager = new AccountManagerSQL();
$db = $manager->getConnection();

// Intentar obtener estructura de la tabla para saber columna de usuario
try {
    $result = $db->query("PRAGMA table_info(accounts);");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);

    $userColumn = null;
    foreach ($columns as $col) {
        if (in_array(strtolower($col['name']), ['username', 'name', 'playername', 'user'])) {
            $userColumn = $col['name'];
            break;
        }
    }
    if (!$userColumn) {
        die("No se pudo encontrar la columna de usuario en la tabla accounts.");
    }

    // Obtener usuarios con el nombre de columna detectado
    $stmt = $db->query("SELECT id, {$userColumn} FROM accounts");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error al obtener usuarios: " . $e->getMessage());
}

// Cargar XML y capturar errores
libxml_use_internal_errors(true);
$xml = simplexml_load_file($aclFile);
if ($xml === false) {
    echo "<div class='alert alert-danger'>Error cargando acl.xml: <br>";
    foreach(libxml_get_errors() as $error) {
        echo htmlspecialchars($error->message) . "<br>";
    }
    echo "</div>";
    $xml = null; // no continuamos con XML roto
}

// Manejo formulario asignar rol
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user_id'] ?? 0);
    $newRole = $_POST['role'] ?? '';

    if ($userId > 0 && in_array($newRole, ['admin', 'moderator', 'user'])) {
        // Aquí guardar el rol en ACL (debes ajustar para modificar el archivo XML o tu sistema)
        // Por ahora mostramos mensaje simulado
        echo "<div class='alert alert-success'>Rol '{$newRole}' asignado al usuario ID {$userId}.</div>";
        // TODO: Implementar la modificación real en acl.xml o sistema correspondiente
    } else {
        echo "<div class='alert alert-warning'>Datos inválidos para asignar rol.</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Asignar Roles MTA</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-dark text-light p-4">

<div class="container">
    <h1>Asignar Roles a Usuarios MTA</h1>

    <?php if ($xml): ?>
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="userSelect" class="form-label">Seleccionar Usuario:</label>
                <select id="userSelect" name="user_id" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= htmlspecialchars($u['id']) ?>">
                            <?= htmlspecialchars($u[$userColumn]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="roleSelect" class="form-label">Asignar Rol:</label>
                <select id="roleSelect" name="role" class="form-select" required>
                    <option value="">-- Seleccione --</option>
                    <option value="admin">Admin</option>
                    <option value="moderator">Moderador</option>
                    <option value="user">Usuario</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Asignar Rol</button>
        </form>

        <h2>Roles actuales en ACL</h2>
        <pre style="background:#222; color:#0f0; padding:15px; max-height:300px; overflow:auto;">
<?= htmlspecialchars($xml->asXML()) ?>
        </pre>

    <?php else: ?>
        <div class="alert alert-danger">No se pudo cargar el archivo ACL para mostrar roles.</div>
    <?php endif; ?>
</div>

</body>
</html>
