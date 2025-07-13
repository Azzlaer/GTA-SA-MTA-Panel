<?php
require_once "./includes/accounts_sqlite.class.php";

$manager = new AccountManagerSQL();
$usuarios = $manager->getAccounts();

include 'header.php';  // aquí va el inicio <html>, <head>, <body>, y menú
?>

<div class="container my-4">
    <h2 class="text-light text-center mb-4">👥 Usuarios registrados (MTA)</h2>

    <a href="crear_cuenta.php" class="btn btn-success mb-3">➕ Crear nueva cuenta</a>
<?php
if (isset($_GET['msg'])) {
    echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($_GET['msg']) . '</div>';
}
?>

    <div class="table-responsive">
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Última IP</th>
                    <th>Último Serial</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['last_ip'] ?? $u['ip']) ?></td>
                    <td><?= htmlspecialchars($u['last_serial'] ?? $u['serial']) ?></td>
                    <td>
                        <form method="post" action="editar_pass.php" class="d-inline">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <input type="hidden" name="name" value="<?= $u['name'] ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Editar</button>
                        </form>
						<form method="post" action="eliminar_user.php" class="d-inline" onsubmit="return confirm('¿Eliminar esta cuenta? Esta acción es irreversible.');">
        <input type="hidden" name="id" value="<?= $u['id'] ?>">
        <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
    </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php';  // cierra body, html, y scripts globales ?>
